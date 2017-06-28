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
        if(isDefined(customResetForm) && $.isFunction(customResetForm)) {
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
            'variables'    => [
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

        $listHTML = PostsServiceProvider::showList($filterData);

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

        return view('front.posts.general.list-frame.main', compact(
            'listHTML',
            'positionInfo'
        ));
    }

}
