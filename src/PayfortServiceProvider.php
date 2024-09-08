<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Http\PayfortHttpClient;
use Sevaske\Payfort\Managers\MerchantManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PayfortServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('payfort')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        // http client with a base_uri
        $this->app->singleton(PayfortHttpClient::class, function () {
            return new PayfortHttpClient([
                'base_uri' => Config::getApiUrl(),
            ]);
        });

        // merchants
        $this->app->singleton(MerchantManager::class, function ($app) {
            return new MerchantManager($app, $app['config']->get('payfort'));
        });

        // main class
        $this->app->singleton(Payfort::class, fn () => new Payfort);
    }
}
