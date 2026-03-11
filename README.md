# Slash PHP SDK

PHP SDK for Slash API - 用于接入 Slash API 的官方 PHP 客户端库。

## 安装

你可以使用 Composer 来安装此 SDK：

```bash
composer require xiaoye0402/slash-php-sdk
```

## 快速开始

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use SlashPhpSdk\SlashPhpSdk;

// 创建SDK实例
$sdk = new SlashPhpSdk([
    'api_key' => 'your-api-key-here',
    'base_url' => 'https://api.joinslash.com'
]);
//必须在协程中运行
\Swoole\Coroutine\run(function () use ($sdk) {
    // 获取账户列表
    $accounts = $sdk->account()->list();
    print_r($accounts);

    // 创建新账户
    $newAccount = $sdk->account()->create([
        'name' => 'Example Account',
        'type' => 'checking',
        'balance' => 1000.00
    ]);
    print_r($newAccount);
});
```

## 配置选项

创建 SDK 实例时，可以传递以下配置项：

- `api_key`: API 密钥，用于身份验证
- `bearer_token`: Bearer 令牌，用于身份验证
- `username` 和 `password`: 用户名和密码，用于基本身份验证
- `base_url`: API 基础 URL，默认为 https://api.joinslash.com
- `max_retries`: 请求失败时的最大重试次数，默认为 2
- `timeout`: 请求超时时间，默认为 30 秒
- `default_headers`: 要发送的默认请求头
- `default_query`: 要附加的默认查询参数

## 资源

SDK 提供了以下资源类：

- `$sdk->account()` - 账户相关操作
- `$sdk->transaction()` - 交易相关操作
- `$sdk->card()` - 卡片相关操作

资源类都提供 `list`, `retrieve`, `create` 等方法。

## 异常处理

SDK 在遇到错误时会抛出相应的异常：

- `ApiConnectionException` - 连接错误
- `AuthenticationException` - 身份验证失败
- `NotFoundException` - 资源未找到
- `RateLimitException` - 达到速率限制
- 等等...

## 开发

如果你想要为 SDK 贡献代码：

1. Fork 仓库
2. 创建特性分支
3. 提交你的修改
4. 推送到分支
5. 创建 Pull Request

### 运行测试

```bash
composer test
```

或者使用覆盖率：

```bash
composer test-coverage
```

## 许可证

本项目采用 MIT 许可证 - 请参阅 [LICENSE](LICENSE) 文件了解详情。