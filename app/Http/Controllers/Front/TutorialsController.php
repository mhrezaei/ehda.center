<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TutorialsController extends Controller
{
    private $postTypesSlugs;
    private $postTypes;

    public function __construct()
    {
        $this->postTypesSlugs = ['tutorial-text', 'tutorial-image', 'tutorial-video'];
        $this->postTypes = Posttype::whereIn('slug', $this->postTypesSlugs)
            ->get();
    }

    public function archive()
    {
        $postsListHtml = PostsServiceProvider::showList([
            'type'      => ['tutorial-text', 'tutorial-image', 'tutorial-video'],
            'variables' => [ // Variables that will be sent to view file
                'twoColumns' => true,
            ],
        ]);

        $slideShow = Post::selector([
            'type'     => 'slideshows',
            'category' => 'tutorials-slideshow',
        ])->get();

        $compactedData = compact('postsListHtml', 'slideShow');
        $otherData = [
            'postTypes' => $this->postTypes,
        ];
        $viewData = array_merge($compactedData, $otherData);
        return view('front.tutorials.archive.main', $viewData);
    }
}
