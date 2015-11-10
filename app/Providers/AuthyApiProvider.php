<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Authy\AuthyApi as AuthyApi;

class AuthyApiProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Authy\AuthyApi', function ($app) {
                $authyKey = config('services.authy')['apiKey'];
                return new AuthyApi($authyKey);
            }
        );
    }
}
