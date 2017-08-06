<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Providers\PostsServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TutorialsController extends Controller
{
    public function archive()
    {
        $postsListHtml = PostsServiceProvider::showList([
            'type' => ['tutorial-text', 'tutorial-image', 'tutorial-video'],
            'twoColumns' => true,
        ]);

        return view('front.tutorials.archive.main', compact('postsListHtml'));
    }
}
