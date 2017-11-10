<?php namespace App\Providers;

use DaveJamesMiller\Breadcrumbs\ServiceProvider;

/**
*
*/
class BreadcrumbsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('breadcrumbs', function ($app) {
            $breadcrumbs = $this->app->make('DaveJamesMiller\Breadcrumbs\Manager');

            $viewPath = __DIR__ . '/../../resources/views/layouts/';

            $this->loadViewsFrom($viewPath, 'breadcrumbs');
            $this->loadViewsFrom($viewPath, 'laravel-breadcrumbs'); // Backwards-compatibility with 2.x

            $breadcrumbs->setView($app['config']['breadcrumbs.view']);

            return $breadcrumbs;
        });
    }
}
