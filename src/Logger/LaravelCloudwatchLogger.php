<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Logger;

use CloudwatchLogger\Config\LaravelCloudwatchConfig;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/** @phpstan-import-type CloudwatchLoggerConfigArray from PhpCloudwatchLogger */
class LaravelCloudwatchLogger extends PhpCloudwatchLogger
{
    public function __construct(
        LoggerFactory $factory,
        ContainerInterface $container,
        private readonly LaravelCloudwatchConfig $config,
    )
    {
        parent::__construct($factory, $container);
    }

    public static function alias(): string
    {
        return 'cloudwatch.logger';
    }

    /**
     * @param CloudwatchLoggerConfigArray $config
     * @return CloudwatchLoggerConfigArray
     */
    protected function composeConfig(array $config): array
    {
        return [
            'batch_size' => $config['batch_size'] ?? $this->config->batchSize(),
            'credentials' => [
                'key' => $config['credentials']['key'] ?? $this->config->key(),
                'secret' => $config['credentials']['secret'] ?? $this->config->secret(),
            ],
            'formatter' => $config['formatter'] ?? $this->config->formatter(),
            'group_name' => $config['group_name'] ?? $this->config->groupName(),
            'name' => $config['name'] ?? $this->config->name(),
            'region' => $config['region'] ?? $this->config->region(),
            'retention_days' => $config['retention_days'] ?? $this->config->retentionDays(),
            'stream_name' => $config['stream_name'] ?? $this->config->streamName(),
            'version' => $config['version'] ?? $this->config->version(),
        ];
    }

    /** @inheritdoc */
    public function __invoke(array $config): LoggerInterface
    {
        return parent::__invoke(
            config: $this->composeConfig($config),
        );
    }
}
