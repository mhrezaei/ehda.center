<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Posttype;
use App\Models\Receipt;
use App\Models\User;
use App\Traits\GlobalControllerTrait;
use App\Traits\TahaControllerTrait;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Morilog\Jalali\Facades\jDateTime;

class PostsServiceProvider extends ServiceProvider
{
    private static $searchTemplate = 'post';

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

    /**
     * Returns a view including list view of post with specified filters and conditions
     *
     * @param array $data Filters and Conditions
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function showList($data = [])
    {
        $defaultData = [
            'showList' => [
                'slug'             => "",
                'role'             => "",
                'criteria'         => "published",
                'locale'           => getLocale(),
                'domain'           => getUsableDomains(),
                'owner'            => 0,
                'type'             => "feature:searchable",
                'category'         => "",
                'folder'           => "",
                'keyword'          => "",
                'search'           => "",
                'from'             => "",
                'to'               => "",
                'max_per_page'     => 12, // If this is set as "-1" pagination will be applied
                'random'           => false,
                'sort'             => 'DESC',
                'sort_by'          => 'published_at',
                'show_filter'      => true,
                'ajax_request'     => false,
                'conditions'       => [], // additional conditions to be used in "where" clause
                'paginate_hash'    => '', // the fragment that should be added to links in pagination
                'paginate_url'     => '',
                'paginate_current' => '',
                'is_base_page'     => false,
                'showError'        => true,
                'variables'        => [],
            ]
        ];

        $methodName = 'showList';
        // normalize data
        $data = array_normalize($data, $defaultData[$methodName]);

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

            if ($data['random']) {
                $posts = $posts->inRandomOrder();
            }

            if ($data['max_per_page'] == -1) {
                $posts = $posts->get();
            } else {
                $posts = $posts->paginate($data['max_per_page']);
            }

            if (!$posts->count()) {
                if ($data['showError']) {
                    return self::showError(trans('front.no_result_found'), $ajaxRequest);
                } else {
                    return false;
                }
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
            if ($data['showError']) {
                return self::showError(trans('front.no_result_found'), $ajaxRequest);
            } else {
                return false;
            }
        }

        // set an array for sending data to view
        $viewData = [];

        // specify template
        $postType = Posttype::findBySlug($data['type']);
        if ($defaultData[$methodName]['type'] != $data['type']) {
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


        return self::generateView($viewFolder . '.main', compact(
                'posts',
                'viewFolder',
                'showFilter',
                'ajaxRequest',
                'isBasePage',
                'allPosts'
            ) + $data['variables']);

//        return view($viewFolder . '.main', compact(
//            'posts',
//            'viewFolder',
//            'showFilter',
//            'ajaxRequest',
//            'isBasePage',
//            'allPosts'
//        ));
    }

    /**
     * Returns a view including single view of a post with specified filters and conditions
     *
     * @param Post|string|integer $identifier
     * @param array               $data
     *
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function showPost($identifier, $data = [])
    {
        // normalize data
        $data = array_normalize($data, [
            'lang'          => getLocale(),
            'externalBlade' => '',
            'preview'       => false,
            'showError'     => true,
            'variables'     => [],
        ]);

        $post = self::smartFindPost($identifier);

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
        $externalBlade = $data['externalBlade'];
        return self::generateView($viewFolder . '.main', compact('post',
                'viewFolder',
                'externalBlade'
            ) + $data['variables']);
    }

    /**
     * Returns a view to show error
     *
     * @param string $errorMessage Error message to be shown
     * @param bool   $ajaxRequest  If true, error page will not be shown
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function showError($errorMessage, $ajaxRequest = false)
    {
        return view('front.posts.error', [
            'errorMessage' => $errorMessage,
            'ajaxRequest'  => $ajaxRequest,
        ]);
    }

    /**
     * @param null $ajaxUrlPrefix : the prefix url for ajax calls that should be called for "waiting" and "expired"
     *                            events
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public static function showEventsAccordion($ajaxUrlPrefix = null)
    {
        if (!$ajaxUrlPrefix) {
            $ajaxUrlPrefix = request()->url();
        }

        $selectConditions = [
            'type'       => 'events',
            'conditions' => [
                ['starts_at', '<=', Carbon::now()],
                ['ends_at', '>=', Carbon::now()],
            ]
        ];

        $currentEventsHTML = PostsServiceProvider::showList($selectConditions);

        $waitingCount = Post::selector(['type' => 'events'])
            ->whereDate('starts_at', '>=', Carbon::now())
            ->whereDate('ends_at', '>=', Carbon::now())
            ->count();
        $expiredCount = Post::selector(['type' => 'events'])
            ->whereDate('starts_at', '<=', Carbon::now())
            ->whereDate('ends_at', '<=', Carbon::now())
            ->count();

        return view('front.posts.list.special.events.accordion', compact(
            'currentEventsHTML',
            'ajaxUrlPrefix',
            'waitingCount',
            'expiredCount'
        ));
    }

    /**
     * Returns an array of categories of given posts
     *
     * @param \Illuminate\Database\Eloquent\Collection $posts
     * @param string                                   $slug
     *
     * @return array
     */
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

    /**
     * Returns the minimum price in given posts
     *
     * @param \Illuminate\Database\Eloquent\Collection $posts
     *
     * @return string|int
     */
    public static function productsMinPrice($posts)
    {
        return $posts->pluck('current_price')->min();
    }

    /**
     * Returns the maximum price in given posts
     *
     * @param \Illuminate\Database\Eloquent\Collection $posts
     *
     * @return string|int
     */
    public static function productsMaxPrice($posts)
    {
        return $posts->pluck('current_price')->max();
    }

    /**
     * Returns all posts of the specified post type
     *
     * @param string      $type
     * @param null|string $locale
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function allPostsOfType($type, $locale = null)
    {
        $data = [
            'locale' => ($locale) ? $locale : getLocale(),
            'type'   => $type,
        ];

        return Post::selector($data)->get();
    }

    /**
     * Returns points of the $user in the $event
     *
     * @param string|integer $event
     * @param string         $user
     *
     * @return bool|float
     */
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

    /**
     * Returns Collection of comments for a post
     *
     * @param \App\Models\Postt $post
     * @param array             $parameters
     *
     * @return mixed
     */
    public static function getPostComments($post, $parameters = [])
    {
        $post = self::smartFindPost($post);
        if ($post->exists) {
            $parameters = array_normalize($parameters, [
                'user_id' => '0',
            ]);

            $selectorRules = [
                'type'     => $post->type,
                'post_id'  => $post->id,
                'criteria' => 'all',
            ];

            if ($parameters['user_id']) {
                $selectorRules['user_id'] = $parameters['user_id'];
            }

            return Comment::selector($selectorRules)
                ->orderBy('created_at', 'DESC')
                ->get();

        }
    }

    /**
     * Find post with multiple types of identifiers
     *
     * @param         $identifier
     * @param boolean $checkDirectId If false, post will not be searched with id of db table
     *
     * @return \App\Models\Post
     */
    public static function smartFindPost($identifier, $checkDirectId = false)
    {
        if ($identifier instanceof Post) {
            $post = $identifier;
        } else if (is_numeric($identifier) and $checkDirectId) {
            $post = Post::find($identifier);
        } else if (count($dehashed = hashid_decrypt($identifier, 'ids')) and
            is_numeric($id = $dehashed[0])
        ) {
            $post = Post::find($id);
        } else {
            $post = Post::findBySlug($identifier);
        }

        if ($post->exists and
            (
                !$post->domain or
                // Domain isn't specified
                ($post->domain() and in_array($post->domain, getUsableDomains()))
                // Domain is specified and it is one of usable domains
            )
        ) {
            // Find sister of found post in current locale
            $post = $post->in(getLocale());

            return $post;
        }

        return new Post();
    }

    public static function forceFieldsInLocales($identifier, $fields, $locales)
    {
        $post = self::smartFindPost($identifier);

        if ($post->exists) {
            $parameters = [];
            if (!is_array($fields)) {
                $fields = [$fields];
            }

            if (!is_array($locales)) {
                $locales = [$locales];
            }

            foreach ($locales as $locale) {
                $sisterPost = $post->in($locale);
                if ($sisterPost->exists) {
                    $localeValue = [];
                    foreach ($fields as $parameterKey => $field) {
                        if (!is_string($parameters)) {
                            $parameterKey = $field;
                        }
                        $localeValue[$parameterKey] = $sisterPost->$field;
                    }
                } else {
                    $localeValue = false;
                }
                $parameters[$locale] = $localeValue;
            }

            TransServiceProvider::forceUrlParameters($parameters);
        }
    }

    private static function generateView($view, $data = [])
    {
        if (View::exists($view)) {
            return view($view, $data);
        }

        return view('errors.m404');
    }
}

