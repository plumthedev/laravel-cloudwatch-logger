<?php

return [
    'name'           => env('CLOUDWATCH_LOGGER_NAME', 'Laravel Cloudwatch Logger'),
    'region'         => env('CLOUDWATCH_LOGGER_REGION', 'eu-central-1'),
    'version'        => env('CLOUDWATCH_LOGGER_VERSION', 'latest'),
    'credentials'    => [
        'key'    => env('CLOUDWATCH_LOGGER_CREDENTIALS_KEY'),
        'secret' => env('CLOUDWATCH_LOGGER_CREDENTIALS_SECRET'),
    ],
    'group_name'     => env('CLOUDWATCH_LOGGER_GROUP_NAME', env('APP_NAME')),
    'stream_name'    => env('CLOUDWATCH_LOGGER_STREAM_NAME', env('APP_NAME')),
    'retention_days' => env('CLOUDWATCH_LOGGER_RETENTION_DAYS', 14),
    'batch_size'     => env('CLOUDWATCH_LOGGER_BATCH_SIZE', 25),
];
