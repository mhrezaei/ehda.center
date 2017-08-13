<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;


class Printing extends Model
{
	use SoftDeletes, TahaModelTrait;
	protected $guarded = ['id'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function getUserAttribute()
	{
		$user = $this->user()->first() ;
		if(!$user or !$user->id) {
			$user = new User() ;
		}

		return $user ;
	}


	public function event()
	{
		return $this->belongsTo('App\Models\Post');
	}

	public function getEventAttribute()
	{
		if($this->event_id) {
			$event = Cache::remember("post-$this->event_id", 10, function () {
				return $this->event()->first();
			});
		}

		if(isset($event) and $event and $event->exists) {
			return $event;
		}
		else {
			return false;
		}
	}


	public function printing()
	{
		return $this->hasOne('App\Models\Printing');
	}

	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function counter($switches = [], $overriding_criteria = false)
	{
		if($overriding_criteria) {
			$switches['criteria'] = $overriding_criteria;
		}

		return self::selector($switches)->count();
	}


	/**
	 * @param array $switches (accepts 'domain' , 'criteria' , 'user_id' , 'event_id' , 'volunteer_id' )
	 */
	public static function selector($parameters = [])
	{
		$switch = array_normalize($parameters, [
			'user_id'  => false,
			'event_id' => false,
			'domain'   => false,
			'criteria' => false,
		     'created_by' => false ,
		]);

		$table = self::where('id', '>', '0');

		/*-----------------------------------------------
		| Simple Things ...
		*/
		if($switch['user_id']) {
			$table->where('user_id', $switch['user_id']);
		}
		if($switch['event_id'] and $switch['event_id'] > 0) {
			$table->where('event_id', $switch['event_id']);
		}
		if($switch['created_by']) {
			$table->where('created_by', $switch['created_by']);
		}

		/*-----------------------------------------------
		| Domain ...
		*/
		if($switch['domain'] == 'auto') {
			if(user()->is_a('manager')) {
				$switch['domain'] = false;
			}
			else {
				$switch['domain'] = user()->domainsArray('users-card-holder.print'); //@TODO: Vulnerability: What if a user() has access to 'print-excel' but not to 'print-direct'?
			}
		}

		if($switch['domain'] !== false) {
			$switch['domain'] = (array)$switch['domain'];

			$table = self::whereIn('domain' , $switch['domain']);
		}



		/*-----------------------------------------------
		| Criteria ...
		*/
		switch ($switch['criteria']) {
			case 'all' :
				break;

			case 'under_any_action' :
				$table->whereNull('delivered_at');
				break;

			case 'all_with_trashed':
				$table->withTrashed();
				break;

			case 'pending' :
				$table->whereNull('queued_at');
				break;

			case 'under_direct_printing':
				$table->whereNotNull('queued_at')->whereNull('printed_at');
				break;

			case 'under_excel_printing' :
				$table->whereNotNull('printed_at')->whereNull('verified_at');
				break;

			//case 'under_print' : // means direct
			//	$table->whereNotNull('queued_at')->whereNull('printed_at');
			//	break;
			//
			//case 'under_verification' : // means excel
			//	$table->whereNotNull('printed_at')->whereNull('verified_at');
			//	break;
			//
			//case 'under_dispatch' :
			//	$table->whereNotNull('verified_at')->whereNull('dispatched_at');
			//	break;
			//
			//case 'under_delivery' :
			//	$table->whereNotNull('dispatched_at')->whereNull('delivered_at');
			//	break;
			//
			//case 'archive' :
			//	$table->where('delivered_at');
			//	break;
			//
			//case 'bin' :
			//	$table->onlyTrashed();
			//	break;

		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $table;

	}


	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getStatusAttribute()
	{

		if($this->delivered_at) {
			return 'archive';
		}
		if($this->dispatched_at and !$this->delivered_at) {
			return 'under_delivery';
		}
		if($this->verified_at and !$this->dispatched_at) {
			return 'under_dispatch';
		}
		if($this->printed_at and !$this->verified_at) {
			return 'under_verification';
		}
		if($this->queued_at and !$this->printed_at) {
			return 'under_print';
		}
		if(!$this->queued_at) {
			return 'under_print';
		}
		else {
			return 'unknown' ;
		}

	}

	public function getStatusColorAttribute()
	{
		switch ($this->status) {
			case 'pending' :
				return 'danger';

			case 'under_print' :
				return 'warning';

			case 'under_verification' :
				return 'warning';

			case 'under_dispatch' :
				return 'warning';

			case 'under_delivery' :
				return 'primary';

			case 'archive' :
				return 'success';

			default :
				return 'black' ;

		}
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public static function addTo($event, $user)
	{
		/*-----------------------------------------------
		| Delete current rows where a pending request is already in the queue ...
		*/
		self::selector([
			'user_id' => $user->id ,
	          'criteria' => "under_any_action" ,
		])->delete() ;

		/*-----------------------------------------------
		| Add New Row ...
		*/
		return self::store([
			'user_id' => $user->id ,
			'event_id' => $event->id ,
		]);

	}

}
