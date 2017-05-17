<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Providers\PostsServiceProvider;
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

        $ogData['description'] = $postType->title;

        $accordion = PostsServiceProvider::showEventsAccordion();

        return view('front.events.archive.0', compact('accordion', 'breadCrumb', 'ogData'));
    }

    public function waitingEvents()
    {
        if (request()->ajax()) {
            $conditions = [
                'type' => 'events',
                'conditions' => [
                    ['starts_at', '>=', Carbon::now()],
                    ['ends_at', '>=', Carbon::now()],
                ],
                'ajax_request' => true,
            ];

            return PostsServiceProvider::showList($conditions);
        }
    }

    public function expiredEvents()
    {
        if (request()->ajax()) {
            $conditions = [
                'type' => 'events',
                'conditions' => [
                    ['starts_at', '<=', Carbon::now()],
                    ['ends_at', '<=', Carbon::now()],
                ],
                'ajax_request' => true,
                'max_per_page' => 5,
            ];

            return PostsServiceProvider::showList($conditions);
        }
    }
}
