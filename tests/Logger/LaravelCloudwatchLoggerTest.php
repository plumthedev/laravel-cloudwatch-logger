<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger\Logger;

use CloudwatchLogger\Client\ClientConfig;
use CloudwatchLogger\Handler\HandlerConfig;
use CloudwatchLogger\Logger\LoggerFactory;
use CloudwatchLogger\Logger\PhpCloudwatchLogger;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Mockery;
use Mockery\MockInterface;
use Monolog\Formatter\LineFormatter;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tests\CloudwatchLogger\TestCase;

/** @phpstan-import-type CloudwatchLoggerConfigArray from PhpCloudwatchLogger */
class LaravelCloudwatchLoggerTest extends TestCase
{
    /** @return array<string, array<string, mixed>> */
    // phpcs:ignore SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
    public static function loggerConfigExamples(): iterable
    {
        $formatter = Mockery::mock(LineFormatter::class);

        return [
            'create by cloudwatch config' => [
                'cloudwatch config' => [
                    'batch_size' => 1,
                    'credentials' => [
                        'key' => 'secret-key',
                        'secret' => 'secret-value',
                    ],
                    'formatter' => null,
                    'group_name' => 'Laravel App',
                    'name' => 'Cloudwatch Logger Test',
                    'region' => 'us-west-2',
                    'retention_days' => 180,
                    'stream_name' => 'api',
                    'version' => '2.1.37',
                ],
                'expected client config' => ClientConfig::create(
                    key: 'secret-key',
                    secret: 'secret-value',
                    region: 'us-west-2',
                    version: '2.1.37',
                ),
                'expected handler config' => HandlerConfig::create(
                    groupName: 'Laravel App',
                    streamName: 'api',
                    retentionDays: 180,
                    batchSize: 1,
                ),
                'expected name' => 'Cloudwatch Logger Test',
                'logger config' => [
                    'driver' => 'cloudwatch',
                ],
            ],
            'create by config fusion' => [
                'cloudwatch config' => [
                    'batch_size' => 1,
                    'credentials' => [
                        'key' => 'secret-key',
                        'secret' => 'secret-value',
                    ],
                    'formatter' => $formatter::class,
                    'group_name' => 'Laravel App',
                    'name' => 'Cloudwatch Logger Test',
                    'region' => 'us-west-2',
                    'retention_days' => 180,
                    'stream_name' => 'api',
                    'version' => '2.1.37',
                ],
                'expected client config' => ClientConfig::create(
                    key: 'secret-key',
                    secret: 'secret-value',
                    region: 'us-west-2',
                    version: '2.1.37',
                ),
                'expected handler config' => HandlerConfig::create(
                    groupName: 'api_v1',
                    streamName: 'payments',
                    retentionDays: 30,
                    batchSize: 10,
                    formatter: $formatter,
                ),
                'expected name' => 'V1 Payments Logger',
                'logger config' => [
                    'batch_size' => 10,
                    'driver' => 'cloudwatch',
                    'formatter' => $formatter::class,
                    'group_name' => 'api_v1',
                    'name' => 'V1 Payments Logger',
                    'retention_days' => 30,
                    'stream_name' => 'payments',
                ],
            ],
            'create by logger config' => [
                'cloudwatch config' => [],
                'expected client config' => ClientConfig::create(
                    key: 'secret-key',
                    secret: 'secret-value',
                    region: 'us-west-2',
                    version: '2.1.37',
                ),
                'expected handler config' => HandlerConfig::create(
                    groupName: 'Laravel App',
                    streamName: 'api',
                    retentionDays: 180,
                    batchSize: 1,
                ),
                'expected name' => 'Cloudwatch Logger Test',
                'logger config' => [
                    'batch_size' => 1,
                    'credentials' => [
                        'key' => 'secret-key',
                        'secret' => 'secret-value',
                    ],
                    'driver' => 'cloudwatch',
                    'formatter' => null,
                    'group_name' => 'Laravel App',
                    'name' => 'Cloudwatch Logger Test',
                    'region' => 'us-west-2',
                    'retention_days' => 180,
                    'stream_name' => 'api',
                    'version' => '2.1.37',
                ],
            ],
        ];
    }

    /**
     * @dataProvider loggerConfigExamples
     * @param CloudwatchLoggerConfigArray $cloudwatchConfig
     * @param CloudwatchLoggerConfigArray $loggerConfig
     */
    public function testCreateByConfig(
        array $cloudwatchConfig,
        ClientConfig $expectedClientConfig,
        HandlerConfig $expectedHandlerConfig,
        string $expectedName,
        array $loggerConfig,
    ): void
    {
        if ($expectedHandlerConfig->formatter !== null) {
            $this->mock(
                ContainerInterface::class,
                static fn (ContainerInterface|MockInterface $mock) => $mock
                    ->shouldReceive('get')
                    ->with($expectedHandlerConfig->formatter::class)
                    ->andReturn($expectedHandlerConfig->formatter)
                    ->getMock(),
            );
        }

        $this->mock(
            LoggerFactory::class,
            static fn (LoggerFactory|MockInterface $mock) => $mock
                ->shouldReceive('createCloudwatchLogger')
                ->once()
                ->with(
                    $expectedName,
                    Mockery::isEqual($expectedClientConfig),
                    Mockery::isEqual($expectedHandlerConfig),
                )
                ->andReturn(
                    Mockery::mock(LoggerInterface::class)
                        ->shouldReceive('debug')
                        ->once()
                        ->with('Hello world!', [])
                        ->andReturnTrue()
                        ->getMock(),
                )
                ->getMock(),
        );

        Config::set('logging.channels.plumthedev', $loggerConfig);
        Config::set('logging.cloudwatch', $cloudwatchConfig);

        Log::channel('plumthedev')->debug('Hello world!');
    }
}
