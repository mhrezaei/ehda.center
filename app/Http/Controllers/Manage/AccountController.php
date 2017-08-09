<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\AccountProfileRequest;
use App\Http\Requests\Manage\ChangeSelfPasswordRequest;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\User;
use App\Traits\ManageControllerTrait;


class AccountController extends Controller
{
	use ManageControllerTrait;

	public function index($request_tab = 'password')
	{
		/*-----------------------------------------------
		| Page ...
		*/
		$page[0] = ['account', trans('settings.account.title')];

		/*-----------------------------------------------
		| Request Tab ...
		*/
		switch ($request_tab) {
			case 'password' :
				$page[1] = ['password', trans('people.commands.change_password')];
				break;

			case 'profile' :
				$page[1]                      = ['profile', trans("people.commands.profile")];
				$model                        = user();
				$states                       = State::combo();
				break;

			case 'card' :
				$page[1]                      = ['card', trans("ehda.donation_card")];
				break;

			default:
				return view('errors.404');
		}

		/*-----------------------------------------------
		| View ...
		*/

		return view("manage.account.$request_tab", compact('page', 'model', 'states'));

	}

	public function action($action)
	{
		return view("manage.account.$action");
	}

	public function saveCard(Request $request)
	{
		/*-----------------------------------------------
		| Validation ...
		*/
		if(user()->is_a('card-holder')) {
			return $this->jsonFeedback(trans("ehda.cards.you_already_have"));
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$ok = user()->attachRole('card-holder') ;
		if($ok) {
			$ok = user()->update([
				'card_registered_at' => Carbon::now()->toDateTimeString(),
				'card_no'            => user()->generateCardNo(),
			]);
		}

		/*-----------------------------------------------
		| Feedback ...
		*/
		return $this->jsonAjaxSaveFeedback( $ok , [
				'success_refresh' => 1,
		]);


	}

	public function savePassword(ChangeSelfPasswordRequest $request)
	{
		$session_key = 'password_attempts';
		$check       = Hash::check($request->current_password, user()->password);


		if(!$check) {
			$session_value = $request->session()->get($session_key, 0);
			$request->session()->put($session_key, $session_value + 1);
			if($session_value > 3) {
				$request->session()->flush();
				$ok = 0;
			}
			else {
				return $this->jsonFeedback(trans('forms.feed.wrong_current_password'));
			}
		}
		else {
			$request->session()->forget($session_key);
			user()->password = bcrypt($request->new_password);
			$ok              = user()->update();
		}

		return $this->jsonAjaxSaveFeedback($ok, [
			'success_redirect' => url('manage'),
			'danger_refresh'   => true,
		]);


	}

	public function saveProfile(AccountProfileRequest $request)
	{
		$model = user();
		$model->updateMeta([
			'edit_reject_notice' => null,
		], true);

		/*
		|--------------------------------------------------------------------------
		| In case of reverting back
		|--------------------------------------------------------------------------
		|
		*/
		if($request->_submit == 'revert') {
			$ok = $model->update([
				'unverified_changes' => null,
				'unverified_flag'    => 0,
			]);

			return $this->jsonAjaxSaveFeedback($ok, [
				'success_message' => trans('settings.account.profile_revert_note'),
				'success_refresh' => "1",
			]);
		}

		/*
		|--------------------------------------------------------------------------
		| Purification
		|--------------------------------------------------------------------------
		|
		*/
		$raw_data = $request->toArray();

		$home = State::find($request->home_city);
		if($home and $home->id) {
			$raw_data['home_province'] = $home->parent_id;
		}
		else {
			$raw_data['home_province'] = 0;
		}

		$work = State::find($request->work_city);
		if($work and $work->id) {
			$raw_data['work_province'] = $work->parent_id;
		}
		else {
			$raw_data['work_province'] = 0;
		}

		$raw_data['activities'] = Activity::requestToString($raw_data) ;


		/*
		|--------------------------------------------------------------------------
		| Save an approved profile
		|--------------------------------------------------------------------------
		| The profile will be intact and the changes will be saved in the `unverified_changes` field
		*/
		if($model->min(8)->is_admin()) {
			$new_data = [];
			foreach($raw_data as $field => $value) {
				if($field[0] != '_' and $model->$field != $value) {
					$new_data[ $field ] = $value;
				}
			}
			if(sizeof($new_data)) {
				$model->unverified_changes = json_encode($new_data);
				$model->unverified_flag    = 1;
			}
			else {
				$model->unverified_changes = null;
				$model->unverified_flag    = 0;
			}
			$ok = $model->save();

			return $this->jsonAjaxSaveFeedback($ok, [
				'success_message' => trans('settings.account.profile_save_note'),
				'success_refresh' => 1,
			]);

		}


		/*
		|--------------------------------------------------------------------------
		| Save a pending profile
		|--------------------------------------------------------------------------
		| Changes will be saved directly into the table
		*/
		else {
			$data             = $raw_data;
			$data['id']       = $model->id;
			$complete_profile = true;

			foreach(User::$volunteers_mandatory_fields as $field) {
				if(!in_array($field, ['code_melli']) and !$data[ $field ]) {
					$complete_profile = false;

					return $this->jsonFeedback($field);
				}
			}

			$ok = User::store($data);

			if($complete_profile) { //@TODO: To be tested for accurate result!
				foreach(user()->max(7)->rolesArray() as $role_slug) {
					user()->as($role_slug)->setStatus(3) ;
				}
			}
			else {
				foreach(user()->max(7)->rolesArray() as $role_slug) {
					user()->as($role_slug)->setStatus(2) ;
				}
			}

			return $this->jsonAjaxSaveFeedback($ok, [
				'success_message' => trans('forms.feed.done'),
				'success_refresh' => 0,
			]);

		}

	}
}