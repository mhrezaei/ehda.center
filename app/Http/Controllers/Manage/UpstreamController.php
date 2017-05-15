<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CitySaveRequest;
use App\Http\Requests\Manage\DownstreamSaveRequest;
use App\Http\Requests\Manage\PackageSaveRequest;
use App\Http\Requests\Manage\PosttypeSaveRequest;
use App\Http\Requests\Manage\ProvinceSaveRequest;
use App\Http\Requests\Manage\RoleSaveRequest;
use App\Models\Folder;
use App\Models\Unit;
use App\Models\Posttype;
use App\Models\Role;
use App\Models\Setting;
use App\Models\State;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class UpstreamController extends Controller
{
	use ManageControllerTrait;
	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	public function __construct()
	{
		$this->page[0] = ['upstream', trans('settings.upstream')];

		$this->view_folder = "manage.upstream";

	}

	public function index($request_tab = 'downstream', $parent_id = 0)
	{
		//Preparetions...
		$page    = $this->page;
		$page[1] = [$request_tab, trans("settings.$request_tab")];

		//Model...
		switch ($request_tab) {
			case 'states' :
				$models = State::getProvinces()->orderBy('title')->paginate(user()->preference('max_rows_per_page'));
				break;

			case 'posttypes':
				$models = Posttype::orderBy('order')->paginate(user()->preference('max_rows_per_page'));
				break;

			case 'downstream' :
				$models = Setting::orderBy('category')->orderBy('title')->paginate(user()->preference('max_rows_per_page'));
				break;

			case 'roles' :
				$models = Role::withTrashed()->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
				break;

			case 'packages':
				$models = Unit::withTrashed()->orderBy('deleted_at')->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
				break;

			default :
				return view('errors.404');
		}

		//View...
		return view("manage.settings.$request_tab", compact('page', 'models'));

	}

	public function search($request_tab, Request $request)
	{
		$key = $request->keyword;
		//		if(!$key)
		//			return $this->index($request_tab);

		//Preparation...
		$page    = $this->page;
		$page[1] = [$request_tab, trans("settings.$request_tab")];
		$view    = "manage.settings.";

		switch ($request_tab) {
			case 'downstream' :
				$models = Setting::where('title', 'like', "%$key%")->orWhere('slug', 'like', "%$key%")->orderBy('title')->paginate(user()->preference('max_rows_per_page'));
				$view .= 'downstream';
				$page[2] = ['search', trans('forms.button.search_for') . " $key", ''];
				break;

			case 'posttypes':
				$models = Posttype::where('title', 'like', "%$key%")->orWhere('slug', 'like', "%$key%")->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
				$view .= "posttypes";
				$page[2] = ['search', trans('forms.button.search_for') . " $key", ''];
				break;

			case 'states' :
				$models = State::where([
					['title', 'like', '%' . $key . '%'],
					['parent_id', '<>', '0'],
				])->orderBy('title')->paginate(user()->preference('max_rows_per_page'))
				;
				$view .= "states-cities";
				$page[2] = ['search', trans('forms.button.search_for') . " $key", ''];
				break;

			default:
				return view('templates.say', ['array' => "What the hell is $request_tab?"]); //@TODO: REMOVE THIS
				return view('errors.404');
		}

		//View...
		return view($view, compact('page', 'models', 'key'));

	}

	public function item($request_tab, $item_id)
	{

		//Preparation...
		$page    = $this->page;
		$page[1] = [$request_tab, trans("settings.$request_tab")];
		$page[2] = ['edit', null, ''];
		$view    = "manage.settings.";

		switch ($request_tab) {
			case 'states':
				$province = State::find($item_id);
				if(!$province or !$province->isProvince()) {
					return view('errors.410');
				}
				$models     = State::getCities($item_id)->orderBy('title')->paginate(user()->preference('max_rows_per_page'));
				$page[2][1] = trans('settings.cities_of', ['province' => $province->title]);

				return view('manage.settings.states-cities', compact('page', 'models', 'province'));
				break;

			case 'downstream' :
				$model = Setting::find($item_id);
				if(!$model) {
					return view('errors.m410');
				}

				return view('manage.settings.downstream-value', compact('model'));
				break;

			default:
				return view('templates.say', ['array' => "What the hell is $request_tab?"]); //@TODO: REMOVE THIS

		}
	}


	public function editor($request_tab, $item_id = 0, $parent_id = 0)
	{
		switch ($request_tab) {
			case 'package':
				if($item_id) {
					$model = Unit::withTrashed()->find($item_id);
					if(!$model) {
						return view('errors.410');
					}
				}
				else {
					$model = new Unit() ;
					$model->is_continuous = 0 ;
				}

				return view("manage.settings.packages-edit", compact('model'));

				break;

			case 'city' :
				if($item_id) {
					$model = State::find($item_id);
					if(!$model or $model->isProvince()) {
						return view('errors.m410');
					}
					$provinces = State::getProvinces()->orderBy('title')->get();
				}
				else {
					if(!$parent_id) {
						return view('errors.m404');
					}
					$provinces        = State::getProvinces()->orderBy('title')->get();
					$model            = new State();
					$model->parent_id = $parent_id;
				}

				return view('manage.settings.states-cities-edit', compact('model', 'provinces'));

			case 'state' :
				if($item_id) {
					$model = State::find($item_id);
					if(!$model or !$model->isProvince()) {
						return view('errors.m410');
					}
				}
				else {
					$model = new State();
				}

				return view('manage.settings.states-edit', compact('model'));

			case 'downstream' :
				if($item_id > 0) {
					$model = Setting::find($item_id);
					if(!$model) {
						return trans('validation.invalid');
					}
				}
				else {
					$model = new Setting();
				}

				return view('manage.settings.downstream-edit', compact('model'));

			case 'role' :
				if($item_id > 0) {
					$model = Role::withTrashed()->find($item_id);
					if($model) {
						$model->spreadMeta();
					}
					if(!$model) {
						return trans('validation.invalid');
					}
				}
				else {
					$model = new Role();
				}

				return view("manage.settings.roles-edit", compact('model'));


			case 'department' :
				if($item_id) {
					$model = Department::find($item_id);
					if(!$model) {
						return view('errors.m410');
					}
				}
				else {
					$model = new Department();
				}

				return view('manage.settings.departments-edit', compact('model'));
				break;


			case 'posttype' :
				if($item_id) {
					$model = Posttype::find($item_id);
					if(!$model) {
						return view('errors.m410');
					}
				}
				else {
					$model           = new Posttype();
					$model->template = 'post';
					$model->features = 'title text';
				}

				return view('manage.settings.posttypes-edit', compact('model'));

			case 'categories' :
				if($item_id) {
					$model = Category::find($item_id);
					if(!$model) {
						return view('errors.m410');
					}
				}
				else {
					$model            = new Category();
					$model->branch_id = $parent_id;
				}
				$branches = Branch::selector('category');

				return view('manage.settings.categories_edit', compact('model', 'branches'));

			default:
				return view('errors.m404');
		}

	}

	public function savePackage(PackageSaveRequest $request)
	{
		$data = $request->toArray();
		if($request->_submit == 'active') {
			$data['deleted_at'] = null;
		}
		elseif(!$data['deleted_at']) {
			$data['deleted_at'] = Carbon::now()->toDateTimeString();
		}

		return $this->jsonAjaxSaveFeedback( Unit::store($data) , [
				'success_callback' => "",
				'success_refresh' => "1",
		]);
	}

	public function saveDownstream(DownstreamSaveRequest $request)
	{
		if($request->_submit == 'save') {
			return $this->jsonAjaxSaveFeedback(Setting::store($request), [
				'success_refresh' => 1,
			]);
		}
		else {
			return $this->jsonAjaxSaveFeedback(Setting::destroy($request->id), [
				'success_refresh' => 1,
			]);
		}
	}


	public function savePosttype(PosttypeSaveRequest $request)
	{
		//If Save...
		if($request->_submit == 'save') {
			return $this->jsonAjaxSaveFeedback(Posttype::store($request), [
				'fake' => $request->id? '' : Folder::updateDefaultFolders(),
				'success_refresh' => 1,
			]);
		}

		//If Delete...
		if($request->_submit == 'delete') {
			$model = Posttype::find($request->id);
			if(!$model) {
				return $this->jsonFeedback();
			}

			return $this->jsonAjaxSaveFeedback(Posttype::destroy($request->id), [
				'success_refresh' => 1,
			]);
		}

	}


	public function saveProvince(ProvinceSaveRequest $request)
	{
		//If Save...
		if($request->_submit == 'save') {
			return $this->jsonAjaxSaveFeedback(State::store($request), [
				'success_refresh' => 1,
			]);
		}

		//If Delete...
		if($request->_submit == 'delete') {
			$model = State::find($request->id);
			if(!$model or !$model->isProvince() or $model->cities()->count()) {
				return $this->jsonFeedback();
			}

			return $this->jsonAjaxSaveFeedback(State::destroy($request->id), [
				'success_refresh' => 1,
			]);
		}


	}

	public function saveCity(CitySaveRequest $request)
	{
		$data = $request->toArray();

		//If Save...
		if($data['_submit'] == 'save') {
			$data['parent_id'] = $data['province_id'];
			unset($data['province_id']);

			return $this->jsonAjaxSaveFeedback(State::store($data), [
				'success_refresh' => 1,
			]);
		}

		//If Delete...
		if($data['_submit'] == 'delete') {
			return $this->jsonAjaxSaveFeedback(State::destroy($data['id']), [
				'success_refresh' => 1,
			]);
		}

	}


	public function saveRole(RoleSaveRequest $request)
	{
		switch ($request->toArray()['_submit']) {
			case 'save' :
				$ok = Role::store($request);
				break;

			case 'delete' :
				$ok = Role::where('id', $request->id)->delete();
				break;

			case 'restore' :
				$ok = Role::withTrashed()->where('id', $request->id)->restore();
				break;

			default:
				$ok = false;
		}

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_refresh' => 1,
		]);
	}


	public function loginAs(Request $request)
	{
		$user = User::find($request->id);
		//if(!$user->hasRole('admin')) {
		//	return $this->jsonFeedback('user is not as admin');
		//}


		session()->put('logged_developer', encrypt(user()->id));
		$ok = Auth::loginUsingId($user->id);

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_redirect' => url('/manage'),
		]);

	}

}
