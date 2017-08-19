<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    use ManageControllerTrait;

    private $postTypesSlugs;
    private $postTypes;
    private $postTypesPrefix = 'educational-';

    public function __construct()
    {
        $this->postTypesSlugs = ['tutorial-text', 'tutorial-image', 'tutorial-video'];
        $this->postTypes = Posttype::whereIn('slug', $this->postTypesSlugs)
            ->get();
    }

    public function archive($lang, $educationType)
    {
        $postTypeSlug = $this->postTypesPrefix . $educationType;
        $postType = Posttype::findBySlug($postTypeSlug);
        if (!$postType->exists) {
            return $this->abort('404');
        }
        $postsListHtml = PostsServiceProvider::showList([
            'type'      => $postTypeSlug,
            'variables' => [ // Variables that will be sent to view file
                'twoColumns' => true,
            ],
        ]);

        $slideShow = Post::selector([
            'type'     => 'slideshows',
            'category' => 'education-slideshow',
        ])->get();

        $compactedData = compact('postsListHtml', 'slideShow', 'postType');
        $otherData = [];
        $viewData = array_merge($compactedData, $otherData);
        return view('front.tutorials.archive.main', $viewData);
    }
}
