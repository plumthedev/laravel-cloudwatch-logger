<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Config;

use CloudwatchLogger\Config\LaravelCloudwatchConfig;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Tests\CloudwatchLogger\TestCase;

class LaravelCloudwatchConfigTest extends TestCase
{
    private LaravelCloudwatchConfig $config;

    public function testName(): void
    {
        Config::set('logging.cloudwatch.name');
        $this->assertSame('Laravel Cloudwatch Logger', $this->config->name());

        Config::set('logging.cloudwatch.name', 'My Superfluous App');
        $this->assertSame('My Superfluous App', $this->config->name());
    }

    public function testKey(): void
    {
        Config::set('logging.cloudwatch.credentials.key', 'super-secret-key');
        $this->assertSame('super-secret-key', $this->config->key());
    }

    public function testMissingKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->config->key();
    }

    public function testSecret(): void
    {
        Config::set('logging.cloudwatch.credentials.secret', 'super-secret-value');
        $this->assertSame('super-secret-value', $this->config->secret());
    }

    public function testMissingSecret(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->config->secret();
    }

    public function testRegion(): void
    {
        Config::set('logging.cloudwatch.region');
        $this->assertSame('eu-central-1', $this->config->region());

        Config::set('logging.cloudwatch.region', 'us-east-1');
        $this->assertSame('us-east-1', $this->config->region());
    }

    public function testVersion(): void
    {
        Config::set('logging.cloudwatch.version');
        $this->assertSame('latest', $this->config->version());

        Config::set('logging.cloudwatch.version', '2.1.37');
        $this->assertSame('2.1.37', $this->config->version());
    }

    public function testGroupName(): void
    {
        Config::set('logging.cloudwatch.group_name', 'api_v1');
        $this->assertSame('api_v1', $this->config->groupName());
    }

    public function testMissingGroupName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->config->groupName();
    }

    public function testStreamName(): void
    {
        Config::set('logging.cloudwatch.stream_name', 'payments');
        $this->assertSame('payments', $this->config->streamName());
    }

    public function testMissingStreamName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->config->streamName();
    }

    public function testRetentionDays(): void
    {
        Config::set('logging.cloudwatch.retention_days');
        $this->assertSame(14, $this->config->retentionDays());

        Config::set('logging.cloudwatch.retention_days', 180);
        $this->assertSame(180, $this->config->retentionDays());
    }

    public function testBatchSize(): void
    {
        Config::set('logging.cloudwatch.batch_size');
        $this->assertSame(25, $this->config->batchSize());

        Config::set('logging.cloudwatch.batch_size', 10);
        $this->assertSame(10, $this->config->batchSize());
    }

    public function testFormatter(): void
    {
        $this->assertNull($this->config->formatter());

        Config::set('logging.cloudwatch.formatter', LineFormatter::class);
        $this->assertSame(LineFormatter::class, $this->config->formatter());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = $this->app->make(LaravelCloudwatchConfig::class);
    }
}
