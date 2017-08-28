<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AjaxFilterServiceProvider extends ServiceProvider
{
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

    public static function translateHash($hash)
    {
        $hashArray = [];
        $hashParts = explodeNotEmpty('!', $hash);
        foreach ($hashParts as $field) {
            $fieldParts = explodeNotEmpty('?', $field);
            if (count($fieldParts) == 2) {
                $hashArray[$fieldParts[1]] = explodeNotEmpty('/', $fieldParts[0]);
                $currentGroup = &$hashArray[$fieldParts[1]];
                $currentGroup = arrayPrefixToIndex('_', $currentGroup);
            }
        }

        return $hashArray;
    }
}
