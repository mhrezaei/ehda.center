<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\UserPasswordChangeRequest;
use App\Http\Requests\Manage\UserSaveRequest;
use App\Models\Posttype;
use App\Models\Role;
use App\Models\User;
use App\Providers\SmsServiceProvider;
use App\Traits\ManageControllerTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
	use ManageControllerTrait;

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

	public function search($request_role, Request $request)
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Role::checkManagePermission($request_role, 'search')) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Revealing the Role...
		*/
		if($request_role != 'all') {
			$role = Role::findBySlug($request_role);
			if(!$role->exists) {
				return view('errors.404');
			}
		}
		else {
			$role               = new Role();
			$role->plural_title = trans('people.commands.all_users');
		}

		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ["users/browse/$request_role", $role->plural_title, "users/browse/$request_role"],
			'1' => ['search', trans('forms.button.search_for') . " $request->keyword ", "users/search/$request_role"],
		];

		/*-----------------------------------------------
		| Only Panel ...
		*/
		if(strlen($request->keyword) < 3) {
			$db         = $this->Model;
			$page[1][1] = trans('forms.button.search');

			return view($this->view_folder . ".search", compact('page', 'models', 'db', 'request_role', 'role'));
		}


		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'role'     => $request_role,
			'criteria' => 'all',
			'search'   => $keyword = $request->keyword,
		];

		$models = User::selector($selector_switches)->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
		$db     = $this->Model;

		/*-----------------------------------------------
		| Views ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'request_role', 'role', 'keyword'));

	}

	public function browse($request_role, $request_tab = 'actives')
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Role::checkManagePermission($request_role, $request_tab)) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Revealing the Role...
		*/
		if($request_role != 'all') {
			$role = Role::findBySlug($request_role);
			if(!$role->exists) {
				return view('errors.404');
			}
		}
		else {
			$role               = new Role();
			$role->plural_title = trans('people.commands.all_users');
		}

		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ["users/browse/$request_role", $role->plural_title, "users/browse/$request_role"],
			'1' => [$request_tab, trans("people.criteria.$request_tab"), "users/browse/$request_role/$request_tab"],
		];

		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'role'     => $request_role,
			'criteria' => $request_tab,
		];

		$models = User::selector($selector_switches)->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
		$db     = $this->Model;

		/*-----------------------------------------------
		| Views ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'request_role', 'role'));

	}

	public function create($role_to_be_attached = null)
	{
		$model                      = new User();
		$model->role_to_be_attached = $role_to_be_attached;

		return view("manage.users.edit", compact('model'));
	}

	public function permitsForm($model, $role_id)
	{
		$request_role = Role::find($role_id);
		if(!$request_role) {
			return view('errors.m410');
		}
		$modules = $request_role->modules_array;

		$posttypes = Posttype::all();
		$roles     = Role::where('slug', '!=', 'admin')->get();

		return view("manage.users.permits", compact('model', 'request_role', 'roles', 'posttypes', 'modules'));

	}

	public function savePermits(Request $request)
	{
		$data = $request->toArray();

		/*-----------------------------------------------
		| Models & Security Check...
		*/
		$model_user = User::find($request->id);
		$model_role = Role::find($request->role_id);
		$as         = $model_role->slug; //just for the easier use

		if(!$model_user or !$model_role or !$model_role) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if($model_user->is_not_a($model_role->slug) or !$model_user->as($model_role->slug)->canPermit()) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Browse available modules and permissions...
		*/
		$modules       = $model_role->modules_array;
		$result_string = "";

		//Users Modules...
		if($permissions = array_pull($modules, 'users')) {
			foreach(Role::where('slug', '!=', 'admin')->get() as $role) {
				foreach($permissions as $permission) {
					$field_name = "role~users-$role->slug~$permission";
					$permit     = "users-$role->slug.$permission";

					if($data[ $field_name ] and user()->as($as)->can($permit)) {
						$result_string .= " $permit ";
					}
				}
			}
		}

		//posts...
		if($permissions = array_pull($modules, 'posts')) {
			foreach(Posttype::all() as $posttype) {
				foreach($permissions as $permission) {
					$field_name = "role~posts-$posttype->slug~$permission";
					$permit     = "posts-$posttype->slug.$permission";

					if($data[ $field_name ] and user()->as($as)->can($permit)) {
						$result_string .= " $permit ";
					}
				}
			}
		}

		//other modules...
		foreach($modules as $module => $permissions) {
			foreach($permissions as $permission) {
				$field_name = "role~$module~$permission";
				$permit     = "$module.$permission";

				if($data[ $field_name ] and user()->as($as)->can($permit)) {
					$result_string .= " $permit ";
				}
			}
		}

		/*-----------------------------------------------
		| Superadmin ...
		*/
		if($as == 'admin' and $data['level'] == 'super' and user()->isSuper()) {
			$result_string .= " users-admin ";
		}

		/*-----------------------------------------------
		| Save and Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($model_user->setPermits($result_string, $model_role->id));


	}

	public function save(UserSaveRequest $request)
	{
		/*-----------------------------------------------
		| Preparations ...
		*/
		$data = $request->toArray();

		if($request->id) {
			$model = User::find($request->id);
			if(!$model or !$model->canEdit()) {
				return $this->jsonFeedback(trans('validation.http.Error403'));
			}
		}
		else {
			$data['password']              = Hash::make($request->mobile);
			$data['password_force_change'] = 1;
		}

		/*-----------------------------------------------
		| Save ...
		*/
		$saved               = User::store($data);
		$role_to_be_attached = $data['_role_to_be_attached'];
		if($saved and !$request->id and $role_to_be_attached and $role_to_be_attached != 'all') {
			$model = User::find($saved);
			$model->attachRoles($data['_role_to_be_attached']);
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($saved, [
			'success_callback' => "rowUpdate('tblUsers','$request->id')",
		]);

	}

	public function savePassword(UserPasswordChangeRequest $request)
	{
		/*-----------------------------------------------
		| Preparations ...
		*/
		$model = User::find($request->id);
		if(!$model) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if(!$model->canEdit()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		} //@TODO: Only superadmins can change password. This is not correct!

		/*-----------------------------------------------
		| Save ...
		*/
		$model->password              = Hash::make($request->password);
		$model->password_force_change = 1;
		$is_saved                     = $model->save();

		if($is_saved and $request->sms_notify) {
			SmsServiceProvider::send($model->mobile, trans('people.form.password_change_sms', [
				'site_title'   => setting()->ask('site_title')->in('fa')->gain(),
				'new_password' => $request->password,
			]));
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($is_saved);


	}

	public function saveRole(Request $request)
	{
		/*-----------------------------------------------
		| Command ...
		*/
		list($command, $role_id) = explode('-', $request->toArray()['_submit']);

		/*-----------------------------------------------
		| Model Reveal and Permission...
		| @TODO: Check Permission: 'create' if attaching role, 'block' if block/unblock, 'delete' if detach
		*/
		$model = User::find($request->id);
		$role  = Role::find($role_id);

		if(!$model or !$role) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if(!user()->isSuper()) { //if(!$model->as($role->slug)->canPermit()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Save ...
		*/
		switch ($command) {
			case 'attach' :
				$saved = $model->attachRole($role->slug);
				break;

			case 'detach' :
				$saved = $model->detachRole($role->slug);
				break;

			case 'unblock':
				$saved = $model->enableRole($role->slug);
				break;

			case 'block':
				$saved = $model->disableRole($role->slug);
				break;

			default:
				$saved = false;
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($saved, [
			'success_callback' => "rowUpdate('tblUsers','$request->id')",
		]);


	}

	public function delete(Request $request)
	{
		$user = User::find($request->id);
		if(!$user or !$user->canDelete()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($user->delete(), [
			'success_callback' => "rowHide('tblUsers','$request->id')",
		]);

	}

	public function undelete(Request $request)
	{
		$user = User::onlyTrashed()->find($request->id);
		if(!$user or !$user->canBin()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($user->restore(), [
			'success_callback' => "rowHide('tblUsers','$request->id')",
		]);

	}

	public function destroy(Request $request)
	{
		$user = User::onlyTrashed()->find($request->id);
		if(!$user or !$user->canBin()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		return $this->jsonAjaxSaveFeedback($user->forceDelete(), [
			'success_callback' => "rowHide('tblUsers','$request->id')",
		]);

	}
}
