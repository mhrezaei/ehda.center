<?php

namespace App\Providers;

use App\Models\Posttype;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    // The locale that have been used in manage and should be used in queries
    private static $defaultLocale = 'fa';


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

    public static function getMenuArray()
    {
        $menu = trans('front.main-menu.items');
        foreach ($menu as $key => $menuItem) {
            $tmpGroup = [
                'children' => [],
                'title'    => $menuItem,
            ];

            $postTypes = Posttype::where([
                'header_title' => trans('front.main-menu.items.' . $key, [], self::$defaultLocale),
            ])->get();

            if ($postTypes->count()) {
                $tmpGroup['title'] = $menuItem;

                foreach ($postTypes as $postType) {
                    $tmpColumn = [
                        'children' => [],
                        'title'    => $postType->titleIn(getLocale()),
                        'slug'     => $postType->slug,
                    ];

                    $folder = $postType->folders()
                        ->where([
                            'slug'   => 'no',
                            'locale' => getLocale(),
                        ])->first();
                    if ($folder and $folder->exists) {

                        $categories = $folder->categories;
                        foreach ($categories as $category) {
                            $tmpColumn['children'][] = [
                                'title' => $category->title,
                                'slug'  => $category->slug,
                            ];
                        }
                    }

                    $tmpGroup['children'][] = $tmpColumn;
                }

                $menu[$key] = $tmpGroup;
            } else {
                unset($menu[$key]);
            }

        }

        return $menu;
    }
}
