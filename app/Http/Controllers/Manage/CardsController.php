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

	public function __construct()
	{
		$this->page[0] = ['users', trans('people.site_users')];

		$this->Model = new User();
		$this->Model->setSelectorPara([
			'role' => "admin",
		]);

		$this->browse_handle = 'counter';
		$this->view_folder   = 'manage.users';
	}

	public function browseCards($request_tab = 'all')
	{
		return $this->browse('card-holder' , $request_tab , [
			'grid_row' => "browse-row-for-cards" ,
		     'grid_array' => [
			     trans('validation.attributes.name_first'),
			     trans("ehda.cards.register"),
			     trans('validation.attributes.home_city'),
			     trans('forms.button.action'),
		     ] ,
		     'url' => "cards/browse" ,
		]) ;
	}

}
