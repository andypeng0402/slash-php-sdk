<?php

namespace SlashPhpSdk\Tests;

use PHPUnit\Framework\TestCase;
use SlashPhpSdk\SlashPhpSdk;

class SlashPhpSdkTest extends TestCase
{
    public function testCanCreateSdkInstance()
    {
        $sdk = new SlashPhpSdk([
            'api_key' => 'test-key',
            'base_url' => 'https://api.example.com'
        ]);

        $this->assertInstanceOf(SlashPhpSdk::class, $sdk);
    }

    public function testCanGetClient()
    {
        $sdk = new SlashPhpSdk([
            'api_key' => 'test-key',
            'base_url' => 'https://api.example.com'
        ]);

        $client = $sdk->getClient();
        $this->assertEquals('test-key', $client->getApiKey());
        $this->assertEquals('https://api.example.com', $client->getBaseUrl());
    }

    public function testCanGetAccountResource()
    {
        $sdk = new SlashPhpSdk([
            'api_key' => 'test-key'
        ]);

        $accountResource = $sdk->account();
        $this->assertNotNull($accountResource);
    }

    public function testCanGetTransactionResource()
    {
        $sdk = new SlashPhpSdk([
            'api_key' => 'test-key'
        ]);

        $transactionResource = $sdk->transaction();
        $this->assertNotNull($transactionResource);
    }
}