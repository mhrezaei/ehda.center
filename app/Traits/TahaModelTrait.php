<?php
namespace App\Traits;


use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait TahaModelTrait
{
	protected $saved_selector_para = [] ;

	/*
	|--------------------------------------------------------------------------
	| Enrichment Methods
	|--------------------------------------------------------------------------
	|
	*/


	public static function counter($parameters, $in_persian = false)
	{
		$return = self::selector($parameters)->count();
		if($in_persian)
			return pd($return);
		else
			return $return ;

	}

	public function counterC($criteria = 'all' , $mood = '')
	{
		$return = $this->counter(array_default(['criteria' => $criteria,] , $this->saved_selector_para));

		switch($mood) {
			case 'badge' :
				return " [".pd($return)."]" ;
			case 'persian' :
				return pd($return);
			default :
				return $return ;
		}
	}

	public function setSelectorPara($parameters = [])
	{
		$this->saved_selector_para = $parameters ;
	}

	public function getIdAttribute($value)
	{
		return intval($value);
	}

	public function className()
	{
		$full_name = self::class ;
		$name_array = explode("\\" , $full_name) ;
		$short_name = $name_array[ sizeof($name_array)-1 ] ;
		return $short_name ;
	}

	public static function tableName()
	{
		$model = new self() ;
		return $model->getTable() ;
	}

	public static function hasColumn($field_name)
	{
		if($field_name == 'deleted_at') {
			return method_exists( new self() , 'withTrashed');
		}
		return Schema::hasColumn(self::tableName(), $field_name);
	}

	public static function none()
	{
		return self::whereNull('id') ;
	}

	public function settingCombo($slug)
	{
		$options = Setting::get($slug) ;
		$result = [] ;

		foreach($options as $option) {
			array_push($result , [$option]);
		}

		return $result ;
	}

	public static function searchRawQuery($keyword, $fields = null)
	{
		if(!$fields) {
			$fields = self::$search_fields ;
		}

		$concat_string = " " ;
		foreach($fields as $field) {
			$concat_string .= " , `$field` " ;
		}

		return " LOCATE('$keyword' , CONCAT_WS(' ' $concat_string)) " ;
 	}

	/*
	|--------------------------------------------------------------------------
	| Meta Enrichment Methods
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * To be used only inside the `store` method.
	 * @param $data
	 * @return array $data
	 */
	public static function storeMeta($data)
	{
		//Bypass...
		if(!self::hasColumn('meta') or !isset(self::$meta_fields))
			return $data ;

		//Current Data...
		if(!isset($data['id']))
			$data['id'] = 0 ;
		$model = self::find($data['id']) ;
		if($model)
			if(is_array($model->meta))
				$meta = $model->meta ;
			else
				$meta = json_decode($model->meta , true) ;
		else
			$meta = [] ;

		//Process...
		foreach($data as $field => $value) {
			if(self::hasColumn($field) or (!in_array($field,self::$meta_fields) and self::$meta_fields[0]!='dynamic'))
				continue ;

			$meta[$field] = $value ;
			unset($data[$field]);
		}
		$data['meta'] = json_encode($meta);
		return $data ;
	}

	/**
	 * Spreads Meta fields into the table columns, all together!
	 * @return $this!
	 */
	public function spreadMeta()
	{
		//Bypass...
		if(!self::hasColumn('meta') or !$this->id)
			return $this ;

		//Retreive...
		if(is_array($this->meta))
			$meta = $this->meta ;
		else
			$meta = json_decode($this->meta , true) ;

		//safety...
		if(!$meta)
			return $this;

		//Process...
		foreach($meta as $field => $value) {
			$this->$field = $value ;
		}

		return $this;

	}

	public function meta($slug=null, $field = 'meta')
	{
		$data = $this->$field ;
		if(!is_array($data))
			$data = json_decode($data , true) ;

		if(!$slug)
			return $data ;
		elseif(isset($data[$slug]))
			return $data[$slug];
		else
			return null ;
	}

	public function updateMeta($array , $update_row = false)
	{
		$meta = $this->meta() ;

		foreach($array as $field => $value) {
			if(in_array($field,self::$meta_fields)) {
				$meta[$field] = $value ;
			}
			if(!$value) {
				unset($meta[$field]);
			}
		}

		$this->meta = json_encode($meta) ;
		if($update_row) {
			$this->save() ;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| General Select Methods
	|--------------------------------------------------------------------------
	|
	*/
	public static function selectBySlug($slug , $field='slug')
	{
		//Deprecated!
		return self::findBySlug($slug , $field) ;

	}

	public static function findBySlug($slug, $field = 'slug')
	{
		if(!$slug) return new self() ;
		$model = self::where($field , $slug)->first() ;

		if($model)
			return $model ;
		else
			return new self() ;

	}

	/*
	|--------------------------------------------------------------------------
	| General Save Methods
	|--------------------------------------------------------------------------
	|
	*/


	public static function store($request , $unset_things = [])
	{
		//Convert to Array...
		if(is_array($request))
			$data = $request ;
		else
			$data = $request->toArray();

		//Unset Unnecessary things...
		$unset_things = array_merge($unset_things , ['key' , 'security']);
		foreach($unset_things as $unset_thing) {
			if(isset($data[$unset_thing]))
				unset($data[$unset_thing]);
		}
		foreach($data as $key => $item) {
			if($key[0] == '_')
				unset($data[$key]);
		}

		//Meta...
		$data = self::storeMeta($data) ;

		//Action...
		if(isset($data['id']) and $data['id'] > 0) {
			if(self::hasColumn('updated_by') and !isset($data['updated_by'])) {
				if( Auth::check())
					$data['updated_by'] = Auth::user()->id ;
				else
					$data['updated_by'] = 0 ;
			}

			$affected = Self::where('id', $data['id']);

			if(self::hasColumn('deleted_at'))
					$affected = $affected->withTrashed();

			$affected = $affected->update($data);
			if($affected) $affected = $data['id'] ;
		}
		else {
			if(self::hasColumn('created_by') and !isset($data['created_by'])) {
				if( Auth::check())
					$data['created_by'] = Auth::user()->id ;
				else
					$data['created_by'] = 0 ;
			}

			$model = Self::create($data);
			if($model)
				$affected = $model->id;
			else
				$affected = 0;
		}

		//feedback...
		return $affected;

	}

	public function unpublish()
	{
		//		$this->published_at = null ;
		if(self::hasColumn('published_by'))
			$this->published_by = null ;
		return $this->save() ;
	}

	public function delete()
	{
		if(self::hasColumn('deleted_at') and !$this->forceDeleting)
			$this->deleted_at = Carbon::now()->toDateTimeString();
		else
			return parent::delete() ;

		if(self::hasColumn('deleted_by'))
			$this->deleted_by = Auth::user()->id ;
		return $this->save();
	}

	public static function bulkDelete($ids , $exception)
	{
		if(!is_array($ids))
			$ids = explode(',',$ids);

		return Self::whereIn('id',$ids)->where('id','<>',$exception)->update([
			'deleted_at' => Carbon::now()->toDateTimeString() ,
			'deleted_by' => Auth::user()->id , //@TODO: What if doesn't have this column  in database
		]);

	}

	public static function bulkPublish($ids)
	{
		if(!is_array($ids))
			$ids = explode(',',$ids);

		return Self::whereIn('id',$ids)->whereNull('deleted_at')->whereNull('published_at')->update([
			'published_at' => Carbon::now()->toDateTimeString() ,
			'published_by' => Auth::user()->id , //@TODO: What if doesn't have this column  in database
		]);

	}

	public static function bulkSet($ids , $setting=[])
	{
		if(!is_array($ids))
			$ids = explode(',',$ids);

		return Self::whereIn('id',$ids)->update($setting);
	}


}