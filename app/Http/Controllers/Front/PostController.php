<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\CommentRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
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
                'slug' => $slug,
                'locale' => getLocale(),
                'type' => 'pages',
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
        $post = Post::find($request->post_id);
        if (!$post) {
            return $this->abort(410, true);
        }


        $request = $request->all();
        $request['ip'] = request()->ip();
        $request['user_id'] = user()->id;
        $request['type'] = $post->type;

        $callbackFn = <<<JS
        $('.previous-comments').updateContent(function() {
            $('.previous-comments').find('.collapse').each(function () { 
                $(this).collapse(); 
            }); 
        });
JS;


        return $this->jsonAjaxSaveFeedback(Comment::store($request), [
            'success_callback' => $callbackFn,
            'success_form_reset' => true,
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

}
