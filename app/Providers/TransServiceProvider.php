<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TransServiceProvider extends ServiceProvider
{
    /**
     * @var array Names of Files that should be checked for comparison
     */
    private static $availableTransFiles = [
        'auth',
        'cart',
        'colors',
        'ehda',
        'forms',
        'front',
        'manage',
        'passwords',
        'people',
        'posts',
        'project_front',
        'settings',
        'validation',
    ];
    private static $forcedUrlParameters = [];
    private static $defaultLocales = ['fa', 'en'];

    /**
     * @var array Languages that should be checked for comparison
     */
    private static $availableLanguages = ['fa', 'en'];

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
     * Returns array of has not been defined in one language
     *
     * @return array
     */
    public static function getAllDifferences()
    {
        $lngsNo = count(self::$availableLanguages);
        $result = [];


        for ($i = 0; $i < ($lngsNo - 1); $i++) {
            for ($j = 1; $j < $lngsNo; $j++) {
                $diffLog1 = [];
                $diffLog2 = [];

                foreach (self::$availableTransFiles as $file) {
                    $l1 = self::$availableLanguages[$i];
                    $l2 = self::$availableLanguages[$j];

                    $n1 = trans($file, [], $l1);
                    $n2 = trans($file, [], $l2);

                    $diffLog1 = self::compareTrans($n1, $n2, [$file], $diffLog1);
                    $diffLog2 = self::compareTrans($n2, $n1, [$file], $diffLog2);
                }

                if (count($diffLog1)) {
                    $result[$l1 . '-' . $l2] = $diffLog1;
                }
                if (count($diffLog2)) {
                    $result[$l2 . '-' . $l1] = $diffLog2;
                }
            }
        }

        return $result;
    }

    /**
     * Compares keys in target array with reference and returns differences.
     *
     * @param array $n1      Reference Array
     * @param array $n2      Target Array
     * @param array $map     Array Containing All Checked Indexes from Here Till File Name
     * @param array $diffLog Array Array Containing Founded Missed IDs in Target Language
     *
     * @return array
     */
    public static function compareTrans($n1, $n2, $map = [], $diffLog = [])
    {
        if (!is_array($n1) or !is_array($n2)) {
            $diffLog[] = implode('.', $map);
            return $diffLog;
        }

        $diff = array_diff_key($n1, $n2);
        if (count($diff)) {
            foreach ($diff as $index => $item) {
                $diffLog[] = implode('.', array_merge($map, [$index]));
            }
        }

        foreach ($n1 as $key => $value) {
            if (is_array($value) and isset($n2[$key])) {
                if (!is_array($n2[$key])) {
                    $n2[$key] = [$n2[$key]];
                }
                $diffLog = self::compareTrans($n1[$key], $n2[$key], array_merge($map, [$key]), $diffLog);
            }
        }

        return $diffLog;
    }

    /** Forces variables to be used while generating url of sister pages.
     *
     * @param array $parameters
     */
    public static function forceUrlParameters($parameters = [])
    {
        //
        // Language should be specified in $parameters
        // Correct Example: ['en' => ['name' => 'John']];
        // Wrong Example: ['name' => 'John'];
        //

        $siteLocales = self::getSiteLocales();
        foreach ($parameters as $locale => $fields) {
            if (in_array($locale, $siteLocales) !== false) {
                self::$forcedUrlParameters = array_merge(self::$forcedUrlParameters, [
                    $locale => $fields
                ]);
            }
        }
    }

    /**
     * Returns forced variables to be used while generating url of sister pages.
     *
     * @return array
     */
    public static function getForcedUrlParameters($locale = '')
    {
        if ($locale) {
            if (array_key_exists($locale, self::$forcedUrlParameters)) {
                return self::$forcedUrlParameters[$locale];
            } else {
                return [];
            }
        }

        return self::$forcedUrlParameters;
    }

    /**
     * Returns current locales of site.
     *
     * @return array
     */
    public static function getSiteLocales()
    {
        $localesFromSettings = setting()->ask('site_locales')->gain();
        return $localesFromSettings ?: self::$defaultLocales;
    }
}
