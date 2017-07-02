<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;


class CardsController extends UsersController
{
	//use ManageControllerTrait;

	protected $Model;
	protected $browse_counter;
	protected $browse_selector;
	protected $view_folder;

	protected $role_slug = 'card-holder';
	protected $url       = "cards/browse";
	protected $grid_row  = "browse-row-for-cards";
	protected $grid_array;

	public function __construct()
	{
		$this->grid_array = [
			trans('validation.attributes.name_first'),
			trans("ehda.cards.register"),
			trans('validation.attributes.home_city'),
			trans('forms.button.action'),
		];

		$this->page[0] = ['users', trans('people.site_users')];

		$this->Model = new User();
		$this->Model->setSelectorPara([
			'role' => "admin",
		]);

		$this->browse_handle = 'counter';
		$this->view_folder   = 'manage.users';

		return parent::__construct();
	}

	public function update($model_id, $request_role = null)
	{
		$request_role = $this->role_slug ;
		$model  = User::withTrashed()->find($model_id);
		$handle = 'selector';

		//Run...
		if(!$model) {
			return view('errors.m410');
		}
		else {
			$model->spreadMeta();
		}

		return view($this->view_folder . '.' . $this->grid_row, compact('model', 'handle', 'request_role'));
	}

	public function browseRole($request_tab = 'all')
	{
		return $this->browse($this->role_slug, $request_tab, [
			'grid_row'   => $this->grid_row,
			'grid_array' => $this->grid_array,
			'url'        => $this->url,
		]);
	}

	public function searchRole(Request $request)
	{
		return $this->search($this->role_slug, $request, [
			'grid_row'   => $this->grid_row,
			'grid_array' => $this->grid_array,
			'url'        => $this->url,
			//'search_panel_view' => "search-for-cards" ,
		]);
	}

}
