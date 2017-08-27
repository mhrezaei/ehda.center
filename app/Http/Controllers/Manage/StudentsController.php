<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\SearchRequest;
use App\Http\Requests\Manage\StudentCreateRequest;
use App\Models\Role;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;


class StudentsController extends UsersController
{
	use ManageControllerTrait;

	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	protected $role_slug = 'student';
	protected $role;
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
		$this->role          = Role::findBySlug($this->role_slug);

		return parent::__construct();
	}

	public function browseSwitchesChild()
	{
		return [
			//'role_slug'       => $this->role_slug,
			'url'               => "students/browse",
			'grid_row'          => "browse-row-for-students",
			'grid_array'        => [
				trans('validation.attributes.name_first'),
				trans("ehda.cards.register"),
				trans('validation.attributes.home_city'),
				trans('forms.button.action'),
			],
			'toolbar_buttons'   => [
				[
					'target'    => "modal:manage/students/create",
					'type'      => 'success',
					'condition' => user()->as('admin')->can('users-student.create'),
					'icon'      => 'plus-circle',
					'caption'   => trans("people.commands.create_new_user" , [
						'role_title' => trans("ehda.students.single") ,
					]),
				],
			],
		     'mass_actions' => [],
		     'browse_tabs' => [],
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

	public function searchChild(SearchRequest $request)
	{
		return $this->search($this->role_slug, $request, $this->browseSwitchesChild());
	}

	public function createChild()
	{
		return view('manage.users.create-student') ;
	}

	public function attachRole( StudentCreateRequest $request)
	{
		$user = userFinder($request->code_melli);

		if(!$user or !$user->id or !$user->exists) {
			return $this->jsonFeedback(trans('people.code_melli_not_found'));
		}

		$ok = $user->attachRole('student') ;

		return $this->jsonAjaxSaveFeedback( $ok , [
				'success_refresh' => "1",
		          'success_message' => trans("ehda.students.name_added" , [
		          	'name' => $user->full_name ,
		          ]) ,
		]);
	}

	public function detachRole(Request $request)
	{
		$user = User::find($request->id) ;
		if(!$user or $user->is_not_a('student')) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		$ok = $user->detachRole('student') ;

		return $this->jsonAjaxSaveFeedback( $ok , [
			'success_callback' => "rowHide('tblUsers' , '$request->id')",
		]);
	}
}
