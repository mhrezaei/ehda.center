<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;


class Role extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static $reserved_slugs        = 'root,super,user,all,dev,developer,manager,manage';
	public static $meta_fields           = ['icon', 'fields', 'status_rule', 'locale_titles'];
	public static $available_field_types = ['text', 'textarea', 'date', 'boolean', 'photo', 'file'];
	protected     $guarded               = ['id'];

	protected $casts = [
		'is_manager' => "boolean",
		'meta'       => "array",
	];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/

	public function users()
	{
		return $this->belongsToMany('App\Models\User')->withTimestamps();
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getStatusAttribute()
	{
		if($this->trashed()) {
			return 'inactive';
		}
		else {
			return 'active';
		}
	}

	public function getMenuIconAttribute()
	{
		if($this->spreadMeta()->icon) {
			return $this->icon;
		}
		else {
			return 'user';
		}
	}

	public function getHasModulesAttribute()
	{
		return isJson($this->modules);
	}

	public function getModulesArrayAttribute()
	{
		if(!$this->has_modules) {
			return [];
		}
		else {
			return json_decode($this->modules, true);
		}
	}

	public function getModulesForInputAttribute()
	{
		$string = null;

		foreach($this->modules_array as $module => $permits) {
			if($string) {
				$string .= "\n";
			}
			$string .= $module . ": ";
			foreach($permits as $permit) {
				$string .= " $permit , ";
			}
		}

		return $string;
	}

	public function getStatusRuleForInputAttribute()
	{
		$string = null;
		if(!is_array($this->status_rule)) {
			return null;
		}

		foreach($this->status_rule as $status => $description) {
			if($string) {
				$string .= "\n";
			}
			$string .= "$status : $description ";
		}

		return $string;
	}

	public function getFieldsArrayAttribute()
	{
		$string = str_replace(' ', null, $this->spreadMeta()->fields);
		$result = [];

		$array = explode(',', $string);
		foreach($array as $item) {
			if(str_contains($item, '*')) {
				$required = true;
				$item     = str_replace('*', null, $item);
			}
			else {
				$required = false;
			}

			$field = explode(':', $item);
			if(!$field[0]) {
				continue;
			}

			array_push($result, [
				'name'     => $field[0],
				'type'     => isset($field[1]) ? $field[1] : 'text',
				'required' => $required,
			]);
		}

		return $result;
	}

	public function getUsersBrowseLinkAttribute()
	{
		return 'manage/users/browse/' . $this->slug;
	}

	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function titleIn($locale = 'fa')
	{
		if($locale == 'fa') {
			return $this->title;
		}
		else {
			return $this->meta("locale_titles")["title-$locale"];
		}
	}

	public function pluralTitleIn($locale)
	{
		if($locale == 'fa') {
			return $this->plural_title;
		}
		else {
			return $this->meta("locale_titles")["plural_title-$locale"];
		}
	}

	public function isDefault()
	{
		$default_role = setting('default_role')->noCache()->gain();

		return boolval($default_role == $this->slug);
	}

	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheRegenerate()
	{
		Cache::forget("managing_roles");
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public static function getModulesJson($input)
	{
		$array_layer_1 = array_filter(preg_split("/\\r\\n|\\r|\\n/", $input));
		$array_final   = [];

		foreach($array_layer_1 as $item) {
			$array_layer_2                    = array_filter(explode(':', str_replace(' ', null, $item)));
			$array_final[ $array_layer_2[0] ] = array_filter(explode(',', str_replace(' ', null, $array_layer_2[1])));
		}

		$json = json_encode($array_final);

		return $json;
	}

	public static function getStatusRuleArray($input)
	{
		$array_layer_1 = array_filter(preg_split("/\\r\\n|\\r|\\n/", $input));
		$array_final   = [];

		foreach($array_layer_1 as $item) {
			$array_layer_2                    = array_filter(explode(':', str_replace(' ', null, $item)));
			$array_final[ $array_layer_2[0] ] = $array_layer_2[1];
		}

		return $array_final;
	}

	public static function checkManagePermission($role_slug, $criteria)
	{
		if(in_array($role_slug, ['admin', 'all'])) {
			return user()->isSuper();
		}
		switch ($criteria) {
			case 'bin' :
			case 'banned' :
				$permit = 'bin';
				break;

			default:
				$permit = '*';
		}

		return user()->as('admin')->can("users-$role_slug.$permit");

	}

	public static function managingRoles()
	{
		$managing_roles = Cache::remember("managing_roles" , 100 , function() {
			$roles = self::where('is_manager' , true)->get() ;
			$array = [] ;
			foreach($roles as $role) {
				$array[] = $role->slug ;
			}
			return $array ;
		});

		return $managing_roles ;
	}


}
