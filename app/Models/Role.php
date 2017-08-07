<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;


class Role extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static $reserved_slugs        = 'root,super,user,all,dev,developer,admin';
	public static $meta_fields           = ['icon', 'fields', 'status_rule', 'locale_titles'];
	public static $available_field_types = ['text', 'textarea', 'date', 'boolean', 'photo', 'file'];
	public static $support_role_prefix   = 'support';
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
	public function getIsSupportAttribute()
	{
		if(str_contains($this->slug , self::$support_role_prefix.'-')) {
			return true ;
		}
		else {
			return false ;
		}
	}


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

	public function statusRule($key, $in_full = false)
	{
		if(!$this->has_status_rules) {
			return $key;
		}
		if(is_numeric($key)) {
			$this->spreadMeta();
			if(isset($this->status_rule[ $key ])) {
				$string = $this->status_rule[ $key ];
			}
			else {
				$string = "!";
			}

			if($in_full) {
				return Lang::has("forms.status_text.$string") ? trans("forms.status_text.$string") : $string;
			}
			else {
				return $string;
			}
		}
		else {
			return $key;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheRegenerate()
	{
		Cache::forget("admin_roles");
		Cache::forget("support_roles");
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

	public function getStatusRuleArrayAttribute()
	{
		$array = $this->spreadMeta()->status_rule;
		if(is_array($array)) {
			return $array;
		}
		else {
			return [];
		}
	}

	public function getHasStatusRulesAttribute()
	{
		return boolval(count($this->status_rule_array));
	}


	public static function checkManagePermission($role_slug, $criteria)
	{
		if($role_slug == 'all') {
			$role_slug = 'all'; //@TODO: Check in operation
		}
		elseif($role_slug == 'auto') {
			return true;
		}
		elseif($role_slug == 'admin') {
			return true; //just like 'auto'
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

	public static function adminRoles($additive = null)
	{
		$admin_roles = Cache::remember("admin_roles-$additive", 100, function () use ($additive) {
			$roles = self::where('is_admin', true)->get();
			$array = [];
			foreach($roles as $role) {
				$array[] = $role->slug . $additive;
			}

			return $array;
		});

		return $admin_roles;
	}

	public static function supportRoles()
	{
		$support_roles = Cache::remember("support_roles" , 100 , function() {
			$roles = self::where('slug' , 'like' , self::$support_role_prefix . '-%')->orderBy('title')->get() ;
			return $roles ;
		});

		return $support_roles ;
	}

	public function browseTabs()
	{
		/*-----------------------------------------------
		| When all roles are being browsed ...
		*/
		if($this->slug == 'all' or $this->slug == 'admin') {
			return [
				["all", trans('people.criteria.all')],
				['bin', trans('manage.tabs.bin'), '0'],
				['search', trans('forms.button.search')],
			];
		}

		/*-----------------------------------------------
		| When a particular valid role is being browsed ...
		*/
		$array[] = ['all', trans('people.criteria.all')];

		foreach($this->status_rule_array as $key => $string) {
			$array[] = [$key, trans("people.criteria.$string")];
		}
		$array[] = ['bin', trans('people.criteria.banned'), null, user()->as('admin')->can("users-$this->slug.bin")];
		$array[] = ['search', trans('forms.button.search')];

		return $array;
	}

	public function statusCombo($include_delete_options = false)
	{
		$array = [];
		foreach($this->status_rule_array as $key => $item) {
			$array[] = [$key, Lang::has("people.criteria.$item") ? trans("people.criteria.$item") : $item];
		}
		if(!count($array)) {
			$array[] = [1, trans("people.criteria.active")];
		}
		if($include_delete_options) {
			$array[] = ['ban', trans("forms.status_text.blocked")];
			$array[] = ['detach', trans("people.form.detach_this_role")];
		}

		return $array;
	}


}
