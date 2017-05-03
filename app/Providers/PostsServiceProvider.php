<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Posttype;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Morilog\Jalali\Facades\jDateTime;

class PostsServiceProvider extends ServiceProvider
{
    private static $searchTemplate = 'post';

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


    public static function showList($data = [])
    {
        // normalize data
        $data = array_normalize($data, [
            'slug' => "",
            'role' => "",
            'locale' => "",
            'owner' => 0,
            'type' => "",
            'category' => "",
            'keyword' => "",
            'search' => "",
            'date_begin' => "",
            'date_end' => "",
            'max_per_page' => 12,
            'sort' => 'DESC',
            'sort_by' => 'published_at',
            'show_filter' => true,
            'ajax_request' => false,
            'conditions' => [], // additional conditions to be used in "where" clause
        ]);

        // select posts
        $firstLevel = Post::selector($data)
            ->where($data['conditions']);

        $posts = $firstLevel->orderBy($data['sort_by'], $data['sort'])
            ->paginate($data['max_per_page']);

        $minPrice = $firstLevel->select(DB::raw('min(price) min'))->first()->toArray()['min'];
        $maxPrice = $firstLevel->select(DB::raw('max(price) max'))->first()->toArray()['max'];


        // set an array for sending data to view
        $viewData = [];

        // specify template
        if ($data['type']) {
            $postType = Posttype::findBySlug($data['type']);
            $viewData['postType'] = $postType;
            $template = $postType
                ->spreadMeta()
                ->template;
        } else {
            $template = self::$searchTemplate;
        }

        // render view
        $viewFolder = "front.posts.list.$template";
        $showFilter = $data['show_filter'];
        $ajaxRequest = $data['ajax_request'];

        return view($viewFolder . '.main', compact(
            'posts',
            'viewFolder',
            'showFilter',
            'ajaxRequest',
            'minPrice',
            'maxPrice'
        ));
    }

    public static function showPost($identifier, $data = [])
    {
        // normalize data
        $data = array_normalize($data, [
            'lang' => getLocale(),
            'preview' => false,
            'showError' => true,
        ]);

        if ($identifier instanceof Post) {
            $post = $identifier;
        } else if (is_numeric($identifier)) {
            $post = Post::find($identifier);
        } else {
            $post = Post::findBySlug($identifier);
        }

        if (!$post->exists and $data['showError']) {
            return view('errors.m410');
        }

        $template = $post->posttype()->spreadMeta()->template;

        // render view
        $viewFolder = "front.posts.single.$template";

        return view($viewFolder . '.main', compact('post', 'viewFolder'));
    }

    public static function postsCategories($posts, $slug = 'id')
    {
        $result = [];
        foreach ($posts->items() as $key => $item) {
            if ($item instanceof Post) {
                $categories = $item->categories;
                if ($categories->count()) {
                    $result += $categories->pluck('title', $slug)->toArray();
                } else {
                    $result += ['' => trans('posts.filters.no_category')];
                }
            }
        }

        ksort($result);
        return $result;
    }

    public static function productsMinPrice($posts)
    {
        return $posts->pluck('current_price')->min();
    }

    public static function productsMaxPrice($posts)
    {
        return $posts->pluck('current_price')->max();
    }
}
