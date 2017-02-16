<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\AdminSaveRequest;
use App\Http\Requests\Manage\UserPasswordChangeRequest;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
	use ManageControllerTrait ;

	protected $Model ;
	protected $browse_counter ;
	protected $browse_selector ;
	protected $view_folder ;

	public function __construct()
	{
		$this->page[0] = ['admins' , trans('people.admins.title')];

		$this->Model = new User() ;
		$this->Model->setSelectorPara([
				'role' => "admin",
		]);

		$this->browse_counter = true ;
		$this->browse_selector = false ;
		$this->view_folder = "manage.admins" ;

	}

	public function browse($request_tab = 'actives')
	{
		//Preparations...
		$page = $this->page ;
		$page[1] = ["browse/$request_tab" , trans("manage.tabs.$request_tab") , $request_tab] ;

		//Model...
		$models = User::selector([
			'role' => "admin",
			'criteria' => $request_tab,
		])->orderBy('created_at' , 'desc')->paginate(user()->preference('max_rows_per_page'));;
		$db = new User() ;
		$db->setSelectorPara([
			'role' => "admin",
		]);

		//View...
		return view("manage.admins.browse",compact('page' , 'models' , 'db'));

	}

	public function create()
	{
		$model = new User() ;
		return view("manage.admins.edit",compact('model'));
	}

	public function save(AdminSaveRequest $request)
	{
		//@TODO: Inserting new fields should be more accurate. What if they want to attach the admin role to an already registered user?

		//Preparations...
		$data = $request->toArray() ;

		if($request->id) {
			$user = User::find($request->id) ;
			if(!$user or !$user->as('admin')->canEdit())
				return $this->jsonFeedback(trans('validation.http.Error403'));
		}
		else {
			$data['password'] = Hash::make($request->mobile);
			$data['password_force_change'] = 1 ;
		}

		//Save...
		$saved = User::store($data);

		//Attach admin role...
		if($saved and !$request->id) {
			$user = User::find($saved) ;
			$user->attachRoles('admin');
		}

		//Feedback...
		return $this->jsonAjaxSaveFeedback($saved , [
				'success_callback' => "rowUpdate('tblAdmins','$request->id')",
		]);


	}

	public function delete(Request $request)
	{
		$user = User::find($request->id) ;
		if(!$user or !$user->as('admin')->canDelete())
			return $this->jsonFeedback(trans('validation.http.Error403'));

		$user->disableRole('admin');
		return $this->jsonAjaxSaveFeedback(true , [
				'success_callback' => "rowHide('tblAdmins','$request->id')",
		]);

	}
	public function undelete(Request $request)
	{
		$user = User::find($request->id) ;
		$user->enableRole('admin');
		return $this->jsonAjaxSaveFeedback(true , [
				'success_callback' => "rowHide('tblAdmins','$request->id')",
		]);

	}

	public function password(UserPasswordChangeRequest $request)
	{
		//Preparations....
		$model = User::find($request->id);
		if(!$model)
			return $this->jsonFeedback(trans('validation.http.Error410'));
		if(!$model->as('admin')->canEdit())
			return $this->jsonFeedback(trans('validation.http.Error403'));

		//Save...
		$model->password = Hash::make($request->password);
		$model->password_force_change = 1 ;
		$is_saved = $model->save();

		if($is_saved and $request->sms_notify)
			;//@TODO: Call the event
		//Event::fire(new VolunteerPasswordManualReset($model , $request->password));

		return $this->jsonAjaxSaveFeedback($is_saved);

	}


}
