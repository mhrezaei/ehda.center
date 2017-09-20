<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{
    use ManageControllerTrait;

    public function index()
    {
        return view('errors.404');
    }

    public function ramazan()
    {
        $count = User::where('created_by', '303958')->count();
        return view('front.landing.events.ramazan.0', compact('count'));
    }

    public function ramazan_count()
    {
        $data['count'] = User::where('created_by', '303958')->count();
        $data['status'] = 1;
        $data['sm'] = csrf_token();
        return json_encode($data);
    }

    public function summer()
    {
        $count = User::where('created_by', '303958')
            ->where('card_registered_at', '>', '2017-06-22 00:00:00')
            ->where('card_registered_at', '<', '2017-09-22 23:59:59')
            ->count();
        return view('front.landing.events.summer.0', compact('count'));
    }

    public function summer_count()
    {
        $data['count'] = User::where('created_by', '303958')
            ->where('card_registered_at', '>', '2017-06-22 00:00:00')
            ->where('card_registered_at', '<', '2017-09-22 23:59:59')
            ->count();
        $data['status'] = 1;
        $data['sm'] = csrf_token();
        return json_encode($data);
    }

    public function event($event)
    {
        $post = PostsServiceProvider::smartFindPost($event);
        if (!$post or !$post->exists) {
            return $this->abort('404');
        }

        return PostsServiceProvider::showPost($post);
    }

    public function event_counter($event)
    {
        $post = PostsServiceProvider::smartFindPost($event);
        if (!$post or !$post->exists) {
            return $this->abort('404', true);
        }

        $count = User::where([
            ['created_at', '>=', $post->starts_at],
            ['created_at', '<=', $post->ends_at],
        ])->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    public function football()
    {
        $post = PostsServiceProvider::smartFindPost('football');
        if (!$post or !$post->exists) {
            return $this->abort('404');
        }

        return PostsServiceProvider::showPost($post);
    }

    public function football_counter()
    {
        $post = PostsServiceProvider::smartFindPost('football');
        if (!$post or !$post->exists) {
            return $this->abort('404', true);
        }

        $count = User::where([
            ['created_at', '>=', $post->starts_at],
            ['created_at', '<=', $post->ends_at],
        ])->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}
