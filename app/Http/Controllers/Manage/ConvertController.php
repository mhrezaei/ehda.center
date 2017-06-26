<?php

namespace App\Http\Controllers\Manage;

use App\Models\Domain;
use App\Models\Role;
use App\Models\User;
use App\Models\UsersOld;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class ConvertController extends Controller
{
	public function index()
	{
		//$this->createTaha() ;
		//$this->reset() ;
		return $this->users();
		//return $this->createRoles() ;
	}

	public function createTaha()
	{
		DB::table('users')->insert([
			[
				'code_melli' => "0074715623",
				'email' => "chieftaha@gmail.com",
				'name_first' => "طاها",
				'name_last' => "کامکار",
				'password' => bcrypt('11111111'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			]]);
		return ;

	}

	public function reset()
	{
		User::where('id','>','0')->forceDelete() ;
	}

	public function createRoles()
	{
		$domains = Domain::all() ;
		foreach($domains as $domain) {
			Role::create([
				'slug' => 'volunteer-' . $domain->slug ,
			     'title' => "سفیر " . $domain->title ,
			     'plural_title' => "سفیران " . $domain->title ,
			     'modules' => "{\"posts\":[\"create\",\"edit\",\"publish\",\"report\",\"delete\",\"bin\"],\"users\":[\"browse\",\"search\",\"create\",\"edit\",\"publish\",\"report\",\"delete\",\"bin\",\"settings\",\"permit\"],\"cards\":[\"browse\",\"search\",\"create\",\"edit\",\"report\",\"delete\",\"bin\"],\"volunteers\":[\"browse\",\"search\",\"create\",\"edit\",\"report\",\"delete\",\"bin\",\"settings\",\"permit\"],\"comments\":[\"edit\",\"process\",\"publish\",\"report\",\"delete\",\"bin\"]}" ,
			     'is_admin' => '1' ,
			     'meta' => "{\"icon\":\"\",\"status_rule\":{\"1\":\"under_examination\",\"2\":\"waiting_for_data_completion\",\"3\":\"pending\",\"8\":\"active\"},\"fields\":\"\"}" ,
			]) ;
		}
	}

	public function users()
	{
		$olds    = UsersOld::where('convert', '0')->take(50)->get();
		$last_user_id = 0 ;
		$counter = 0;

		foreach($olds as $old) {
			$data = $old->toArray();

			/*-----------------------------------------------
			| Unset Old Fields ...
			*/
			unset($data['volunteer_status']);
			unset($data['card_status']);
			unset($data['tel_mobile']);
			unset($data['home_postal_code']);
			unset($data['work_postal_code']);
			unset($data['remember_token']);
			unset($data['organs']);
			unset($data['familization']);
			unset($data['settings']);
			unset($data['domains']);
			unset($data['roles']);
			unset($data['card_print_status']);
			unset($data['event_id']);
			unset($data['convert']) ;



			/*-----------------------------------------------
			| Set Simple Things ...
			*/
			$data['mobile']          = $old->tel_mobile;
			$data['home_postal']     = $old->home_postal_code;
			$data['work_postal']     = $old->work_postal_code;
			$data['familiarization'] = $old->familization;
			$data['from_event_id']   = $old->event_id;

			$data['exam_result'] = $old->exam_result + 0 ;
			$data['from_event_id'] = $old->event_id + 0 ;
			$data['gender'] = $old->gender + 0 ;
			$data['marital'] = $old->marital + 0 ;
			$data['deleted_by'] = $old->deleted_by + 0 ;
			$data['meta'] = '' ;

			if(!$old->birth_date or $old->birth_date == '0000-00-00' or intval($old->birth_date) > 2017) {
				$data['birth_date'] = Carbon::createFromDate(1900,1,1)->toDateString()  ;
			}

			$user = User::create($data);
			$old->update( [
				'convert' => "1" ,
			]) ;
			$counter++;

			/*-----------------------------------------------
			| Role Attachment ...
			*/
			if($old->card_status>0) {
				$user->attachRole('card-holder') ;
			}
			if($old->volunteer_status>0) {
				if($old->domain) {
					$user->attachRole('volunteer-'.$old->domain  , '' , $old->volunteer_status) ;
				}
				else {
					$user->attachRole('manager' , '' , $old->volunteer_status) ;
				}
			}

			$last_user_id = $user->id ;
			$last_user = $user ;
		}

		ss("Counter: $counter");
		ss("last created id: " . $last_user_id);
		ss($last_user->birth_date);
		return ;
		echo "<script>location.reload();</script>" ;
	}
}
