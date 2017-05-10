<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqsController extends Controller
{
    use ManageControllerTrait;

    private $faqPrefix = 'faq-';

    public function archive()
    {
        $postType = Posttype::findBySlug('faqs')->spreadMeta();

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.faqs'), url_locale('faqs')],
        ];

        $selectConditions = [
            'type' => 'faqs',
            'sort' => 'asc',
        ];

        $ogData['description'] = trans('front.faqs');
        if ($postType->defatul_featured_image) {
            $ogData['image'] = url($postType->defatul_featured_image);
        }

        return view('front.faqs.archive.0', compact('selectConditions', 'breadCrumb', 'ogData'));
    }

    public function single($lang, $identifier)
    {
        $identifier = substr($identifier, strlen($this->faqPrefix));

        if (is_numeric($identifier)) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $faq = Post::where([
            $field => $identifier,
            'type' => 'faqs'
        ])->first();

        if (!$faq) {
            $this->abort(410);
        }

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.faqs'), url_locale('faqs')],
            [$faq->title, url_locale('faqs/' . $this->faqPrefix . $faq->id)],
        ];

        $ogData = [
            'title' => $faq->title,
            'description' => $faq->getAbstract(),
        ];

        if($faq->viewable_featured_image) {
            $ogData['image'] = $faq->viewable_featured_image;
        }

        return view('front.faqs.single.0', compact('faq', 'breadCrumb', 'ogData'));
    }
}
