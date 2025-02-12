<?php

namespace Sokeio\Page;

use Illuminate\Support\ServiceProvider;
use Sokeio\ServicePackage;
use Sokeio\Core\Concerns\WithServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('sokeio-page')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function packageRegistered()
    {
        // packageRegistered
    }

    public function packageBooted()
    {
        // packageBooted
    }
}
