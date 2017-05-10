<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Posttype;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeammatesController extends Controller
{
    use ManageControllerTrait;

    private $faqPrefix = 'tm-';

    public function archive()
    {
        $postType = Posttype::findBySlug('teammates')->spreadMeta();
        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [$postType->title, url_locale('teammates')],
        ];

        $selectConditions = [
            'type' => 'teammates',
            'sort' => 'asc',
            'max_per_page' => -1,
        ];

        $ogData['description'] = $postType->title;

        return view('front.teammates.archive.0', compact('selectConditions', 'breadCrumb', 'ogData'));
    }

    public function single($lang, $identifier)
    {
        $identifier = substr($identifier, strlen($this->faqPrefix));

        if (is_numeric($identifier)) {
            $field = 'id';
        } else {
            $field = 'slug';
        }
        $person = Post::where([
            $field => $identifier,
            'type' => 'teammates'
        ])->first();

        if (!$person) {
            $this->abort(410);
        }
        $person->spreadMeta();

        $breadCrumb = [
            [trans('front.home'), url_locale('')],
            [trans('front.teammates'), url_locale('teammates')],
            [$person->title, url_locale('teammates/' . $this->faqPrefix . $person->id)],
        ];

        $ogData = [
            'description' => $person->title . ' - ' . $person->seat . '<br />' . $person->getAbstract(),
        ];
        if ($person->featured_image) {
            $ogData['image'] = $person->featured_image;
        }

        return view('front.teammates.single.0', compact('person', 'breadCrumb', 'ogData'));
    }
}
