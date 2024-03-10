<?php

declare(strict_types = 1);

namespace CloudwatchLogger\Config;

use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use Monolog\Formatter\FormatterInterface;

class LaravelCloudwatchConfig
{
    public function __construct(protected readonly Repository $config)
    {
    }

    public static function alias(): string
    {
        return 'cloudwatch.logger.config';
    }

    public function name(): string
    {
        $name = $this->config->get('logging.cloudwatch.name');

        if (is_string($name)) {
            return $name;
        }

        return 'Laravel Cloudwatch Logger';
    }

    public function key(): string
    {
        $key = $this->config->get('logging.cloudwatch.credentials.key');

        if (is_string($key)) {
            return $key;
        }

        throw new InvalidArgumentException(
            'Please set the default AWS CloudWatch Secret Key in the configuration under [logging.cloudwatch.credentials.key]',
        );
    }

    public function secret(): string
    {
        $secret = $this->config->get('logging.cloudwatch.credentials.secret');

        if (is_string($secret)) {
            return $secret;
        }

        throw new InvalidArgumentException(
            'Please set the default AWS CloudWatch Secret in the configuration under [logging.cloudwatch.credentials.secret]',
        );
    }

    public function region(): string
    {
        $region = $this->config->get('logging.cloudwatch.region');

        if (is_string($region)) {
            return $region;
        }

        return 'eu-central-1';
    }

    public function version(): string
    {
        $version = $this->config->get('logging.cloudwatch.version');

        if (is_string($version)) {
            return $version;
        }

        return 'latest';
    }

    public function groupName(): string
    {
        $groupName = $this->config->get('logging.cloudwatch.group_name');

        if (is_string($groupName)) {
            return $groupName;
        }

        throw new InvalidArgumentException(
            'Please set the default AWS CloudWatch Group Name in the configuration under [logging.cloudwatch.group_name]',
        );
    }

    public function streamName(): string
    {
        $streamName = $this->config->get('logging.cloudwatch.stream_name');

        if (is_string($streamName)) {
            return $streamName;
        }

        throw new InvalidArgumentException(
            'Please set the default AWS CloudWatch Stream Name in the configuration under [logging.cloudwatch.stream_name]',
        );
    }

    public function retentionDays(): int
    {
        $days = $this->config->get('logging.cloudwatch.retention_days');

        if (is_int($days) && $days > 0) {
            return $days;
        }

        return 14;
    }

    public function batchSize(): int
    {
        $batch = $this->config->get('logging.cloudwatch.batch_size');

        if (is_int($batch) && $batch > 0) {
            return $batch;
        }

        return 25;
    }

    /** @return class-string<FormatterInterface>|null */
    public function formatter(): string|null
    {
        $formatter = $this->config->get('logging.cloudwatch.formatter');

        if (is_string($formatter) && is_a($formatter, FormatterInterface::class, true)) {
            return $formatter;
        }

        return null;
    }
}
