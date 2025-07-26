<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Http\Middlewares\PayfortWebhookSignature;
use Sevaske\Payfort\Managers\MerchantManager;
use Sevaske\PayfortApi\Enums\PayfortEnvironmentEnum;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PayfortServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('payfort')
            ->hasConfigFile()
            ->hasRoute('payfort-webhook')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('sevaske/payfort');
            });
    }

    public function registeringPackage(): void
    {
        // http client with a base_uri
        $this->app->singleton('payfort-http-client', function () {
            return new \GuzzleHttp\Client([
                'base_uri' => PayfortEnvironmentEnum::getUrl(config('payfort.env')),
                'verify' => true,
            ]);
        });

        // merchant manager
        $this->app->singleton(MerchantManager::class, function ($app) {
            return new MerchantManager($app, $app['config']->get('payfort'));
        });

        // main class
        $this->app->singleton(Payfort::class, fn () => new Payfort);
    }

    public function bootingPackage(): void
    {
        // middleware alias: payfort.webhook.signature
        $this->app['router']->aliasMiddleware('payfort.webhook.signature', PayfortWebhookSignature::class);
    }
}
