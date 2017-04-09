<?php

namespace Asanak\Sms;

use Asanak\Sms\Facade\AsanakSms;
use Illuminate\Support\ServiceProvider;

class AsanakSmsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // when user use php artisan vendor:publish views and config files publish and store to this locate
        // php artisan vendor:publish --tag=AsanakSmsTag --force
        $this->publishes([
            __DIR__.'/Config/asanak-sms.php' => config_path('asanak-sms.php'),
        ], 'AsanakSmsTag');

        // bind facade for use this->app['MyFacade'];
        $this->app->bind('AsanakSms', function (){
            return new AsanakSms();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/asanak-sms.php', 'AsanakSmsConfig'
        );
    }
}
