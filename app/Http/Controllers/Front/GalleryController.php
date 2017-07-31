<?php

namespace App\Http\Controllers\Front;

use App\Models\Category;
use App\Models\Posttype;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class GalleryController extends Controller
{
    public function show_categories($lang, $postTypeSlug = null)
    {
        /************************* Generate Data for List View ********************** START */

        $filterData = [
            'variables' => [
                'twoColumns' => false, // @TODO: to be read from setting
            ],
        ];

        $items = Category::whereHas('folder', function ($query) {
            $query->where('locale', getLocale());
        });

        $items->whereHas('folder', function ($query) use ($postTypeSlug) {
            $query->whereHas('posttype', function ($query) use ($postTypeSlug) {
                $query->where('slug', $postTypeSlug);
            });
        });
        $items = $items->get();

        $postType = Posttype::findBySlug($postTypeSlug);
        /************************* Generate Data for List View ********************** END */

        /************************* Generate Position Info ********************** START */
        $positionInfo = [];
        if (isset($postType) and // If $postTypeSlug was specified
            $postType->exists // If $postTypeSlug is an slug of an existed postType
        ) {
            $postType->spreadMeta();

            if ($postType->header_title) { // If $postType has an specified "header_title"
                $positionInfo['group'] = $postType->header_title;
            }

            $positionInfo['title'] = $postType->title;
        } else {
            $positionInfo['title'] = $postTypeSlug;
        }

        $positionInfo = array_normalize_keep_originals($positionInfo, [
            'group' => trans('manage.global'),
        ]);
        /************************* Generate Position Info ********************** END */

        /************************* Set Other Values ********************** START */
        $otherValues = [
            'pageTitle' => trans('front.archive'),
        ];
        /************************* Set Other Values ********************** END */

        $view = "front.gallery.archive.main";

        if (View::exists($view)) {
            return view($view, compact(
                    'items',
                    'postType',
                    'positionInfo')
                + $otherValues);
        }

        return view('errors.m404');
    }
}
