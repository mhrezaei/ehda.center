<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqsController extends Controller
{

    private $faqPrefix = 'faq-';

    public function archive()
    {
        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.faqs'), url_locale('faqs')],
        ];

        $selectConditions = [
            'type' => 'faqs',
            'sort' => 'asc',
        ];

        return view('front.faqs.archive.0', compact('selectConditions', 'breadCrumb'));
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

        return view('front.faqs.single.0', compact('faq', 'breadCrumb'));
    }
}
