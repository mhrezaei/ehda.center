<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class FrontServiceProvider extends ServiceProvider
{
    private static $homeControllerNamespace = 'App\Http\Controllers\Front';
    private static $homeController = 'FrontController';
    private static $homeAction = 'index';


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Checks if is home route running
     *
     * @return bool
     */
    public static function isHome()
    {
        return (self::getCurrentAction() == self::getHomeUsingFunction());
    }

    /**
     * Returns a full name of current running action
     *
     * @return string
     */
    public static function getCurrentAction()
    {
        return request()->route()->getActionName();
    }

    /**
     * Returns full name of home action
     *
     * @return string
     */
    public static function getHomeUsingFunction()
    {
        // @TODO: try to get from named route
//        if (\Route::has('home')) {
//            // If a route with the name "home" is defined
//        }
        return self::$homeControllerNamespace . '\\' . self::$homeController . '@' . self::$homeAction;
    }
}
