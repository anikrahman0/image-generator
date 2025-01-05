<?php

namespace Noobtrader\Imagegenerator;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ImageGenerateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the ImageGenerate class as a singleton in the service container
        $this->app->singleton('image-generate', function ($app) {
            return new ImageGenerate();
        });

        // Register the facade alias
        AliasLoader::getInstance()->alias('ImageGenerate', \Noobtrader\Imagegenerator\Facades\ImageGenerateFacade::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Bootstrapping services if needed
        $this->publishes([
            __DIR__.'/../config/imagegenerator.php' => config_path('profile-imagegenerator.php'),
        ], 'config');

        // Publish the default font file to the public directory
        $this->publishes([
            __DIR__ . '/resources/fonts/' => public_path('imagegenerator/fonts'),
        ], 'fonts');
    }
}
