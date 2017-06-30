<?php
namespace App\Traits;

use App\Models\Domain;
use App\Models\Role;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


trait EhdaUsersTrait
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


}