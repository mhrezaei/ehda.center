<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\Manage\CardInquiryRequest;
use App\Models\Post;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CardsController extends UsersController
{
	use ManageControllerTrait;

	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	protected $role_slug = 'card-holder';
	protected $role ;
	protected $url;
	protected $grid_row;
	protected $grid_array;
	protected $toolbar_buttons;

	public function __construct()
	{
		$this->page[0] = ['users', trans('people.site_users')];

		$this->Model = new User();
		$this->Model->setSelectorPara([
			'role' => "admin",
		]);

		$this->browse_handle = 'counter';
		$this->view_folder   = 'manage.users';
		$this->role = Role::findBySlug($this->role_slug);

		return parent::__construct();
	}

	public function browseSwitchesChild()
	{
		return [
			//'role_slug'       => $this->role_slug,
			'url'               => "cards/browse",
			'grid_row'          => "browse-row-for-cards",
			'grid_array'        => [
				trans('validation.attributes.name_first'),
				trans("ehda.cards.register"),
				trans('validation.attributes.home_city'),
				trans('forms.button.action'),
			],
			'toolbar_buttons'   => [
				[
					'target'    => "manage/cards/create",
					'type'      => 'success',
					'condition' => user()->as('admin')->can('users-card-holder.create'),
					'icon'      => 'plus-circle',
					'caption'   => trans("ehda.cards.create"),
				],
			],
			//'search_panel_view' => "search-for-cards",
		];

	}

	public function update($model_id, $request_role = null)
	{
		$request_role = $this->role_slug;
		$model        = User::withTrashed()->find($model_id);
		$handle       = 'selector';

		//Run...
		if(!$model) {
			return view('errors.m410');
		}
		else {
			$model->spreadMeta();
		}

		return view($this->view_folder . '.' . $this->browseSwitchesChild()['grid_row'], compact('model', 'handle', 'request_role'));
	}


	public function browseChild($request_tab = 'all')
	{
		return $this->browse($this->role_slug, $request_tab, $this->browseSwitchesChild());
	}

	public function searchChild(Request $request)
	{
		return $this->search($this->role_slug, $this->browseSwitchesChild());
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
		$page = $this->page ;
		$page[0] = ['cards/browse' , $this->role->plural_title] ;
		$page[1] = ['cards/create' , trans("ehda.cards.create")] ;

		/*-----------------------------------------------
		| If for Volunteer ...
		*/
		//@TODO: proceed to special view (a good idea would be to use a modal instead of all this crap.

		/*-----------------------------------------------
		| Model ...
		*/
		$model = new User() ;
		$states = State::combo() ;

		$model->newsletter = 1 ;

		$all_events = Post::selector([
			'type' => "event" ,
		     'domain' => "auto" ,
		])->orderBy('published_at' , 'desc')->get() ;
		$events = [];
		foreach($all_events as $event) {
			if($event->spreadMeta()->can_register_card) {
				$event[] = $event ;
			}
		}

		$model->event_id = session()->get('user_last_used_event' , 0);


		/*-----------------------------------------------
		| View ...
		*/
		return view("manage.users.card-editor",compact('page','model','states','events'));

	}

	public function inquiry(CardInquiryRequest $request)
	{
		$user = userFinder($request->code_melli) ;

		/*-----------------------------------------------
		| If not found ...
		*/
		if(!$user or !$user->id) {
			return $this->jsonFeedback(1,[
				'ok' => 1 ,
				'message' => trans('ehda.cards.inquiry_success') ,
				'callback' => 'cardEditor(1)' ,
				'redirectTime' => 1 ,
			]);
		}

		/*-----------------------------------------------
		| If already has card ... //TODO: What if is a card-holder AND a volunteer, who shouldn't be redirected to edit page?
		*/
		if($user->is_a('card-holder')) {
			return $this->jsonFeedback(1,[
				'ok' => 0 ,
				'message' => trans('ehda.cards.inquiry_has_card') ,
				'callback' => 'cardEditor(2 , "'. $user->hash_id .'")'  ,
				'redirectTime' => 1 ,
			]);
		}

		/*-----------------------------------------------
		| If a volunteer without card ...
		*/
		if($user->min(8)->is_an('admin') and $user->is_not_a('card-holder')) {
			return $this->jsonFeedback(1,[
				'ok' => 1 ,
				'message' => trans('ehda.cards.inquiry_is_volunteer') ,
				'redirect' => url("manage/cards/create/$user->id") ,
				'redirectTime' => 1 ,
			]);
		}

		if($user->max(6)->is_an('admin') ) {
			return $this->jsonFeedback(1,[
				'ok' => 1 ,
				'message' => trans('inquiry_will_be_volunteer') ,
				'redirect' => Auth::user()->can('cards.edit') ? url("manage/cards/$user->id/edit") : '' ,
			]);

		}

	}
}
