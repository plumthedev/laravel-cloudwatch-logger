# Laravel Cloudwatch Logger

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PhpStorm](https://img.shields.io/badge/phpstorm-143?style=for-the-badge&logo=phpstorm&logoColor=black&color=black&labelColor=darkorchid)

![quality check](https://github.com/plumthedev/laravel-cloudwatch-logger/actions/workflows/quality_check.yml/badge.svg)
![tests coverage](https://raw.githubusercontent.com/plumthedev/laravel-cloudwatch-logger/image-data/coverage.svg)

Laravel Cloudwatch Logger is a Laravel package that provides integration with AWS CloudWatch for logging.

## Installation

You can install the package via composer:

```bash
composer require plumthedev/laravel-cloudwatch-logger
```

## Configuration

This package allows you to configure AWS CloudWatch logging settings either globally or per channel.

### Global Configuration

If the `logging.cloudwatch` key is not present in your Laravel configuration,
the package merges the following default configuration into the global logging configuration:

| Configuration Key                     | Description                                       | Env Name                             | Default Value             |
|---------------------------------------|---------------------------------------------------|--------------------------------------|---------------------------|
| logging.cloudwatch.name               | Name of the CloudWatch log channel                | CLOUDWATCH_LOGGER_NAME               | Laravel Cloudwatch Logger |
| logging.cloudwatch.region             | AWS region for CloudWatch logging                 | CLOUDWATCH_LOGGER_REGION             | eu-central-1              |
| logging.cloudwatch.version            | AWS SDK version                                   | CLOUDWATCH_LOGGER_VERSION            | latest                    |
| logging.cloudwatch.credentials.key    | AWS access key for authentication                 | CLOUDWATCH_LOGGER_CREDENTIALS_KEY    |                           |
| logging.cloudwatch.credentials.secret | AWS secret key for authentication                 | CLOUDWATCH_LOGGER_CREDENTIALS_SECRET |                           |
| logging.cloudwatch.group_name         | Default log group name                            | CLOUDWATCH_LOGGER_GROUP_NAME         | Your App Name             |
| logging.cloudwatch.stream_name        | Default log stream name                           | CLOUDWATCH_LOGGER_STREAM_NAME        | Your App Name             |
| logging.cloudwatch.retention_days     | Number of days to retain log events               | CLOUDWATCH_LOGGER_RETENTION_DAYS     | 14                        |
| logging.cloudwatch.batch_size         | Maximum number of log events to send in one batch | CLOUDWATCH_LOGGER_BATCH_SIZE         | 25                        |

### Channel Configuration

You can also override or specify additional configuration options for specific logging channels. For example:

```php
// config/logging.php

'channels' => [
    ...
    'cloudwatch' => [
        'driver' => 'cloudwatch',
        'group_name' => 'api_v1',
        'stream' => 'V1 Payments Logger',
    ],
],

'cloudwatch' => [
   'name' => 'Laravel App',
   'group_name' => 'api',
   'stream' => 'component',
   'credentials' => [
        'key' => '...',
        'secret' => '...',
    ],
],
```

In the channel configuration, you can define specific settings such as the log group name (`group_name`) and log stream name (`stream_name`).
These settings will override the corresponding global configuration values for the CloudWatch logging driver.
While `credentials` will keep the same.

## Testing

To run tests, you need to build a Docker image first:

```shell
make build
```

Only then you can execute the tests:

```shell
make test
```

## Contribution

If you spot areas for improvement, wish to make enhancements, or have ideas for further development, feel free to
contribute to this project.

To access the project terminal, you must first build the Docker image:

```shell
make build
```

Afterward, you can enter the console:

```shell
make run
```

Before submitting a pull request, ensure everything is in order:

```shell
make check
```

## License

This project is licensed under the terms of the MIT license. See the [LICENSE](LICENSE) file for details.
