<?php

declare(strict_types = 1);

namespace CloudwatchLogger;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;

class CloudwatchLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->alias(Config\LaravelCloudwatchConfig::class, Config\LaravelCloudwatchConfig::alias());
        $this->app->alias(Logger\LaravelCloudwatchLogger::class, Logger\LaravelCloudwatchLogger::alias());
    }

    public function boot(LogManager $logManager, Repository $config): void
    {
        $logManager->extend(
            'cloudwatch',
            // phpcs:ignore SlevomatCodingStandard.Functions.StaticClosure.ClosureNotStatic
            fn (Application $app, array $config) => $app->call(
                Logger\LaravelCloudwatchLogger::class,
                ['config' => $config],
            ),
        );

        if (! $config->has('logging.cloudwatch')) {
            $this->mergeConfigFrom(
                path: sprintf('%s/../config/cloudwatch.php', __DIR__),
                key: 'logging.cloudwatch',
            );
        }
    }
}
