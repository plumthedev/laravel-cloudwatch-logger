<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger;

use CloudwatchLogger\CloudwatchLoggerServiceProvider;
use CloudwatchLogger\Config\LaravelCloudwatchConfig;
use CloudwatchLogger\Logger\LaravelCloudwatchLogger;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;

class CloudwatchLoggerServiceProviderTest extends TestCase
{
    public function testRegisterAliases(): void
    {
        $this->assertFalse($this->app->has(LaravelCloudwatchConfig::alias()));
        $this->assertFalse($this->app->has(LaravelCloudwatchLogger::alias()));

        $this->app->register(CloudwatchLoggerServiceProvider::class);
        $this->assertTrue($this->app->has(LaravelCloudwatchConfig::alias()));
        $this->assertTrue($this->app->has(LaravelCloudwatchLogger::alias()));
    }

    public function testDoesNotMergeConfigWhenItIsPresentAlready(): void
    {
        $this->assertNull(Config::get('logging.cloudwatch'));

        $definedConfig = ['name' => 'Cloudwatch Test'];
        Config::set('logging.cloudwatch', $definedConfig);

        $this->app->register(CloudwatchLoggerServiceProvider::class);
        $this->assertEquals($definedConfig, Config::get('logging.cloudwatch'));
    }
}
