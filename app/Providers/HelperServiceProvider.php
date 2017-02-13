<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Compares the given $array with the provided $defaults to fill any unset value, based on the $defaults pattern
     * @param $array
     * @param $defaults
     */
    public static function array_default($array, $defaults)
    {
        foreach($defaults as $key => $value) {
            if(!array_has($array, $key))
                $array[$key] = $value;
        }

        return $array;
    }

    /**
     * Normalizes the givn $array with the provided $reference, by deleting the extra entries and filling unset ones
     * @param $array
     * @param $reference
     * @return array
     */
    public static function array_normalize($array, $reference)
    {
        $result = [];
        foreach($reference as $key => $value) {
            if(!array_has($array, $key))
                $result[$key] = $value;
            else
                $result[$key] = $array[$key];
        }

        return $result;

    }


}