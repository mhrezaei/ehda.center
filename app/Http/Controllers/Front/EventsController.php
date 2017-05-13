<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    use ManageControllerTrait;

    private $newsPrefix = 'ev-';

    public function archive()
    {
        $postType = Posttype::findBySlug('events')->spreadMeta();

        $breadCrumb = [
            [trans('front.events'), url_locale('')],
            [$postType->title, url_locale('events')],
        ];

        $selectConditions = [
            'type' => 'events',
            'conditions' => [
                ['starts_at', '<=', Carbon::now()],
                ['ends_at', '>=', Carbon::now()],
            ]
        ];

        $ogData['description'] = $postType->title;

        return view('front.events.archive.0', compact('selectConditions', 'breadCrumb', 'ogData'));
    }

    public function ajaxEvents()
    {

    }
}
