<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\CommentRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
use App\Providers\UploadServiceProvider;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class PostController extends Controller
{
    use ManageControllerTrait;

    public function index()
    {
        return view('errors.404');
    }

    public function page($lang, $slug)
    {
        if (!$slug)
            return view('errors.404');

        if (is_numeric($slug)) {
            $page = Post::find($slug);
        } else {
            $page = Post::selector([
                'slug'   => $slug,
                'locale' => getLocale(),
                'type'   => 'pages',
            ])->first();
        }

        if (!$page)
            return view('errors.404');

        $page->spreadMeta();
        $ogData['description'] = $page->title;
        if ($page->featured_image) {
            $ogData['image'] = url($page->featured_image);
        }

        return view('front.pages.0', compact('page', 'ogData'));
    }

    public function submit_comment(CommentRequest $request)
    {
        UploadServiceProvider::moveUploadedFiles($request);

        $post = Post::find($request->post_id);
        if (!$post) {
            return $this->abort(410, true);
        }

        $request = $request->all();
        $request['ip'] = request()->ip();
        $request['user_id'] = user()->id;
        $request['type'] = $post->type;

        $callbackFn = <<<JS
        if((typeof customResetForm !== "undefined") && $.isFunction(customResetForm)) {
            customResetForm();
        }
        
JS;


        return $this->jsonAjaxSaveFeedback(Comment::store($request), [
            'success_callback'     => $callbackFn,
            'success_feed_timeout' => 3000,
        ]);
    }

    public function search(Request $request)
    {
        if (trim($request->s)) {
            $selectData = [
                'search' => $request->s,
            ];

            $pageTitle = str_replace('::something', $selectData['search'], trans('forms.button.search_for_something'));

            $breadCrumb = [
                [trans('front.home'), url_locale('')],
                [$pageTitle],
            ];

            $searchResultHTML = PostsServiceProvider::showList($selectData);

            return view('front.posts.general.search-result.main', compact('searchResultHTML', 'breadCrumb', 'pageTitle'));
        }
    }

    public function archive($lang, $postTypeSlug = null, $categorySlug = null)
    {
        /************************* Generate Html for List View ********************** START */

        $filterData = [
            'variables' => [
                'twoColumns' => false, // @TODO: to be read from setting
            ],
        ];

        if ($postTypeSlug) {
            // If "postType" is specified, posts in the specified postType will be shown
            $filterData['type'] = $postTypeSlug;

            $postType = Posttype::findBySlug($postTypeSlug);
        } else {
            // If "postType" isn't specified, "listable" posts will be shown
            $filterData['type'] = 'feature:listable';
        }

        if ($categorySlug) {
            // If "category" is specified, posts in specified category will be shown
            $filterData['category'] = $categorySlug;
        } // If "category" isn't specified, posts in all categories will be shown

        $innerHTML = PostsServiceProvider::showList($filterData);

        /************************* Generate Html for List View ********************** END */

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

            if ($categorySlug) {
                $unnamedFolder = Folder::where([
                    'posttype_id' => $postType->id,
                    'locale'      => getLocale(),
                ])->first();

                if ($unnamedFolder and $unnamedFolder->exists) {
                    $category = Category::where([
                        'slug'      => $categorySlug,
                        'folder_id' => $unnamedFolder->id,
                    ])->first();

                    if ($category and $category->exists) {
                        $positionInfo['description'] = $category->title;
                    }
                }

            }
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

        /************************* Render View ********************** START */
        return view('front.posts.general.frame.main', compact(
                'innerHTML',
                'positionInfo'
            ) + $otherValues);
    }

    public function categories($lang, $postTypeSlug = null)
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

        $view = "front.$postTypeSlug.archive.main";
        if (View::exists($view)) {
            return view($view, compact(
                    'items',
                    'postType',
                    'positionInfo')
                + $otherValues);
        }

        return view('errors.m404');
    }

    public function show_with_full_url($lang, $identifier)
    {
        return $this->show($identifier);
    }

    public function show_with_short_url($lang, $identifier)
    {
        $prefix = config('prefix.routes.post.short');

        if (starts_with($identifier, $prefix)) {
            $identifier = substr($identifier, strlen($prefix));
            return $this->show($identifier);
        }

        return $this->abort('403');
    }

    public function faqs()
    {
        $faqsHTML = PostsServiceProvider::showList(['type' => 'faq']);

        $newFaqPost = Post::findBySlug('ask-question');
        if ($newFaqPost->exists and $newFaqPost->canRecieveComments()) {
            $getNewFaq = true;
            $newFaqForm = PostsServiceProvider::showPost($newFaqPost);
        } else {
            $getNewFaq = false;
        }
        return view('front.test.faqs.main', compact('faqsHTML', 'getNewFaq', 'newFaqForm'));
    }

    private function show($hashid)
    {
//        return view('welcome');
        /************************* Find Post ********************** START */
        $post = PostsServiceProvider::smartFindPost($hashid);
        /************************* Find Post ********************** END */

        if ($post->exists) { // If the specified $hashid relates on an existed post
            /************************* Generate Html for Post View Part ********************** START */
            $innerHTML = PostsServiceProvider::showPost($post, [
                'variables' => [
                    'showSideBar' => false, // @TODO: dynamicate this line
                ],
            ]);
            /************************* Generate Html for Post View Part ********************** END */

            /************************* Generate Position Info ********************** START */

            $positionInfo = [];
            $postType = $post->posttype;
            $categories = $post->categories;
            $positionInfo = [
                'group'    => $postType->header_title,
                'category' => $postType->title,
                'title'    => $categories->first() ? $categories->first()->title : '',
            ];

            /************************* Generate Position Info ********************** END */

            /************************* Set Other Values ********************** START */
            $otherValues = [
                'pageTitle' => $post->title,
            ];
            /************************* Set Other Values ********************** END */

            /************************* Render View ********************** START */
            return view('front.posts.general.frame.main', compact(
                    'innerHTML',
                    'positionInfo'
                ) + $otherValues);

        } else {
            $this->abort('404');
        }
    }

}
