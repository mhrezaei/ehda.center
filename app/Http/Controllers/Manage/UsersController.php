<?php

namespace App\Http\Controllers\Manage;

use App\Http\Requests\Manage\MessageSendRequest;
use App\Http\Requests\Manage\SearchRequest;
use App\Http\Requests\Manage\UserPasswordChangeRequest;
use App\Http\Requests\Manage\UserSaveRequest;
use App\Models\Posttype;
use App\Models\Role;
use App\Models\User;
use App\Providers\SmsServiceProvider;
use App\Traits\ManageControllerTrait;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
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

	public function update($model_id, $request_role)
	{
		$model  = User::withTrashed()->find($model_id);
		$handle = 'selector';

		//Run...
		if(!$model) {
			return view('errors.m410');
		}
		else {
			$model->spreadMeta();

			return view($this->view_folder . '.browse-row', compact('model', 'handle', 'request_role'));
		}
	}

	protected function browseSwitches($request_role, $switches = [])
	{
		$switches = array_normalize($switches, [
			'grid_row'          => "browse-row",
			'grid_array'        => [
				trans('validation.attributes.name_first'),
				[trans('people.user_role'), 'NO', $request_role == 'all'],
				[trans('cart.purchases'), 'NO', $request_role == 'customer'],
				trans('forms.button.action'),
			],
			'url'               => "users/browse/$request_role",
			'search_panel_view' => "search",
			'mass_actions'      => [
				['mobile', trans('people.commands.send_sms'), "modal:manage/users/act/0/sms/$request_role", user()->as('admin')->can("users-$request_role.send")],
			],
			'more_mass_actions' => [],
			'toolbar_buttons'   => [],
			'browse_tabs'       => 'auto',
			'free_toolbar_view' => "NO",
		]);

		$switches['mass_actions'] = array_merge($switches['mass_actions'], $switches['more_mass_actions']);

		return $switches;
	}

	public function search($request_role, SearchRequest $request, $switches = [])
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Role::checkManagePermission($request_role, 'search')) {
			return view('errors.403');
		}


		/*-----------------------------------------------
		| Switches ...
		*/
		$switches = $this->browseSwitches($request_role, $switches);


		/*-----------------------------------------------
		| Revealing the Role...
		*/
		if($request_role == 'admin') {
			$role = Role::where('is_admin', 1)->first();
		}
		elseif($request_role != 'all' and $request_role != 'auto') {
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
		$page[0] = [
			$switches['url'],
			$role->plural_title,
			$switches['url'],
		];
		$page[1] = [
			'search',
			trans('forms.button.search_for') . " $request->keyword ",
			$switches['url'] . "/search",
		];

		//$page = [
		//	'0' => ["users/browse/$request_role", $role->plural_title, "users/browse/$request_role"],
		//	'1' => ['search', trans('forms.button.search_for') . " $request->keyword ", "users/search/$request_role"],
		//];

		/*-----------------------------------------------
		| Only Panel ...
		*/
		if(!isset($request->id) and strlen($request->keyword) < 3) {
			$db         = $this->Model;
			$page[1][1] = trans('forms.button.search');

			return view($this->view_folder . "." . $switches['search_panel_view'], compact('page', 'models', 'db', 'request_role', 'role', 'switches'));
		}


		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'role'     => $request_role,
			'criteria' => 'all',
		];

		if(isset($request->keyword)) {
			$selector_switches['search'] = $keyword = $request->keyword;
		}
		if(isset($request->id)) {
			$selector_switches['id'] = $request->id;
			//$page[1]                 = ['search', trans('forms.button.search_for') . " " . trans('people.particular_user'), "users/search/$request_role"];
			$page[1] = ['search', trans('forms.button.search_for') . " " . trans('people.particular_user'), $switches['url'] . "/search"];

		}

		$models = User::selector($selector_switches)->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
		$db     = $this->Model;

		/*-----------------------------------------------
		| Views ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'request_role', 'role', 'keyword', 'switches'));

	}

	public function browse($request_role, $request_tab = 'all', $switches = [])
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Role::checkManagePermission($request_role, $request_tab)) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Switches ...
		*/
		$switches = $this->browseSwitches($request_role, $switches);

		/*-----------------------------------------------
		| Revealing the Role...
		*/
		if($request_role == 'all') {
			$role               = new Role();
			$role->slug         = 'all';
			$role->plural_title = trans("people.commands.all_users");
		}
		elseif($request_role == 'admin') {
			$role               = new Role();
			$role->slug         = 'admin';
			$role->plural_title = trans("ehda.volunteers.plural");
		}
		else {
			$role = Role::findBySlug($request_role);
			if(!$role->exists) {
				return view('errors.404');
			}
		}


		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page[0] = [
			$switches['url'],
			$role->plural_title,
			$switches['url'],
		];
		$page[1] = [
			$request_tab,
			trans("people.criteria." . $role->statusRule($request_tab)),
			$switches['url'] . "/$request_tab",
		];

		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'roleString' => "$request_role.$request_tab",
			//'role'   => $request_role,
			'status'     => $request_tab,
		];

		$models = User::selector($selector_switches)->orderBy('created_at', 'desc')->simplePaginate(20 /*user()->preference('max_rows_per_page')*/);
		$db     = $this->Model;

		/*-----------------------------------------------
		| Views ...
		*/

		return view($this->view_folder . ".browse", compact('page', 'models', 'db', 'request_role', 'role', 'switches'));

	}

	public function create($role_to_be_attached = null)
	{
		$model                      = new User();
		$model->role_to_be_attached = $role_to_be_attached;

		return view("manage.users.edit", compact('model'));
	}

	public function permitsForm($model, $role_slug)
	{
		$request_role = Role::findBySlug($role_slug);
		if(!$request_role) {
			return view('errors.m410');
		}
		$modules = $request_role->modules_array;

		$posttypes         = Posttype::all();
		$comment_posttypes = Posttype::whereIn('slug', Posttype::withFeature('comment'))->get();
		$roles             = Role::all();

		return view("manage.users.permits2", compact('model', 'request_role', 'roles', 'posttypes', 'modules' , 'comment_posttypes'));

	}

	public function savePermits(Request $request)
	{
		/*-----------------------------------------------
		| Validation ...
		*/
		$model = User::find($request->id);
		if(!$model or $model->withDisabled()->is_not_a($request->role_slug)) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if(!$model->as($request->role_slug)->canPermit()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Self Privileges ... (Only for admin roles)
		*/
		if(in_array($request->role_slug, Role::adminRoles())) {
			$permits = array_filter(explode(' ', $request->permissions));
			foreach($permits as $permit) {
				if(user()->as_any()->cannot($permit) and $model->as_any()->can()) {
					return $this->jsonFeedback($permit);

				}
			}
			if(!user()->as_any()->can_all($permits)) {
				//return $this->jsonFeedback($request->permissions);
				//
				//return $this->jsonFeedback(trans('validation.http.Error403'));
			}
		}

		/*-----------------------------------------------
		| Save Status ...
		*/
		$ok = $this->saveRoleStatus($model, $request->role_slug, $request->status);

		/*-----------------------------------------------
		| Save Support Roles ...
		*/
		foreach(Role::supportRoles() as $support_role) {
			$model_value = $model->is_a($support_role->slug);
			$input_value = $request->toArray()[ $support_role->slug ];

			if($model_value != $input_value) {
				if($input_value) {
					$model->attachRole($support_role->slug);
				}
				else {
					$model->detachRole($support_role->slug);
				}
			}

		}


		/*-----------------------------------------------
		| Save and Return  ...
		*/
		$model->as($request->role_slug)->setPermission($request->permissions);

		return $this->jsonAjaxSaveFeedback(true, [
			'success_callback' => "rowUpdate('tblUsers','$model->id')",
		]);

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

	public function refreshRoleRowForm($model, $role_id)
	{
		$role = Role::find($role_id);

		return view("manage.users.roles-one", compact('model', 'role'));

	}

	public function saveRole($user_id, $role_slug, $new_status)
	{
		/*-----------------------------------------------
		| Model and Permission ...
		*/
		$user = User::find($user_id);
		if(!$user) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if(!$user->canPermit()) {
			return $this->jsonFeedback(trans('validation.http.Error503'));
		}

		/*-----------------------------------------------
		| Save ...
		*/
		$ok = $this->saveRoleStatus($user, $role_slug, $new_status);
		//if($new_status == 'detach') {
		//	$user->detachRole($role_slug);
		//}
		//elseif($new_status == 'ban') {
		//	$user->disableRole($role_slug);
		//}
		//else {
		//	if($user->withDisabled()->hasnotRole($role_slug)) {
		//		$user->attachRole($role_slug, $new_status);
		//	}
		//	elseif($user->as($role_slug)->disabled()) {
		//		$user->enableRole($role_slug);
		//	}
		//
		//	$user->as($role_slug)->setStatus($new_status) ;
		//}

		/*-----------------------------------------------
		| Feedback...
		*/

		return $this->jsonAjaxSaveFeedback(true); //<~~ Row is automatically refreshed upon receiving of the done feedback!

	}

	private function saveRoleStatus($user, $role_slug, $new_status)
	{
		if($new_status == 'detach') {
			$ok = $user->detachRole($role_slug);
		}
		elseif($new_status == 'ban') {
			$ok = $user->disableRole($role_slug);
		}
		else {
			if($user->withDisabled()->hasnotRole($role_slug)) {
				$ok = $user->attachRole($role_slug, $new_status);
			}
			elseif($user->as($role_slug)->disabled()) {
				$ok = $user->enableRole($role_slug);
			}

			$ok = $user->as($role_slug)->setStatus($new_status);
		}

		return $ok;

	}

	public function _saveRole(Request $request)
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

	public function saveNewRole(Request $request)
	{
		/*-----------------------------------------------
		| Models ...
		*/
		$user = User::find($request->id);
		$role = Role::findBySlug($request->role_slug);

		if(!$user or !$user->id or !$role or !$role->id) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Security ...
		*/
		if(user()->as('admin')->cannot("users-$role->slug.create")) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Other Validation ...
		*/
		if($user->withDisabled()->is_a($role->slug)) {
			return $this->jsonFeedback(trans("people.form.already_has_role"));
		}
		if($role->statusRule($request->status) == '!') {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}


		/*-----------------------------------------------
		| Process ...
		*/
		$ok = $user->attachRole($role->slug, $request->status);

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_refresh' => true,
		]);

	}


	public function saveStatus(Request $request)
	{
		/*-----------------------------------------------
		| Role Model ...
		*/
		$role = Role::findByHashid($request->role_id);
		if(!$role or !$role->id) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if($role->statusRule($request->new_status) == '!') {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| User ...
		*/
		$user = User::find($request->id);
		if(!$user or !$user->id) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if(!$user->canEdit()) {
			return $this->jsonFeedback(trans('validation.http.Error403'));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$done = $user->as($role->slug)->setStatus($request->new_status);

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($done, [
			'success_callback' => "rowUpdate('tblUsers','$user->id')",
		]);


	}

	public function saveStatusMass(Request $request)
	{
		/*-----------------------------------------------
		| Role Model ...
		*/
		$role = Role::findByHashid($request->role_id);
		if(!$role) {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}
		if($role->statusRule($request->new_status) == '!') {
			return $this->jsonFeedback(trans('validation.http.Error410'));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$users = User::whereIn('id', explode(',', $request->ids))->get();
		$count = 0;
		foreach($users as $user) {
			if($user->canEdit()) {
				$count += $user->as($role->slug)->setStatus($request->new_status);
			}
		}

		/*-----------------------------------------------
		| Feedback ...
		*/

		return $this->jsonAjaxSaveFeedback($count, [
			'success_message' => trans('forms.feed.mass_done', [
				'count' => pd($count),
			]),
			'success_refresh' => true,
			'danger_message'  => trans('forms.feed.error'),
		]);


	}

	public function smsMass(MessageSendRequest $request)
	{

		//Collecting Numbers...
		$id_array = explode(',', $request->ids);
		$numbers  = [];

		foreach($id_array as $id) {
			$user = User::find($id);
			if($user and $user->mobile) {
				$numbers[] = $user->mobile;
			}
		}

		//Sending....
		$ok = SmsServiceProvider::send($numbers, $request->message);
		if($ok) {
			$count = count($numbers);
		}
		else {
			$count = 0;
		}

		//Feedback...
		return $this->jsonAjaxSaveFeedback($count, [
			'success_message' => trans('people.form.message_sent_to', [
				'count' => pd($count),
			]),
			'danger_message'  => trans('people.form.message_not_sent_to_anybody'),
		]);

	}
}
