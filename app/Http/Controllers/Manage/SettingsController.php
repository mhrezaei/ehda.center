<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\PosttypeDownstreamSaveRequest;
use App\Models\Pack;
use App\Models\Posttype;
use App\Models\Setting;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SettingsController extends Controller
{
	use ManageControllerTrait;


	public function __construct()
	{
		$this->Model         = new Setting();
		$this->browse_handle = 'counter';
		$this->view_folder   = "manage.settings";
	}

	public function search(Request $request)
	{
		return $this->index('search', $request->keyword);
	}

	public function index($request_tab = 'template', $keyword = null)
	{
		/*-----------------------------------------------
		| Page ...
		*/
		$page = [
			'0' => ['settings', trans('settings.downstream')],
			'1' => ["tab/$request_tab", trans("settings.categories.$request_tab")],
		];

		/*-----------------------------------------------
		| Common Model ...
		*/
		$db = new Setting();

		/*-----------------------------------------------
		| Individual Pages ...
		*/
		switch ($request_tab) {
			case 'search' :
				$page[1] = ['tab/search', trans('forms.button.search_for') . " $keyword "];
				$models  = Setting::whereRaw(Setting::searchRawQuery($keyword))->where('developers_only', '0')->orderBy('title')->paginate(100);

				return view("manage.settings.site", compact('page', 'models', 'request_tab', 'db', 'keyword'));

			case 'packs':
				$page[1] = ['tab/packs', trans('posts.packs.plural')];
				$models  = Posttype::where('features' , 'like' , '%basket%')->orderBy('title')->paginate(100);

				return view("manage.settings.packs", compact('page', 'models', 'request_tab', 'db'));

			default:
				$models = Setting::where('category', $request_tab)->where('developers_only', '0')->orderBy('title')->paginate(100);

				return view("manage.settings.site", compact('page', 'models', 'request_tab', 'db'));
		}


	}

	public function save(Request $request)
	{
		//Preparations...
		$data  = $request->toArray();
		$model = Setting::find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		return $this->jsonAjaxSaveFeedback($model->saveRequest($request), [
			'success_callback' => "rowUpdate('tblSettings','$request->id')",
		]);

	}

	public function posttypeRootForm($type_id)
	{
		/*-----------------------------------------------
		| Model Receive ...
		*/
		$model = Posttype::find($type_id);
		if(!$model) {
			return view('errors.410');
		}
		else {
			$model->spreadMeta();
			$model->fresh_time_duration = $model->fresh_time_duration / (24 * 60);
		}
		// Security (can:super) is checked in the route.

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.settings.posttypes-downstream", compact('model'));


	}

	public function savePosttypeDownstream(PosttypeDownstreamSaveRequest $request)
	{
		$data = $request->toArray();

		if($data['fresh_time_duration']) {
			$data['fresh_time_duration'] = $data['fresh_time_duration'] * 24 * 60;
		}

		$ok = Posttype::store($data);

		return $this->jsonAjaxSaveFeedback($ok);
	}

	public function createPackRootForm($type_id)
	{
		$type = Posttype::find($type_id) ;
		if(!$type or !$type->exists() or $type->hasnot('basket')) {
			return view('errors.m410');
		}
		else {
			$model = new Pack() ;
			$model->type = $type->slug ;
		}

		return view("manage.settings.packs-edit",compact('model'));


	}

}
