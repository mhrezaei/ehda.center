<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    use ManageControllerTrait;


    private $newsPrefix = 'nw-';

    public function archive()
    {
        $postType = Posttype::findBySlug('news')->spreadMeta();
        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.news'), url_locale('news')],
        ];

        $selectConditions = [
            'type' => 'news',
        ];

        $ogData['description'] = trans('front.news');
        if ($postType->defatul_featured_image) {
            $ogData['image'] = url($postType->defatul_featured_image);
        }

        $newsListHTML = PostsServiceProvider::showList($selectConditions);

        return view('front.news.archive.0', compact('newsListHTML', 'breadCrumb', 'ogData'));
    }

    public function single($lang, $identifier)
    {
        $identifier = substr($identifier, strlen($this->newsPrefix));

        $dehashed = hashid_decrypt($identifier, 'ids');
        if (is_array($dehashed) and is_numeric($identifier = $dehashed[0])) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $news = Post::where([
            $field => $identifier,
            'type' => 'news'
        ])->first();

        if (!$news) {
            $this->abort(410);
        }

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.news'), url_locale('news')],
            [$news->title, url_locale('news/' . $this->newsPrefix . $news->id)],
        ];

        $ogData = [
            'title' => $news->title,
            'description' => $news->getAbstract(),
        ];
        if($news->viewable_featured_image) {
            $ogData['image'] = $news->viewable_featured_image;
        }

        $newsHTML = PostsServiceProvider::showPost($news);

        return view('front.news.single.0', compact('newsHTML', 'breadCrumb', 'ogData'));
    }
}
