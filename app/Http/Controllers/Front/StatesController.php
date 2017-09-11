<?php

namespace App\Http\Controllers\Front;

use App\Models\Domain;
use App\Models\Post;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatesController extends Controller
{
    public function map()
    {
        $states = Domain::all();
        $infoPost = Post::findBySlug('states-info')->in(getLocale());
        if ($infoPost->exists) {
            $infoPost->spreadMeta();
            $positionInfo = [
                'group'    => $infoPost->header_title,
                'category' => $infoPost->category_title,
                'title' => $infoPost->title,
            ];
        } else {
            $positionInfo = [
                'group' => trans('front.states_entrance'),
            ];
        }

        return view('front.iranmap.main', compact('states', 'positionInfo'));
    }
}
