<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
        Blade::directive('pd', function ($str) {
            return "<?php echo App\Providers\AppServiceProvider::pd($str) ?>" ;
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function pd($str)
    {
        $farsi_chars = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹','۴','۵','۶','ی','ک','ک',];
        $latin_chars = ['0','1','2','3','4','5','6','7','8','9','٤','٥','٦','ي','ك','ك',];
        $new_str = str_replace($latin_chars,$farsi_chars,$str);

        return $new_str ;
    }
}
