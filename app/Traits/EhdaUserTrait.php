<?php
namespace App\Traits;

use App\Models\Domain;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\jDate;


trait EhdaUserTrait
{

	public static    $volunteers_mandatory_fields = ['code_melli', 'name_first', 'name_last', 'name_father', 'birth_date', 'birth_city', 'gender', 'home_province', 'home_city', 'email', 'mobile', 'tel_emergency'];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/
	public function event()
	{
		return $this->belongsTo('App\Models\Post', 'from_event_id');
	}

	public function getEventAttribute()
	{
		if($this->from_event_id) {
			$event = Cache::remember("post-$this->from_event_id", 10, function ()  {
				return $this->event()->first();
			});
		}

		if(isset($event) and $event and $event->exists) {
			return $event ;
		}
		else {
			return false ;
		}
	}



	/*
	|--------------------------------------------------------------------------
	| Assessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getHomeCityNameAttribute()
	{
		$state = State::find($this->home_city);
		if($state) {
			return $state->full_name;
		}
		else {
			return '-';
		}

	}

	public function getBirthCityNameAttribute()
	{
		$state = State::find($this->birth_city);
		if($state) {
			return $state->full_name;
		}
		else {
			return '-';
		}

	}


	public function getEduCityNameAttribute()
	{
		$state = State::find($this->edu_city);
		if($state) {
			return $state->full_name;
		}
		else {
			return '-';
		}

	}


	public function getGenderIconAttribute()
	{
		switch($this->gender) {
			case 1 :
				return 'male' ;
			case 2 :
				return 'female' ;
			case 3 :
				return 'transgender' ;
			default :
				return 'question-circle' ;
		}
	}



	public function getFromDomainNameAttribute()
	{
		if($this->from_domain) {
			$domain = Domain::selectBySlug($this->from_domain);
			if($domain) {
				return $domain->title;
			}
		}

		return false;
	}

	public function getRegisterDateOnCardEnAttribute()
	{
		if($this->card_registered_at and $this->card_registered_at != '0000-00-00') {
			return jDate::forge($this->card_registered_at)->format('Y/m/d');
		}
		else {
			return '-';
		}

	}

	public function getRegisterDateOnCardAttribute()
	{
		return pd($this->register_date_on_card_en) ;
	}



	public function getBirthDateOnCardEnAttribute()
	{
		if($this->birth_date and $this->birth_date != '0000-00-00') {
			return jDate::forge($this->birth_date)->format('Y/m/d');
		}
		else {
			return '-';
		}

	}

	public function getBirthDateOnCardAttribute()
	{
		return pd($this->birth_date_on_card_en) ;
	}

	public function getOccupationAttribute()
	{
		$return = null;

		if($this->job) {
			$return .= $this->job . " . ";
		}

		$return .= $this->edu_level_short ;

		if($this->edu_field) {
			$return .= " . " . $this->edu_field;
		}

		return $return;

	}



	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function cards($type = 'mini', $mode = 'show')
	{
		$card_type = ['mini', 'single', 'social', 'full'];
		$card_mode = ['show', 'download', 'print'];

		if (!in_array($type, $card_type))
		{
			$type = 'mini';
		}

		if (!in_array($mode, $card_mode))
		{
			$mode = 'show';
		}

		return url('/card/show_card/' . $type . '/' . hashid_encrypt($this->id, 'ids') . '/' . $mode);

	}

	public function generateCardNo()
	{
		return $this->id + 5000 ;
	}

	/**
	 * @return array of all the bot-users
	 *
	 */
	public static function telegramBots()
	{
		$role = Role::findBySlug('telegram-bot') ;
		return $role->users()->get()->pluck('id') ;
	}

	//public static function generateCardNo()
	//{
	//	$record = self::orderBy('card_no', 'desc')->first();
	//	if(!$record) {
	//		return 1500;
	//	}
	//	else {
	//		return $record->card_no + 1;
	//	}
	//}

}