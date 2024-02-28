<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\UserInterface',
            'App\Services\UserService'
        );

        $this->app->bind(
            'App\Contracts\RoleInterface',
            'App\Services\RoleService'
        );

        $this->app->bind(
            'App\Contracts\PermissionInterface',
            'App\Services\PermissionService'
        );

        $this->app->bind(
            'App\Contracts\SectorInterface',
            'App\Services\SectorService'
        );

        $this->app->bind(
            'App\Contracts\CategoryInterface',
            'App\Services\CategoryService'
        );

        $this->app->bind(
            'App\Contracts\CountryInterface',
            'App\Services\CountryService'
        );

        $this->app->bind(
            'App\Contracts\CityInterface',
            'App\Services\CityService'
        );

        $this->app->bind(
            'App\Contracts\CompanyInterface',
            'App\Services\CompanyService'
        );

        $this->app->bind(
            'App\Contracts\JobInterface',
            'App\Services\JobService'
        );

        $this->app->bind(
            'App\Contracts\MailInterface',
            'App\Services\MailService'
        );

        $this->app->bind(
            'App\Contracts\LanguageInterface',
            'App\Services\LanguageService'
        );

        $this->app->bind(
            'App\Contracts\ContactInterface',
            'App\Services\ContactService'
        );

        $this->app->bind(
            'App\Contracts\SubscribtionInterface',
            'App\Services\SubscribtionService'
        );
    }
}
