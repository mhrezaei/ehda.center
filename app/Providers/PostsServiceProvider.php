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
    protected static $searchTemplate = 'post';
    protected static $defaultData = [
        'selectPosts'  => [],
        'collectPosts' => [],
        'showList'     => [],
        'showPost'     => [],
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

    public static function selectPosts($data = [])
    {
        $methodName = __FUNCTION__;
        $defaultValues = self::getDefaultValues($methodName);
        // normalize data
        $data = array_normalize($data, $defaultValues);

        // Select posts
        $posts = Post::selector($data)
            ->where($data['conditions']);

        // Randomize list if needed
        if ($data['random']) {
            $posts = $posts->inRandomOrder();
        } else {
            $posts = $posts->orderBy($data['sort_by'], $data['sort']);
        }

        // Limit number of posts if needed
        if ($data['limit']) {
            $posts = $posts->limit($data['limit']);
        }

        return $posts;

//        // Paginate lists if needed
//        if (($data['max_per_page'] == -1)) {
//            return $posts->get();
//        } else {
//            // Limit number of posts if needed
//            if ($data['limit']) {
//                return $posts->paginate($data['limit']);
//            } else {
//                return $posts->paginate($data['max_per_page']);
//            }
//        }
    }

    /**
     * Collects posts
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public static function collectPosts($data = [])
    {
        $methodName = __FUNCTION__;
        $defaultValues = self::getDefaultValues($methodName);
        // normalize data
        $data = array_normalize($data, $defaultValues);

        // Set current pagination page if needed
        if ($data['paginate_current']) {
            Paginator::currentPageResolver(function () use ($data) {
                return $data['paginate_current'];
            });
        }

        $posts = self::selectPosts($data);

        // Paginate lists if needed
        if (($data['max_per_page'] == -1)) {
            return $posts->get();
        } else {
            // Limit number of posts if needed
            if ($data['limit']) {
                return $posts->paginate($data['limit']);
            } else {
                return $posts->paginate($data['max_per_page']);
            }
        }
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
        // Check received to be listable
        $data['type'] = self::filterPosttypesByFeature($data['type']);
        $methodName = __FUNCTION__;
        $defaultValues = self::getDefaultValues($methodName);
        // normalize data
        $data = array_normalize($data, $defaultValues);


        $showFilter = $data['show_filter'];
        $ajaxRequest = $data['ajax_request'];
        $isBasePage = $data['is_base_page'];

        if (!$data['is_base_page']) {
            $posts = self::collectPosts($data);

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
        $methodName = __FUNCTION__;
        $defaultValues = self::getDefaultValues($methodName);
        // normalize data
        $data = array_normalize($data, $defaultValues);

        // Find posts
        $post = self::smartFindPost($identifier);

        // Make action if post doesn't exist
        if (!$post->exists or !$post->id) {
            if ($data['showError']) {
                return view('errors.m410');
            } else {
                return false;
            }
        }

        // Find posttype
        $postType = $post->posttype()->spreadMeta();

        // Find related alert and messages
        $messagesPosts['noAccessFiles'] = self::smartFindPost($postType->slug . '-no-access-files');
        if (!$messagesPosts['noAccessFiles']->exists) {
            $messagesPosts['noAccessFiles'] = self::smartFindPost('no-access-files');
        }

        // Find template of post
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
                'messagesPosts',
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

    /**
     * Find posttype with multiple types of identifiers
     *
     * @param         $identifier
     * @param boolean $checkDirectId If false, posttype will not be searched with id of db table
     *
     * @return \App\Models\Posttype
     */
    public static function smartFindPosttype($identifier, $checkDirectId = false)
    {
        if ($identifier instanceof Posttype) {
            $posttype = $identifier;
        } else if (is_numeric($identifier) and $checkDirectId) {
            $posttype = Posttype::find($identifier);
        } else if (count($dehashed = hashid_decrypt($identifier, 'ids')) and
            is_numeric($id = $dehashed[0])
        ) {
            $posttype = Posttype::find($id);
        } else {
            $posttype = Posttype::findBySlug($identifier);
        }

        if ($posttype->exists) {
            return $posttype;
        }

        return new Posttype();
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

    public static function filterPosttypesByFeature($posttypes, $feature = 'listable')
    {
        if (is_array($posttypes)) {
            foreach ($posttypes as $key => $posttype) {
                if (is_null(self::filterPosttypesByFeature($posttype, $feature))) {
                    unset($posttypes[$key]);
                }
            }
            if (empty($posttypes)) {
                return self::getDefaultValues('selectPosts')['type'];
            }
            return $posttypes;
        } else {
            $obj = self::smartFindPosttype($posttypes);
            if ($obj->exists and !$obj->has($feature)) {
                return null;
            } else {
                return $posttypes;
            }
        }
    }

    public static function filterActiveCategories($categories) {
        foreach ($categories as $key => $category) {
            if(!$category->posts()->count()) {
                $categories->forget($key);
            }
        }
        return $categories;
    }

    private static function generateView($view, $data = [])
    {
        if (View::exists($view)) {
            return view($view, $data);
        }

        return view('errors.m404');
    }

    protected static function getDefaultValues($method = null)
    {
        if (empty(self::$defaultData['selectPosts'])) {
            self::$defaultData['selectPosts'] = [
                'id'         => "",
                'slug'       => "",
                'role'       => "",
                'criteria'   => "published",
                'locale'     => getLocale(),
                'owner'      => 0,
                'type'       => "feature:searchable",
                'category'   => "",
                'keyword'    => "",
                'search'     => "",
                'from'       => "",
                'to'         => "",
                'folder'     => "",
                'domain'     => getUsableDomains(),
                'limit'      => false, // If this is set as "false" list will not be limited
                'random'     => false,
                'sort'       => 'DESC',
                'sort_by'    => 'published_at',
                'conditions' => [], // additional conditions to be used in "where" clause
            ];
        }
        if ($method == 'selectPosts') {
            return self::$defaultData[$method];
        }

        if (empty(self::$defaultData['collectPosts'])) {
            self::$defaultData['collectPosts'] = array_merge(self::$defaultData['selectPosts'], [
                'max_per_page'     => 12, // If this is set as "-1" pagination will be applied
                'paginate_current' => '',
            ]);
        }
        if ($method == 'collectPosts') {
            return self::$defaultData[$method];
        }

        if (empty(self::$defaultData['showList'])) {
            self::$defaultData['showList'] = array_merge(self::$defaultData['collectPosts'], [
                'paginate_hash' => '', // the fragment that should be added to links in pagination
                'show_filter'   => true,
                'ajax_request'  => false,
                'paginate_url'  => '',
                'is_base_page'  => false,
                'showError'     => true,
                'variables'     => [],
            ]);
        }
        if ($method == 'showList') {
            return self::$defaultData[$method];
        }

        if (empty(self::$defaultData['showPost'])) {
            self::$defaultData['showPost'] = [
                'lang'          => getLocale(),
                'externalBlade' => '',
                'preview'       => false,
                'showError'     => true,
                'variables'     => [],
            ];
        }
        if ($method == 'showPost') {
            return self::$defaultData[$method];
        }

        return self::$defaultData;
    }
}

