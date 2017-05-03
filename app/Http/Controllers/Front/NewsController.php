<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{

    private $newsPrefix = 'nw-';

    public function archive()
    {
        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.news'), url_locale('news')],
        ];

        $selectConditions = [
            'type' => 'news',
        ];

        return view('front.news.archive.0', compact('selectConditions', 'breadCrumb'));
    }

    public function single($lang, $identifier)
    {
        $identifier = substr($identifier, strlen($this->newsPrefix));

        if (is_numeric($identifier)) {
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

        return view('front.news.single.0', compact('news', 'breadCrumb'));
    }
}
