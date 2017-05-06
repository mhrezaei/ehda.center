<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Posttype;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
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
            'locale' => getLocale(),
            'owner' => 0,
            'type' => "",
            'category' => "",
            'keyword' => "",
            'search' => "",
            'from' => "",
            'to' => "",
            'max_per_page' => 12,
            'sort' => 'DESC',
            'sort_by' => 'published_at',
            'show_filter' => true,
            'ajax_request' => false,
            'conditions' => [], // additional conditions to be used in "where" clause
            'paginate_hash' => '', // the fragment that should be added to links in pagination
            'paginate_url' => '',
            'paginate_current' => '',
            'is_base_page' => false,
        ]);

        if (!$data['is_base_page']) {
            if ($data['paginate_current']) {
                Paginator::currentPageResolver(function () use ($data) {
                    return $data['paginate_current'];
                });
            }

            // select posts
            $posts = Post::selector($data)
                ->where($data['conditions'])
                ->orderBy($data['sort_by'], $data['sort'])
                ->paginate($data['max_per_page']);

            if ($data['paginate_hash']) {
                $posts->fragment($data['paginate_hash'])->links();
            }

            if ($data['paginate_url']) {
                $posts->withPath($data['paginate_url']);
            }
        }

        $allPosts = self::allPostsOfType($data['type'], $data['locale']);


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
        $isBasePage = $data['is_base_page'];

        return view($viewFolder . '.main', compact(
            'posts',
            'viewFolder',
            'showFilter',
            'ajaxRequest',
            'isBasePage',
            'allPosts'
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
        $cats = Category::whereHas('posts', function ($query) use ($posts) {
            $query->whereIn('posts.id', $posts->pluck('id')->toArray());
        })->get()
            ->pluck('title', $slug)
            ->toArray();

        ksort($cats);

        return $cats;
    }

    public static function productsMinPrice($posts)
    {
        return $posts->pluck('current_price')->min();
    }

    public static function productsMaxPrice($posts)
    {
        return $posts->pluck('current_price')->max();
    }

    public static function allPostsOfType($type, $locale = null)
    {
        $data = [
            'locale' => ($locale) ? $locale : getLocale(),
            'type' => $type,
        ];

        return Post::selector($data)->get();
    }
}
