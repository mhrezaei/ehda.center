<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Posttype;
use App\Models\Receipt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Morilog\Jalali\Facades\jDateTime;

class PostsServiceProvider extends ServiceProvider
{
    private static $searchTemplate = 'post';
    private static $defaultData = [
        'showList' => [
            'slug' => "",
            'role' => "",
            'criteria' => "published",
            'locale' => "",
            'owner' => 0,
            'type' => "feature:searchable",
            'category' => "",
            'folder' => "",
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
        ]
    ];

    private $runningMethod;

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
        $methodName = 'showList';
        // normalize data
        $data = array_normalize($data, self::$defaultData[$methodName]);

        $showFilter = $data['show_filter'];
        $ajaxRequest = $data['ajax_request'];
        $isBasePage = $data['is_base_page'];

        if (!$data['is_base_page']) {

            if ($data['paginate_current']) {
                Paginator::currentPageResolver(function () use ($data) {
                    return $data['paginate_current'];
                });
            }

            // select posts
            $posts = Post::selector($data)
                ->where($data['conditions'])
                ->orderBy($data['sort_by'], $data['sort']);

            if($data['max_per_page'] == -1) {
                $posts = $posts->get();
            } else {
                $posts = $posts->paginate($data['max_per_page']);
            }


            if (!$posts->count()) {
                return self::showError(trans('front.no_result_found'), $ajaxRequest);
            }

            if ($data['paginate_hash']) {
                $posts->fragment($data['paginate_hash'])->links();
            }

            if ($data['paginate_url']) {
                $posts->withPath($data['paginate_url']);
            }
        }

//        $allPosts = self::allPostsOfType($data['type'], $data['locale']);
        $allPosts = Post::selector($data)->get();

        if (!$allPosts->count()) {
            return self::showError(trans('front.no_result_found'), $ajaxRequest);
        }

        // set an array for sending data to view
        $viewData = [];

        // specify template
        $postType = Posttype::findBySlug($data['type']);
        if (self::$defaultData[$methodName]['type'] != $data['type']) {
            $viewData['postType'] = $postType;
            $template = $postType
                ->spreadMeta()
                ->template;
        } else {
            $template = self::$searchTemplate;
        }

        // render view
        if ($template == 'special') {
            $viewFolder = "front.posts.list.special.$postType->slug";
        } else {
            $viewFolder = "front.posts.list.$template";
        }

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


        if (!$post->exists) {
            if ($data['showError']) {
                return view('errors.m410');
            } else {
                return false;
            }
        }

        $postType = $post->posttype()->spreadMeta();
        $template = $postType->template;

        if ($template == 'special') {
            $viewFolder = "front.posts.single.$template.$postType->slug";
        } else {
            $viewFolder = "front.posts.single.$template";
        }

        // render view

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

    public static function getUserPointOfEvent($event, $user = 'current')
    {
        if (!($event instanceof Post)) {
            if (is_numeric($event)) {
                $event = Post::find($event);
            } else {
                $event = Post::findBySlug($event);
            }
        }

        if (!$event->exists or $event->type != 'events') {
            return false;
        }

        if ($user == 'current') {
            $user = user();
        }


        if (!($user instanceof User) or !$user->exists) {
            return false;
        }

        $event->spreadMeta();

        return floor(Receipt::where(['user_id' => $user->id])
            ->whereDate('purchased_at', '>=', $event->starts_at)
            ->whereDate('purchased_at', '<=', $event->ends_at)
            ->select(DB::raw("SUM(purchased_amount)/'$event->rate_point' points"))
            ->pluck('points')
            ->first());
    }

    public static function showError($errorMessage, $ajaxRequest = false)
    {
        return view('front.posts.error', [
            'errorMessage' => $errorMessage,
            'ajaxRequest' => $ajaxRequest,
        ]);
    }
}

