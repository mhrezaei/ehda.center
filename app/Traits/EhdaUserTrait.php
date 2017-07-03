<?php
namespace App\Traits;

use App\Models\Domain;
use App\Models\Role;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\jDate;


trait EhdaUserTrait
{

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

	public function getBirthDateOnCardAttribute()
	{
		if($this->birth_date and $this->birth_date != '0000-00-00') {
			return pd(jDate::forge($this->birth_date)->format('Y/m/d'));
		}
		else {
			return '-';
		}

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


}