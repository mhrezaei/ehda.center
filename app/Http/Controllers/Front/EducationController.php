<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Providers\AjaxFilterServiceProvider;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

class EducationController extends Controller
{
    use ManageControllerTrait;

    protected $postTypesSlugs;
    protected $postTypes;
    protected $postTypesPrefix = 'educational-';
    protected $twoColumnsList = true;

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
        $categories = $postType->categoriesIn(getLocale());

        $slideShow = PostsServiceProvider::collectPosts([
            'type'     => 'slideshows',
            'category' => 'education-slideshow',
        ]);

        $compactedData = compact('slideShow', 'postType', 'categories');
        $otherData = [];
        if ($categories->count()) {
            $otherData['filterNeeded'] = true;
        } else {
            $otherData['filterNeeded'] = false;
            $postsListHtml = PostsServiceProvider::showList([
                'type'      => $postTypeSlug,
                'variables' => [ // Variables that will be sent to view file
                    'twoColumns' => $this->twoColumnsList,
                ],
            ]);
            $otherData['postsListHtml'] = $postsListHtml;
        }
        $viewData = array_merge($compactedData, $otherData);
        return view('front.tutorials.archive.main', $viewData);
    }

    public function ajaxGetPosts(Request $request)
    {
        $hashArray = AjaxFilterServiceProvider::translateHash($request->hash);

        $referrer = URL::previous();
        $referrerParts = explodeNotEmpty('/', $referrer);
        $educationType = end($referrerParts);

        $postTypeSlug = $this->postTypesPrefix . $educationType;
        $postType = Posttype::findBySlug($postTypeSlug);
        if (!$postType->exists) {
            return $this->abort('404', true);
        }

        $selectData = [
            'type'      => $postTypeSlug,
            'variables' => [ // Variables that will be sent to view file
                'twoColumns' => $this->twoColumnsList,
            ],
        ];

        if (isset($hashArray['selectBox']['category'])) {
            if ($hashArray['selectBox']['category']) {
                $selectData['category'] = $hashArray['selectBox']['category'];
            }
        }

        $postsListHtml = PostsServiceProvider::showList($selectData);

        return view('front.tutorials.archive.list-view', compact('postsListHtml'));
    }
}
