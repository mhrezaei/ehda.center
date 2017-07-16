<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\CardInquiryRequest;
use App\Http\Requests\Manage\CardSaveRequest;
use App\Http\Requests\Manage\SearchRequest;
use App\Models\Post;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
		return [
			//'role_slug'       => $this->role_slug,
			'url'               => "volunteers/browse",
			'grid_row'          => "browse-row-for-volunteers",
			'free_toolbar_view' => "manage.users.browse-free-toolbar-for-volunteers",
			'grid_array'        => [
				trans('validation.attributes.name_first'),
				trans("validation.attributes.occupation"),
				trans("validation.attributes.status"),
				trans('forms.button.action'),
			],
			'toolbar_buttons'   => [
				[
					'target'    => "manage/volunteers/create",
					'type'      => 'success',
					'condition' => user()->as('admin')->can('users-volunteer.create'), //TODO: somehow check this on multiple roles
					'icon'      => 'plus-circle',
					'caption'   => trans("ehda.volunteers.create"),
				],
			],
			'more_mass_actions' => [
				['gavel', trans('forms.button.change_status'), "modal:manage/users/act/0/user-status/".$this->role_slug , $this->role_slug != 'admin'],
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
		| Model ...
		*/
		$role   = Role::findBySlug($request_role);
		$model  = User::withTrashed()->find($model_id);
		$handle = 'selector';

		if(!$model or !$role or !$model->id or !$role->id) {
			return view('errors.m410');
		}
		else {
			$model->spreadMeta();
		}

		/*-----------------------------------------------
		| Run ...
		*/

		return view($this->view_folder . '.' . $this->browseSwitchesChild()['grid_row'], compact('model', 'handle', 'request_role' , 'role'));
	}


	public function browseChild($domain_slug = 'all', $request_tab = 'all')
	{
		/*-----------------------------------------------
		| If generally called ...
		*/
		if($domain_slug == 'all') {
			return $this->browse($this->role_slug, $request_tab, $this->browseSwitchesChild());
		}

		/*-----------------------------------------------
		| If called for a specific domain ...
		*/

		$this->role_slug         = "volunteer-$domain_slug";
		$switches = $this->browseSwitchesChild();
		$switches['browse_tabs'] = 'auto';
		$switches['url'] .= "/$domain_slug";


		return $this->browse($this->role_slug, $request_tab, $switches);

	}

	public function searchChild(SearchRequest $request)
	{
		return $this->search($this->role_slug, $request, $this->browseSwitchesChild());
	}

	public function editorChild($model_hash_id)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$model = User::findByHashid($model_hash_id);
		if(!$model or !$model->id or $model->is_not_a('card-holder')) {
			return view('errors.410');
		}
		if(!$model->canEdit()) {
			return view('errors.403');
		}

		$model->spreadMeta();
		$states = State::combo();

		$all_events = Post::selector([
			'type'   => "event",
			'domain' => "auto",
		])->orderBy('published_at', 'desc')->get()
		;

		$events = [];
		foreach($all_events as $event) {
			if($event->spreadMeta()->can_register_card) {
				$events[] = $event;
			}
		}

		$model->event_id = session()->get('user_last_used_event', 0);

		/*-----------------------------------------------
		| Page Preparations ...
		*/
		$page    = $this->page;
		$page[0] = ['cards/browse', $this->role->plural_title];
		$page[1] = ["cards/edit/$model_hash_id", trans("ehda.cards.edit")];


		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.card-editor", compact('page', 'model', 'states', 'events'));
	}


	public function createChild($volunteer_id = 0)
	{
		/*-----------------------------------------------
		| Permission ...
		*/
		if(user()->as('admin')->cannot('users-card-holder.create')) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Preparations ...
		*/
		$page    = $this->page;
		$page[0] = ['cards/browse', $this->role->plural_title];
		$page[1] = ['cards/create', trans("ehda.cards.create")];

		/*-----------------------------------------------
		| If for Volunteer ...
		*/
		//@TODO: proceed to special view (a good idea would be to use a modal instead of all this crap.

		/*-----------------------------------------------
		| Model ...
		*/
		$model  = new User();
		$states = State::combo();

		$model->newsletter = 1;

		$all_events = Post::selector([
			'type'   => "event",
			'domain' => "auto",
		])->orderBy('published_at', 'desc')->get()
		;
		$events     = [];
		foreach($all_events as $event) {
			if($event->spreadMeta()->can_register_card) {
				$events[] = $event;
			}
		}

		$model->event_id = session()->get('user_last_used_event', 0);


		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.users.card-editor", compact('page', 'model', 'states', 'events'));

	}

	public function inquiry(CardInquiryRequest $request)
	{
		$user = userFinder($request->code_melli);

		/*-----------------------------------------------
		| If not found ...
		*/
		if(!$user or !$user->id) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.cards.inquiry_success'),
				'callback'     => 'cardEditor(1)',
				'redirectTime' => 1,
			]);
		}

		/*-----------------------------------------------
		| If already has card ...
		*/
		if($user->is_a('card-holder')) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.cards.inquiry_has_card'),
				'callback'     => 'cardEditor(2 , "' . $user->hash_id . '")',
				'redirectTime' => 1,
			]);
		}

		/*-----------------------------------------------
		| If a volunteer without card ...
		*/
		if($user->min(8)->is_an('admin') and $user->is_not_a('card-holder')) {
			return $this->jsonFeedback(1, [
				'ok'           => 1,
				'message'      => trans('ehda.cards.inquiry_is_volunteer'),
				'redirect'     => url("manage/cards/create/$user->id"),
				'redirectTime' => 1,
			]);
		}

		if($user->max(6)->is_an('admin')) {
			return $this->jsonFeedback(1, [
				'ok'       => 1,
				'message'  => trans('inquiry_will_be_volunteer'),
				'redirect' => Auth::user()->can('cards.edit') ? url("manage/cards/$user->id/edit") : '',
			]);

		}

	}

	public function saveChild(CardSaveRequest $request)
	{
		/*-----------------------------------------------
		| Model ...
		*/
		$data = $request->toArray();

		if($request->id) {
			unset($data['event_id']);
			$model = User::find($request->id);
			if(!$model or !$model->id) {
				return $this->jsonFeedback(trans('validation.http.Error410'));
			}
			if(!$model->canEdit()) {
				return $this->jsonFeedback(trans('validation.http.Error403'));
			}
		}

		/*-----------------------------------------------
		| Processing Dates ...
		*/
		$carbon             = new Carbon($request->birth_date);
		$data['birth_date'] = $carbon->toDateString();

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
		$data['domain'] = user()->domain;
		if(!$data['domain'] or $data['domain'] == 'global') {
			$state = State::find(user()->home_city);
			if($state) {
				$data['domain'] = $state->domain->slug;
			}
		}

		/*-----------------------------------------------
		| Register Date and Card No...
		*/
		if(!$request->id or (isset($model) and $model->is_not_a('card-holder'))) {
			$data['card_registered_at'] = Carbon::now()->toDateTimeString();
			$data['card_no']            = User::generateCardNo();
		}


		/*-----------------------------------------------
		| user_last_used_event ...
		*/
		session()->put('user_last_used_event', $request->event_id);

		/*-----------------------------------------------
		| Save ...
		*/
		$saved = User::store($data);

		/*-----------------------------------------------
		| Role...
		*/
		if($saved) {
			$saved_user = User::find($saved);
			if($saved_user and $saved_user->id and $saved_user->is_not_a('card-holder')) {
				$saved_user->attachRole('card-holder');
			}
		}

		/*-----------------------------------------------
		| Send to Print ...
		*/
		if($data['_submit'] == 'print') {
			//@TODO
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $this->jsonAjaxSaveFeedback($saved, ['success_refresh' => true,]);

	}


}
