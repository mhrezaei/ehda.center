<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\AngelsSearchRequest;
use App\Http\Requests\Front\CommentRequest;
use App\Http\Requests\Manage\PostSaveRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\File;
use App\Models\FileDownloads;
use App\Models\Folder;
use App\Models\Post;
use App\Models\Posttype;
use App\Providers\DummyServiceProvider;
use App\Providers\PostsServiceProvider;
use App\Providers\TransServiceProvider;
use App\Providers\UploadServiceProvider;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Vinkla\Hashids\Facades\Hashids;

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
        UploadServiceProvider::moveUploadedFilesWithRequest($request);

        $post = Post::find($request->post_id);
        if (!$post) {
            return $this->abort(410, true);
        }
        $post->spreadMeta();

        $data = $request->all();
        $data['ip'] = request()->ip();
        $data['user_id'] = user()->id;
        $data['type'] = $post->type;
        $data['locale'] = getLocale();

        $feedBackData = ['success_feed_timeout' => 3000];

        $feedBackData['success_callback'] = <<<JS
        if((typeof customResetForm !== "undefined") && $.isFunction(customResetForm)) {
            customResetForm();
        }
        
JS;

        if ($message = $post->getAttribute('comment_submission_message_' . getLocale())) {
            $feedBackData['success_message'] = $message;
        } else if ($message = $post->getAttribute('comment_submission_message')) {
            $feedBackData['success_message'] = $message;
        }

        return $this->jsonAjaxSaveFeedback(Comment::store($data), $feedBackData);
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
            'showError' => false,
            // If set "showError" to false, showList function will be return "false" in case of no result
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

        if (!$innerHTML) {
            // If no result found
            return redirect(getLocale());
        }

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

    public function show_with_exact_id($lang, $identifier)
    {
        $post = Post::findBySlug($identifier, 'id');
        if ($post->exists) {
            return redirect($post->short_url);
        }

        return redirect()->home();
    }

    public function faqs()
    {
        $faqsHTML = PostsServiceProvider::showList(['type' => 'faq']);

        $newFaqPost = Post::findBySlug('ask-question');
        if ($newFaqPost->exists and $newFaqPost->canReceiveComments()) {
            $getNewFaq = true;
            $newFaqForm = PostsServiceProvider::showPost($newFaqPost);
        } else {
            $getNewFaq = false;
        }
        return view('front.faqs.main', compact('faqsHTML', 'getNewFaq', 'newFaqForm'));
    }

    public function special_volunteers()
    {
        $postType = Posttype::findBySlug('celebs');

        /************************* Generate Html for List View ********************** START */
        $innerHTML = PostsServiceProvider::showList([
            'type'         => 'celebs',
            'max_per_page' => -1,
        ]);
        /************************* Generate Html for List View ********************** END */

        /************************* Generate Position Info ********************** START */
        $positionInfo = [
            'group'    => $postType->headerTitleIn(getLocale()),
            'category' => $postType->titleIn(getLocale()),
        ];
        /************************* Generate Position Info ********************** END */

        /************************* Set Other Values ********************** START */
        $otherValues = [
            'pageTitle' => trans('front.special_volunteers'),
        ];
        /************************* Set Other Values ********************** END */

        /************************* Render View ********************** START */
        return view('front.posts.general.frame.main', compact(
                'innerHTML',
                'positionInfo'
            ) + $otherValues);
    }

    public function works_send()
    {
        $postsPrefix = 'send-work-';
        UploadServiceProvider::setUserType('client');
        UploadServiceProvider::setSection('work');


        // get related posts
        $posts = Post::selector(['type' => 'commenting'])
            ->where('slug', 'like', "$postsPrefix%")
            ->get();


        // remove posts that are related to inactive file types (in config/upload.php)
        foreach ($posts as $key => $post) {
            $fileType = str_replace($postsPrefix, '', $post->slug);
            if (UploadServiceProvider::isActive($fileType)) {
                $post->fileType = $fileType;
            } else {
                $posts->forget($key);
            }
        }

        $sendingArea = view('front.test.works.sending_area.main', compact('posts'));
        $staticPost = PostsServiceProvider::smartFindPost('send-works-text');
        if ($staticPost->exists) {
//        $postContentHTML = PostsServiceProvider::showPost('send-works-text', ['externalBlade' => $sendingArea]);
            return view('front.test.works.main', compact('sendingArea', 'staticPost'));
        }
        return $this->abort(404);
    }

    public function angels()
    {
        UploadServiceProvider::setUserType('client');
        UploadServiceProvider::setSection('angels');

        $innerHTML = PostsServiceProvider::showList([
            'type'         => 'angels',
            'random'       => true,
            'max_per_page' => 19,
        ]);

        return view('front.angles.main', compact('innerHTML'));
    }

    public function angels_find(AngelsSearchRequest $request)
    {
        $foundAngels = Post::selector([
            'type'   => 'angels',
            'domain' => getUsableDomains(),
        ])->where('title', 'LIKE', "%{$request->angel_name}%")
            ->limit(100)// limit data in query
            ->get();

        if ($foundAngels->count()) {
            foreach ($foundAngels as $angel) {
                $angel->spreadMeta();

                $resultAngels[] = [
                    'id'            => $angel->id,
                    'name'          => $angel->title,
                    'label'         => $angel->title . ($angel->city ? '، ' . $angel->city : ''),
                    'picture_url'   => $angel->viewable_featured_image,
                    'donation_date' => ad(echoDate($angel->donation_date, 'j F Y')),
                ];
            }

            return response()->json($resultAngels);
        }

    }

    public function postVeryShortLink($identifier)
    {
        $prefix = config('prefix.routes.post.short');

        if (starts_with($identifier, $prefix)) {
            $hashid = substr($identifier, strlen($prefix));
            $post = Post::findByHashid($hashid);
            if ($post->exists) {
                return redirect($post->direct_url);
            }
        }

        return redirect('/');
    }

    private function show($hashid)
    {
        /************************* Find Post ********************** START */
        $post = PostsServiceProvider::smartFindPost($hashid);
        /************************* Find Post ********************** END */

        if ($post->exists and $post->id) { // If the specified $hashid relates on an existed post
            $post->spreadMeta();

            /************************* Set Other Values ********************** START */
            $innerHTMLVars = [
                'showSideBar' => false, // @TODO: dynamicate this line
            ];
            $files = $post->post_files ? $post->post_files : [];
            $innerHTMLVars['postFiles'] = $files;
            $orderSessionName = 'product-order-' . $post->hashid;
            if (session()->exists($orderSessionName) and count($files)) {
                $orderId = session($orderSessionName);
                foreach ($files as $file) {
                    $file = UploadServiceProvider::smartFindFile($file['src']);
                    if ($file->exists) {
                        $filesIds[] = $file->id;
                    }
                }

                $undownloadeds = FileDownloads::where([
                    'order_id' => $orderId,
                ])->get();
                if ($undownloadeds and $undownloadeds->count()) {
                    $undownloadedIds = $undownloadeds->pluck('file_id')->toArray();
                    foreach ($files as $key => $file) {
                        $fileObj = File::findByHashid($file['src']);
                        if ($fileObj->exists and in_array($fileObj->id, $undownloadedIds) === false) {
                            unset($files[$key]);
                        }
                    }
                    $innerHTMLVars['files'] = $files;
                }

                session()->forget($orderSessionName);
            }
            /************************* Set Other Values ********************** END */

            /************************* Generate Html for Post View Part ********************** START */
            $innerHTML = PostsServiceProvider::showPost($post, [
                'variables' => $innerHTMLVars,
            ]);
            /************************* Generate Html for Post View Part ********************** END */

            /************************* Generate Position Info ********************** START */

            $positionInfo = [];
            $postType = $post->posttype;
            $categories = $post->categories;
            $positionInfo = [
                'group'    => $post->header_title ?: $postType->header_title,
                'category' => $post->category_title ?: $postType->title,
                'title'    => $categories->first() ? $categories->first()->title : '',
            ];

            /************************* Generate Position Info ********************** END */

            /************************* Set Other Values ********************** START */
            $otherValues = [
                'pageTitle' => $post->title,
                'metaTags'  => [
                    'image' => $post->viewable_featured_image_thumbnail,
                ]
            ];
            /************************* Set Other Values ********************** END */

            /************************* Set Locale Depended Parameters ********************** START */
            $urlParametersForForce = [];
            foreach (TransServiceProvider::getSiteLocales() as $locale) {
                $sisterPost = $post->in($locale);

                if ($sisterPost->exists) {
                    $localeValue = [];
                    $localeValue['url'] = urlencode($sisterPost->title);
                } else {
                    $localeValue = false;
                }
                $urlParametersForForce[$locale] = $localeValue;
            }
            TransServiceProvider::forceUrlParameters($urlParametersForForce);
            /************************* Set Locale Depended Parameters ********************** END */

            /************************* Render View ********************** START */
            return view('front.posts.general.frame.main', compact(
                    'innerHTML',
                    'positionInfo'
                ) + $otherValues);

        } else {
            $this->abort('404');
        }
    }

    private function show_categories($postTypeSlug = null)
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
}
