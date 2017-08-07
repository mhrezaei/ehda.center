<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CardInquiryRequest;
use App\Http\Requests\Manage\SearchRequest;
use App\Http\Requests\Manage\VolunteerInquiryRequest;
use App\Http\Requests\Manage\VolunteerSaveRequest;
use App\Providers\YasnaServiceProvider;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class VolunteersController extends UsersController
{
	use ManageControllerTrait;

	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	protected $role_slug = 'admin';
	protected $role;
	protected $url;
	protected $grid_row;
	protected $grid_array;
	protected $toolbar_buttons;

	public function __construct()
	{
		$this->Model = new User();
		$this->Model->setSelectorPara([
			'role' => "admin",
		]);

		$this->browse_handle = 'counter';
		$this->view_folder   = 'manage.users';
		$this->role          = Role::findBySlug($this->role_slug);

		return parent::__construct();
	}

	public function browseSwitchesChild()
	{
		$role_slug     = $this->role_slug;
		$permit_module = "users-$role_slug";

		return [
			//'role_slug'       => $this->role_slug,
			'url'               => "volunteers/browse/all",
			'grid_row'          => "browse-row-for-volunteers",
			'free_toolbar_view' => "manage.users.browse-free-toolbar-for-volunteers",
			'grid_array'        => [
				[trans('validation.attributes.name_first'),200],
				trans("validation.attributes.occupation"),
				trans("validation.attributes.status"),
				trans('forms.button.action'),
			],
			'toolbar_buttons'   => [
				[
					'target'    => "manage/volunteers/create/$role_slug",
					'type'      => 'success',
					'condition' => $this->role_slug == 'admin' ? user()->as('admin')->can_any(Role::adminRoles('.create')) : user()->as('admin')->can("$permit_module.create"), //TODO: Check performance in action!
					'icon'      => 'plus-circle',
					'caption'   => trans("ehda.volunteers.create"),
				],
			],
			'more_mass_actions' => [
				['gavel', trans('forms.button.change_status'), "modal:manage/users/act/0/user-status/" . $role_slug, (user()->as('admin')->can("$permit_module.edit") and $role_slug != 'admin')],
			],
			'browse_tabs'       => [
				["all", trans('people.criteria.all')],
				['search', trans('forms.button.search')],
			],
			//'search_panel_view' => "search-for-cards",
		];

	}

	public function update($model_id, $request_role = null)
	{
		/*-----------------------------------------------
		| User Model ...
		*/
		$model = User::withTrashed()->find($model_id);

		if(!$model or !$model->id) {
			return view('errors.m410');
		}

		/*-----------------------------------------------
		| Role Model ...
		*/
		if($request_role == 'admin') {
			$role = Role::where('is_admin', 1)->first();
		}
		else {
			$role = Role::findBySlug($request_role);
		}

		if(!$role or !$role->id) {
			return view('errors.m410');
		}


		/*-----------------------------------------------
		| Run ...
		*/
		$model->spreadMeta();
		$handle = 'selector';

		return view($this->view_folder . '.' . $this->browseSwitchesChild()['grid_row'], compact('model', 'handle', 'request_role', 'role'));
	}


	public function browseChild($domain_slug = 'all', $request_tab = null)
	{
		/*-----------------------------------------------
		| If generally called ...
		*/
		if($domain_slug == 'all') {
			$allowed_array = user()->userRolesArray('browse', [], Role::adminRoles());
			if(count($allowed_array) == 1) {
				$domain_slug = str_replace("volunteer-", null, $allowed_array[0]);
				if(!$request_tab) {
					$request_tab = '8';
				}
			}
			else {
				if(!$request_tab) {
					$request_tab = 'all';
				}
				return $this->browse($this->role_slug, $request_tab, $this->browseSwitchesChild());
			}
		}

		if(!$request_tab) {
			$request_tab = 'all';
		}

		/*-----------------------------------------------
		| If called for a specific domain ...
		*/

		//dd($domain_slug) ;
		$this->role_slug         = "volunteer-$domain_slug";
		$switches                = $this->browseSwitchesChild();
		$switches['browse_tabs'] = 'auto';
		$switches['url']         = "volunteers/browse/$domain_slug";


		return $this->browse($this->role_slug, $request_tab, $switches);

	}

	public function searchChild(SearchRequest $request, $domain_slug = 'all')
	{
		if($domain_slug == 'all') {
			$switches = $this->browseSwitchesChild();
		}
		else {
			$this->role_slug         = "volunteer-$domain_slug";
			$switches                = $this->browseSwitchesChild();
			$switches['browse_tabs'] = 'auto';
			$switches['url'] .= "/$domain_slug";
		}

		return $this->search($this->role_slug, $request, $switches);
	}


	public function editorChild($model_hash_id)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$model = User::findByHashid($model_hash_id);
		if(!$model or !$model->id or !$model->withDisabled()->is_admin()) {
			return view('errors.410');
		}
		if(!$model->canEdit()) {
			return view('errors.403');
		}

		$model->spreadMeta();
		$states = State::combo();

		/*-----------------------------------------------
		| Page Preparations ...
		*/
		$page    = $this->page;
		$page[0] = ["volunteers/browse/", trans("ehda.volunteers.plural")];
		$page[1] = ["volunteers/edit/$model_hash_id", trans("ehda.volunteers.edit")];


		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.volunteer-editor", compact('page', 'model', 'states'));
	}


	public function createChild($request_role = null, $given_code_melli = false)
	{
		/*-----------------------------------------------
		| Permission ...
		*/
		$permit = false;
		if(!$request_role or $request_role == 'admin') {
			if(user()->userRolesArray('create', [], model('role')::adminRoles())) {
				$permit = true;
			}
		}
		else {
			if(user()->as('admin')->can("users-$request_role.create")) {
				$permit = true;
			}
		}
		if(!$permit) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Preparations ...
		*/
		$page    = $this->page;
		$page[0] = ["volunteers/browse/$request_role", trans("ehda.volunteers.plural")];
		$page[1] = ["volunteers/create/$request_role", trans("ehda.volunteers.create")];

		/*-----------------------------------------------
		| If a Code Melli is Given ...
		*/
		if(!YasnaServiceProvider::isCodeMelli($given_code_melli)) {
			$given_code_melli = false;
		}


		/*-----------------------------------------------
		| Model ...
		*/
		$model  = new User();
		$states = State::combo();

		if($given_code_melli) {
			$user = userFinder($given_code_melli);
			if(!$user or !$user->id) {
				$model->code_melli = $given_code_melli;
			}
			elseif(!$user->withDisabled()->is_admin()) {
				$model = $user;
			}
		}

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.volunteer-editor", compact('page', 'model', 'states', 'request_role'));

	}

	public function inquiry(VolunteerInquiryRequest $request)
	{
		$user = userFinder($request->code_melli);

		/*-----------------------------------------------
		| If not found ...
		*/
		if(!$user or !$user->id) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.volunteers.inquiry_success'),
				'callback'     => 'cardEditor(1)',
				'redirectTime' => 1,
			]);
		}

		/*-----------------------------------------------
		| If a Volunteer in the selected role ...
		*/
		//if($request->request_role and $user->withDisabled()->is_a($request->request_role)) {
		//	return $this->jsonFeedback([
		//		'ok' => "1" ,
		//		'message' => trans("ehda.volunteers.already_volunteer") ,
		//	     'callback' => "$('#divCard').slideUp('fast').html('')" ,
		//	]);
		//
		//}


		/*-----------------------------------------------
		| If a volunteer (active or blocked) ...
		*/
		if($user->withDisabled()->is_admin()) {
			return $this->jsonFeedback(1, [
				'ok'       => "1",
				'message'  => trans("ehda.volunteers.already_volunteer"),
				'callback' => 'cardEditor(2 , "' . $user->hash_id . '")',
			]);
		}


		/*-----------------------------------------------
		| Otherwise (if has card or even not) ...
		*/

		return $this->jsonFeedback(1, [
			'ok'           => 1,
			'message'      => trans('ehda.cards.inquiry_has_card'),
			'callback'     => 'cardEditor(3 , "' . $user->hash_id . '")',
			'redirectTime' => 1,
		]);

	}


	public function saveChild(VolunteerSaveRequest $request)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$data = $request->toArray();

		if($request->id) {
			$model = User::find($request->id);
			if(!$model or !$model->id) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
		}

		/*-----------------------------------------------
		| Security ...
		*/
		if($request->id and $model->withDisabled()->is_admin()) {
			$permit = $model->canEdit();
		}
		else {
			$permit = user()->as('admin')->can("users-$request->role_slug.create");
		}
		if(!$permit) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Processing Dates ...
		*/
		$carbon             = new Carbon($request->birth_date);
		$data['birth_date'] = $carbon->toDateString();

		/*-----------------------------------------------
		| Processing States ...
		*/
		$home_city = State::find($request->home_city);
		if($home_city) {
			$data['home_province'] = $home_city->parent_id;
		}
		else {
			$data['home_city']     = 0;
			$data['home_province'] = 0;
		}

		$work_city = State::find($request->work_city);
		if($work_city) {
			$data['work_province'] = $work_city->parent_id;
		}
		else {
			$data['work_province'] = 0;
			$data['home_province'] = 0;
		}


		/*-----------------------------------------------
		| Processing passwords ...
		*/
		if(!$data['id'] or $data['_password_set_to_mobile']) {
			$data['password']              = Hash::make($data['mobile']);
			$data['password_force_change'] = 1;
		}

		/*-----------------------------------------------
		| Processing Domain ...
		*/
		//$data['domain'] = user()->domain;
		//if(!$data['domain'] or $data['domain'] == 'global') {
		//	$state = State::find(user()->home_city);
		//	if($state) {
		//		$data['domain'] = $state->domain->slug;
		//	}
		//}

		/*-----------------------------------------------
		| Save ...
		*/
		$saved = User::store($data, ['status', 'role_slug']);

		/*-----------------------------------------------
		| Role...
		*/
		if($saved) {
			$saved_user = User::find($saved);
			if($saved_user and $saved_user->id and $saved_user->is_not_a($request->role_slug)) {
				$saved_user->attachRole($request->role_slug, $request->status);
			}
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $this->jsonAjaxSaveFeedback($saved, ['success_refresh' => true,]);

	}

	public function view($model_hashid)
	{
		/*-----------------------------------------------
		| Security ...
		*/
		//if(user()->as('admin')->cannot('users-card-holder.view')) {
		//	return view('errors.m403');
		//}

		/*-----------------------------------------------
		| Model ...
		*/
		$model = User::findByHashid($model_hashid);
		if(!$model or !$model->id or $model->is_not_an('admin')) {
			return view('errors.m410');
		}

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.volunteer-view", compact('model'));


	}


}
