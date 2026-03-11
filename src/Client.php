<?php

namespace SlashPhpSdk;

use Swoole\Coroutine\Http\Client as SwooleClient;
use SlashPhpSdk\Exceptions as Exceptions;

/**
 * 主客户端类，提供与 Slash API 交互的功能
 */
class Client
{
    private $httpClient;
    private $apiKey;
    private $bearerToken;
    private $username;
    private $password;
    private $baseUrl;
    private $maxRetries;
    private $defaultHeaders;
    private $defaultQuery;
    private $parsedUrl;
    
    public function __construct(array $config = [])
    {
        // 从环境变量获取配置值，如果没有提供则使用默认值
        $this->apiKey = $config['api_key'] ?? $_ENV['SLASH_SDK_API_KEY'] ?? null;
        $this->bearerToken = $config['bearer_token'] ?? $_ENV['SLASH_SDK_BEARER_TOKEN'] ?? null;
        $this->username = $config['username'] ?? $_ENV['SLASH_SDK_USERNAME'] ?? null;
        $this->password = $config['password'] ?? $_ENV['SLASH_SDK_PASSWORD'] ?? null;
        
        $this->baseUrl = $config['base_url'] ?? $_ENV['SLASH_SDK_BASE_URL'] ?? 'https://api.joinslash.com';
        $this->maxRetries = $config['max_retries'] ?? 2;
        $this->defaultHeaders = $config['default_headers'] ?? [];
        $this->defaultQuery = $config['default_query'] ?? [];
        
        // 解析基础 URL 并初始化 HTTP 客户端
        $this->parsedUrl = parse_url($this->baseUrl);
        $this->httpClient = $this->createHttpClient($config);
    }
    
    /**
     * 创建 HTTP 客户端
     */
    private function createHttpClient(array $config = []): SwooleClient
    {
        $parsedUrl = $this->parsedUrl;
        $host = $parsedUrl['host'];
        $port = $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80);
        $ssl = $parsedUrl['scheme'] === 'https';
        
        $httpClient = new SwooleClient($host, $port, $ssl);
        $httpClient->set([
            'timeout' => $config['timeout'] ?? 30,
        ]);
        
        return $httpClient;
    }
    
    /**
     * 根据 baseURL 创建临时的 HTTP 客户端
     */
    private function createHttpClientForUrl(string $baseUrl, array $config = []): SwooleClient
    {
        $parsedUrl = parse_url($baseUrl);
        $host = $parsedUrl['host'];
        $port = $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80);
        $ssl = $parsedUrl['scheme'] === 'https';
        
        $httpClient = new SwooleClient($host, $port, $ssl);
        $httpClient->set([
            'timeout' => $config['timeout'] ?? 30,
        ]);
        
        return $httpClient;
    }
    
    /**
     * 获取默认请求头
     */
    private function getDefaultHeaders(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'SlashPhpSdk/0.1.0',
        ];
        
        $authHeaders = $this->getAuthHeaders();
        $headers = array_merge($headers, $authHeaders);
        $headers = array_merge($headers, $this->defaultHeaders);
        
        return $headers;
    }
    
    /**
     * 获取认证相关的头部
     */
    private function getAuthHeaders(): array
    {
        $headers = [];
        
        if ($this->apiKey) {
            $headers['X-API-Key'] = $this->apiKey;
        }
        
        if ($this->bearerToken) {
            $headers['Authorization'] = 'Bearer ' . $this->bearerToken;
        }
        
        if ($this->username && $this->password) {
            $credentials = base64_encode($this->username . ':' . $this->password);
            $headers['Authorization'] = 'Basic ' . $credentials;
        }
        
        return $headers;
    }
    
    /**
     * 发送 GET 请求
     * @param string $endpoint 请求端点
     * @param array $params 查询参数
     * @param string|null $baseUrl 自定义基础 URL（可选）
     */
    public function get(string $endpoint, array $params = [], ?string $baseUrl = null): array
    {
        return $this->request('GET', $endpoint, ['query' => array_merge($this->defaultQuery, $params)], $baseUrl);
    }
    
    /**
     * 发送 POST 请求
     * @param string $endpoint 请求端点
     * @param array $data 请求数据
     * @param string|null $baseUrl 自定义基础 URL（可选）
     */
    public function post(string $endpoint, array $data = [], ?string $baseUrl = null): array
    {
        return $this->request('POST', $endpoint, ['json' => $data], $baseUrl);
    }
    
    /**
     * 发送 PUT 请求
     * @param string $endpoint 请求端点
     * @param array $data 请求数据
     * @param string|null $baseUrl 自定义基础 URL（可选）
     */
    public function put(string $endpoint, array $data = [], ?string $baseUrl = null): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data], $baseUrl);
    }
    
    /**
     * 发送 PATCH 请求
     * @param string $endpoint 请求端点
     * @param array $data 请求数据
     * @param string|null $baseUrl 自定义基础 URL（可选）
     */
    public function patch(string $endpoint, array $data = [], ?string $baseUrl = null): array
    {
        return $this->request('PATCH', $endpoint, ['json' => $data], $baseUrl);
    }
    
    /**
     * 发送 DELETE 请求
     * @param string $endpoint 请求端点
     * @param array $params 查询参数
     * @param string|null $baseUrl 自定义基础 URL（可选）
     */
    public function delete(string $endpoint, array $params = [], ?string $baseUrl = null): array
    {
        return $this->request('DELETE', $endpoint, ['query' => $params], $baseUrl);
    }
    
    /**
     * 执行 HTTP 请求
     * @param string $method 请求方法
     * @param string $endpoint 请求端点
     * @param array $options 请求选项
     * @param string|null $baseUrl 自定义基础 URL（可选）
     */
    private function request(string $method, string $endpoint, array $options = [], ?string $baseUrl = null): array
    {
        $retries = 0;
        
        // 确定使用哪个 baseURL
        $currentBaseUrl = $baseUrl ?? $this->baseUrl;
        
        // 如果使用了自定义 baseURL，需要创建临时的 HTTP 客户端
        $useCustomClient = ($baseUrl !== null && $baseUrl !== $this->baseUrl);
        $originalHttpClient = null;
        
        if ($useCustomClient) {
            $originalHttpClient = $this->httpClient;
            $this->httpClient = $this->createHttpClientForUrl($currentBaseUrl);
        }
        
        // 解析当前 baseURL
        $parsedUrl = parse_url($currentBaseUrl);
        
        while (true) {
            try {
                // 构建完整 URL
                $fullUrl = $currentBaseUrl . $endpoint;
                
                // 合并查询参数
                $query = [];
                if (isset($options['query']) && is_array($options['query'])) {
                    $query = array_merge($this->defaultQuery, $options['query']);
                } else {
                    $query = $this->defaultQuery;
                }
                
                if (!empty($query)) {
                    $fullUrl .= '?' . http_build_query($query);
                }
                
                // 解析 URL 获取路径和查询字符串
                $parsedEndpoint = parse_url($fullUrl);
                $path = $parsedEndpoint['path'] ?? '/';
                $path .= isset($parsedEndpoint['query']) ? '?' . $parsedEndpoint['query'] : '';
                
                // 准备请求数据
                $headers = $this->getDefaultHeaders();
                
                // 设置请求体
                $body = null;
                if (isset($options['json']) && $options['json']) {
                    $headers['Content-Type'] = 'application/json';
                    $body = json_encode($options['json']);
                }
                
                // 设置 Swoole 客户端
                $this->httpClient->setMethod($method);
                $this->httpClient->setHeaders($headers);
                $this->httpClient->setData($body);
                $this->httpClient->execute($path);
                
                // 获取响应
                $responseHeaders = $this->httpClient->getHeaders();
                $responseBody = $this->httpClient->getBody();
                $statusCode = $this->httpClient->getStatusCode();
                
                if ($this->httpClient->errCode) {
                    throw new Exceptions\ApiConnectionException("Connection error: " . socket_strerror($this->httpClient->errCode));
                }
                
                if ($statusCode <= 0) {
                    throw new Exceptions\ApiConnectionException("Request failed with status code: {$statusCode}");
                }
                
                // 恢复原始 HTTP 客户端
                if ($useCustomClient) {
                    $this->httpClient = $originalHttpClient;
                }
                
                return $this->handleResponse($statusCode, $responseBody, $responseHeaders);
                
            } catch (\Swoole\Coroutine\Http\Client\Exception $e) {
                // 恢复原始 HTTP 客户端
                if ($useCustomClient) {
                    $this->httpClient = $originalHttpClient;
                }
                throw new Exceptions\ApiConnectionException("Connection error: " . $e->getMessage());
            } catch (\Exception $e) {
                // 恢复原始 HTTP 客户端
                if ($useCustomClient) {
                    $this->httpClient = $originalHttpClient;
                }
                
                $errorType = get_class($e);
                
                if ($errorType === Exceptions\ApiConnectionException::class) {
                    throw $e;
                }
                
                $statusCode = $this->httpClient->errCode ?? 0;
                
                // 检查是否需要重试
                if ($this->shouldRetry($statusCode, $retries)) {
                    $retries++;
                    $delay = $this->calculateRetryDelay($retries);
                    
                    // 使用协程 sleep
                    \Swoole\Coroutine::sleep($delay);
                    continue;
                }
                
                // 根据状态码抛出适当的异常
                $responseHeaders = $this->httpClient->getHeaders() ?? [];
                $responseBody = $this->httpClient->getBody() ?? '';
                
                throw $this->makeStatusErrorWithRawData($statusCode, $responseBody, $responseHeaders);
            }
        }
    }
    
    /**
     * 处理响应
     */
    private function handleResponse(int $statusCode, string $body, array $headers): array
    {
        if ($statusCode >= 400) {
            $mockResponse = (object)[
                'getStatusCode' => function() use ($statusCode) { return $statusCode; },
                'getBody' => function() use ($body) { return $body; }
            ];
            
            throw $this->makeStatusErrorWithRawData($statusCode, $body, $headers);
        }
        
        if (empty($body)) {
            return [];
        }
        
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exceptions\ApiResponseValidationException("Invalid JSON response: " . json_last_error_msg());
        }
        
        return $data;
    }
    
    /**
     * 根据状态码判断是否需要重试
     */
    private function shouldRetry(int $statusCode, int $retries): bool
    {
        if ($retries >= $this->maxRetries) {
            return false;
        }
        
        return $statusCode === 408 || $statusCode === 409 || $statusCode === 429 || $statusCode >= 500;
    }
    
    /**
     * 计算重试延迟时间（秒）
     */
    private function calculateRetryDelay(int $attempt): float
    {
        $initialDelay = 0.5;
        $maxDelay = 8.0;
        
        $delay = $initialDelay * pow(2, $attempt - 1);
        $jitter = 1 - 0.25 * mt_rand() / mt_getrandmax();
        
        return min($delay * $jitter, $maxDelay);
    }
    
    /**
     * 创建状态错误异常
     */
    private function makeStatusErrorWithRawData(int $statusCode, string $body, array $headers): \Exception
    {
        $decodedBody = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $decodedBody = $body;
        }
        
        $message = "API Error: Status Code {$statusCode}";
        if ($body) {
            $message .= " - " . $body;
        }
        
        $mockResponse = (object)[
            'getStatusCode' => function() use ($statusCode) { return $statusCode; },
            'getBody' => function() use ($body) { return $body; }
        ];
        
        switch ($statusCode) {
            case 400:
                return new Exceptions\BadRequestException($message, $mockResponse, $decodedBody);
            case 401:
                return new Exceptions\AuthenticationException($message, $mockResponse, $decodedBody);
            case 403:
                return new Exceptions\PermissionDeniedException($message, $mockResponse, $decodedBody);
            case 404:
                return new Exceptions\NotFoundException($message, $mockResponse, $decodedBody);
            case 409:
                return new Exceptions\ConflictException($message, $mockResponse, $decodedBody);
            case 422:
                return new Exceptions\UnprocessableEntityException($message, $mockResponse, $decodedBody);
            case 429:
                return new Exceptions\RateLimitException($message, $mockResponse, $decodedBody);
            case 500:
                return new Exceptions\InternalServerErrorException($message, $mockResponse, $decodedBody);
            default:
                if ($statusCode >= 500) {
                    return new Exceptions\InternalServerErrorException($message, $mockResponse, $decodedBody);
                }
                return new Exceptions\ApiStatusException($message, $mockResponse, $decodedBody);
        }
    }
    
    /**
     * 获取 API 密钥
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }
    
    /**
     * 获取承载令牌
     */
    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }
    
    /**
     * 获取用户名
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    /**
     * 获取密码
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * 获取基础 URL
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    /**
     * 获取最大重试次数
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }
    
    /**
     * 设置新的 API 密钥
     */
    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
        $this->refreshHttpClient();
    }
    
    /**
     * 设置新的承载令牌
     */
    public function setBearerToken(string $bearerToken): void
    {
        $this->bearerToken = $bearerToken;
        $this->refreshHttpClient();
    }
    
    /**
     * 刷新 HTTP 客户端
     */
    private function refreshHttpClient(): void
    {
        $this->httpClient = $this->createHttpClient([]);
    }
}