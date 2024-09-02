<?php

namespace Sevaske\Payfort;

use Sevaske\Payfort\Services\Http\PayfortHttpClient;
use Sevaske\Payfort\Services\Merchant\PayfortMerchantManager;
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
        $this->app->singleton(PayfortMerchantManager::class, function ($app) {
            return new PayfortMerchantManager($app, $app['config']->get('payfort'));
        });

        // main class
        $this->app->singleton(Payfort::class, fn () => new Payfort);
    }
}
