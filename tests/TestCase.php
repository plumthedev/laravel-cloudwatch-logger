<?php

declare(strict_types = 1);

namespace Tests\CloudwatchLogger;

use CloudwatchLogger\CloudwatchLoggerServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Orchestra\Testbench\TestCase as PackageTestCase;

class TestCase extends PackageTestCase
{
    /**
     * @param Application $app
     * @return array<int, class-string<ServiceProvider>>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     */
    protected function getPackageProviders($app): array
    {
        return [
            CloudwatchLoggerServiceProvider::class,
        ];
    }
}
