<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index()
    {
        return view('errors.404');
    }

    public function page($lang, $slug)
    {
        if (!$slug)
            return view('errors.404');

        if (is_numeric($slug))
        {
            $page = Post::find($slug);
        }
        else
        {
            $page = Post::selector([
                'slug' => $slug,
                'locale' => getLocale(),
                'type' => 'pages',
            ])->first();
        }

        if (!$page)
            return view('errors.404');

        return view('front.pages.0', compact('page'));

    }
}
