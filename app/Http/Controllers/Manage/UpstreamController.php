<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\DownstreamSaveRequest;
use App\Http\Requests\Manage\PosttypeSaveRequest;
use App\Models\Posttype;
use App\Models\Setting;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UpstreamController extends Controller
{
	use ManageControllerTrait;
	protected $Model ;
	protected $browse_counter ;
	protected $browse_selector ;
	protected $view_folder ;

	public function __construct()
	{
		$this->page[0] = ['upstream' , trans('settings.upstream')];

		$this->view_folder = "manage.upstream" ;

	}

	public function index($request_tab = 'downstream' , $parent_id = 0)
	{
		//Preparetions...
		$page = $this->page;
		$page[1] = [$request_tab , trans("settings.$request_tab")];

		//Model...
		switch($request_tab) {
			case 'states' :
				$models = State::get_provinces()->orderBy('title')->get();
				break;

			case 'posttypes':
				$models = Posttype::orderBy('title')->paginate(user()->preference('max_rows_per_page'));
				break;

			case 'downstream' :
				$models = Setting::orderBy('category')->orderBy('title')->paginate(user()->preference('max_rows_per_page')) ;
				break;

			case 'categories' :
				$models = State::get_provinces()->orderBy('title')->get();
				break;


			default :
				return view('errors.404');
		}

		//View...
		return view("manage.settings.$request_tab", compact('page', 'models'));

	}
	public function search($request_tab , Request $request)
	{
		$key = $request->keyword;
//		if(!$key)
//			return $this->index($request_tab);

		//Preparation...
		$page = $this->page;
		$page[1] = [$request_tab , trans("settings.$request_tab")];
		$view = "manage.settings." ;

		switch($request_tab) {
			case 'downstream' :
				$models = Setting::where('title' , 'like' , "%$key%")->orWhere('slug' , 'like' , "%$key%")->orderBy('title')->paginate(user()->preference('max_rows_per_page'));
				$view .= 'downstream' ;
				$page[2] = ['search',trans('forms.button.search_for')." $key",''];
				break;

			case 'states' :
				$model_data = State::where([
						['title' , 'like' , '%'.$key.'%'] ,
						['parent_id' , '<>' , '0']
				])->orderBy('title')->get();
				$view .= "states-cities";
				$page[2] = ['search',trans('forms.button.search_for')." $key",''];
				break;

			default:
				return view('templates.say' , ['array'=>"What the hell is $request_tab?"]); //@TODO: REMOVE THIS
				return view('errors.404');
		}

		//View...
		return view($view, compact('page', 'models' , 'key'));

	}

	public function item($request_tab, $item_id)
	{

		//Preparation...
		$page = $this->page;
		$page[1] = [$request_tab , trans("manage.settings.$request_tab")];
		$page[2] = ['edit',null,''];
		$view = "manage.settings." ;

		switch($request_tab) {
			case 'states':
				$province = State::find($item_id) ;
				if(!$province or !$province->isProvince())
					return view('errors.410');
				$model_data = State::get_cities($item_id)->orderBy('title')->get();
				$page[2][1] = trans('manage.settings.cities_of' , ['province'=>$province->title]) ;
				return view('manage.settings.states-cities', compact('page', 'model_data' , 'province'));
				break;

			case 'downstream' :
				$model = Setting::find($item_id) ;
				if(!$model)
					return view('errors.m410');

				return view('manage.settings.downstream-value' , compact('model'));
				break;

			case 'branches' :
				$branch = Branch::find($item_id) ;
				if(!$branch)
					return view('errors.410');
				$model_data = $branch->categories()->get() ;
				$page[1] = [$request_tab , trans("manage.settings.categories")];
				$page[2] = ['categories' , $branch->title() , $item_id];
				return view('manage.settings.categories', compact('page', 'model_data','branch'));
				break;

			default:
				return view('templates.say' , ['array'=>"What the hell is $request_tab?"]); //@TODO: REMOVE THIS

		}


		if(!View::exists($view))
			return view('templates.say' , ['array'=>"View '$view' is not found."]); //@TODO: REMOVE THIS



		if(!isset($model_data) or !$model_data or !View::exists($view))
			return view('errors.m404');

		//View...
		return view($view, compact('page', 'model_data'));

	}


	public function editor($request_tab , $item_id=0 , $parent_id=0)
	{
		switch($request_tab) {
			case 'city' :
				if($item_id) {
					$model = State::find($item_id) ;
					if(!$model or $model->isProvince()) {
						return view('errors.m410');
					}
					$provinces = State::get_provinces()->orderBy('title')->get() ;
				}
				else {
					if(!$parent_id) {
						return view('errors.m404');
					}
					$provinces = State::get_provinces()->orderBy('title')->get() ;
					$model = new State() ;
					$model->parent_id = $parent_id ;
				}
				return view('manage.settings.states-cities-edit', compact('model' , 'provinces'));

			case 'state' :
				if($item_id) {
					$model = State::find($item_id) ;
					if(!$model or !$model->isProvince())
						return view('errors.m410');
				}
				else
					$model = new State() ;
				return view('manage.settings.states-edit', compact('model'));

			case 'downstream' :
				if($item_id>0) {
					$model = Setting::find($item_id);
					if(!$model)
						return trans('validation.invalid');
				}
				else {
					$model = new Setting() ;
				}
				return view('manage.settings.downstream-edit' , compact('model'));

			case 'department' :
				if($item_id) {
					$model = Department::find($item_id) ;
					if(!$model)
						return view('errors.m410');
				}
				else {
					$model = new Department() ;
				}
				return view('manage.settings.departments-edit' , compact('model'));
				break;


			case 'posttype' :
				if($item_id) {
					$model = Posttype::find($item_id);
					if(!$model)
						return view('errors.m410');
				}
				else {
					$model = new Posttype();
					$model->template = 'post' ;
					$model->features = 'title text';
				}
				return view('manage.settings.posttypes-edit', compact('model'));

			case 'categories' :
				if($item_id) {
					$model = Category::find($item_id);
					if(!$model)
						return view('errors.m410');
				}
				else {
					$model = new Category() ;
					$model->branch_id = $parent_id ;
				}
				$branches = Branch::selector('category') ;
				return view('manage.settings.categories_edit' , compact('model' , 'branches'));

			default:
				return view('errors.m404');
		}

	}

	public function saveDownstream(DownstreamSaveRequest $request)
	{
		if($request->_submit == 'save') {
			return $this->jsonAjaxSaveFeedback(Setting::store($request) ,[
					'success_refresh' => 1,
			]);
		}
		else {
			return $this->jsonAjaxSaveFeedback(Setting::destroy($request->id) , [
					'success_refresh' => 1,
			]);
		}
	}

	public function setDownstream(Request $request)
	{
		//Preparations...
		$data = $request->toArray();
		$model = Setting::find($request->id);
		if(!$model)
			return $this->jsonFeedback(trans('validation.http.Eror410'));

		return $this->jsonAjaxSaveFeedback($model->saveRequest($request) , [
				'success_refresh' => 0,
		]);

	}

	public function savePosttype(PosttypeSaveRequest $request)
	{
		//If Save...
		if($request->_submit == 'save') {
			return $this->jsonAjaxSaveFeedback(Posttype::store($request) ,[
					'success_refresh' => 1,
			]);
		}

		//If Delete...
		if($request->_submit == 'delete') {
			$model = Posttype::find($request->id) ;
			if(!$model)
				return $this->jsonFeedback();

			return $this->jsonAjaxSaveFeedback(Posttype::destroy($request->id) ,[
					'success_refresh' => 1,
			]);
		}

	}



	public function loginAs(Request $request)
	{
		$user = User::find($request->id) ;
		if(!$user->hasRole('admin'))
			return $this->jsonFeedback('user is not as admin');


		session()->put('logged_developer' , encrypt(user()->id)) ;
		$ok = Auth::loginUsingId( $user->id );
		return $this->jsonAjaxSaveFeedback($ok , [
				'success_redirect' => url('/manage'),
		]);

	}

}
