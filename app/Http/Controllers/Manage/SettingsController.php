<?php

namespace App\Http\Controllers\Manage;

use App\Models\Setting;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
	use ManageControllerTrait ;


	public function __construct()
	{
		$this->Model = new Setting();
		$this->browse_handle = 'counter';
		$this->view_folder   = "manage.settings";
	}

	public function search(Request $request)
	{
		return $this->index('search' , $request->keyword);
	}
	public function index($request_tab = 'template' , $keyword=null)
	{
		/*-----------------------------------------------
		| Page ...
		*/
		$page = [
			'0' => ['settings' , trans('settings.site.title')],
		     '1' => ["tab/$request_tab" , trans("settings.categories.$request_tab")],
		];

		/*-----------------------------------------------
		| Common Model ...
		*/
		$db = new Setting() ;

		/*-----------------------------------------------
		| Individual Pages ...
		*/
		switch($request_tab) {
			case 'search' :
				$page[1] = ['tab/search',trans('forms.button.search_for')." $keyword "];
				$models = Setting::whereRaw( Setting::searchRawQuery($keyword) )->where('developers_only' , '0')->orderBy('title')->paginate(100) ;
				return view("manage.settings.site", compact('page', 'models' , 'request_tab' , 'db' , 'keyword'));

			default:
				$models = Setting::where('category' , $request_tab)->where('developers_only' , '0')->orderBy('title')->paginate(100) ;
				return view("manage.settings.site", compact('page', 'models' , 'request_tab' , 'db'));
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

}
