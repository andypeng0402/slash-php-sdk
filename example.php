<?php

require_once __DIR__.'/vendor/autoload.php';

use SlashPhpSdk\SlashPhpSdk;

// 创建SDK实例
$sdk = new SlashPhpSdk([
    'api_key' => $_ENV['SLASH_SDK_API_KEY'] ?? 'your-api-key-here',
    'base_url' => $_ENV['SLASH_SDK_BASE_URL'] ?? 'https://api.joinslash.com'
]);

// 获取账户列表
try {
    $accounts = $sdk->account()->list();
    print_r($accounts);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 创建新账户
try {
    $newAccount = $sdk->account()->create([
        'name' => 'Example Account',
        'type' => 'checking',
        'balance' => 1000.00
    ]);
    print_r($newAccount);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// 获取特定账户
if (isset($newAccount['id'])) {
    try {
        $account = $sdk->account()->retrieve($newAccount['id']);
        print_r($account);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// 更新账户
if (isset($newAccount['id'])) {
    try {
        $updatedAccount = $sdk->account()->update($newAccount['id'], [
            'name' => 'Updated Account Name'
        ]);
        print_r($updatedAccount);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// 获取交易列表
try {
    $transactions = $sdk->transaction()->list(['limit' => 10]);
    print_r($transactions);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}