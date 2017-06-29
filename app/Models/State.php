<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes, TahaModelTrait;

    protected $guarded = ['id'];

    private static $combo;

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function cities()
    {
        if ($this->id)
            return self::where('parent_id', $this->id);
        else
            return self::where('parent_id', '>', '0');
    }

    public function capital()
    {
        if ($this->isProvince())
            return self::find($this->capital_id);
        else
            return $this->province()->capital();
    }

    public function province()
    {
        if ($this->isProvince())
            return $this;
        else {
            return self::find($this->parent_id);
        }
    }

    public function domain()
    {
        return $this->belongsTo('App\Models\Domain');
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors and Mutators
    |--------------------------------------------------------------------------
    |
    */
    public function getFullNameAttribute()
    {
        if ($this->isProvince())
            return trans('manage.devSettings.states.province') . "  " . $this->title;
        else
            return $this->province()->title . " / " . $this->title;

    }

    public function getGuessDomainAttribute()
    {
        $guess = self::getCities($this->parent_id)->first();
        if ($guess and $guess->domain_id) {
            return $guess->domain_id;
        } else {
            return 0;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Selectors
    |--------------------------------------------------------------------------
    |
    */
    public static function getProvinces()
    {
        return self::where('parent_id', 0);
    }

    public static function getCountries()
    {
        return self::where('country_id', 0);
    }

    public static function findByName($state_name)
    {
        return self::findBySlug($state_name, 'title');
    }

    public static function getCities($given_province = 0)
    {
        if (is_numeric($given_province))
            if ($given_province == 0)
                $return = self::where('parent_id', '>', '0');
            else
                $return = self::where('parent_id', $given_province);
        else {
            $province = self::where([
                'title'     => $given_province,
                'parent_id' => '0'
            ])->first();
            if (!$province)
                $return = self::where('parent_id', 0); //safely returns nothing!
            else
                $return = self::where('parent_id', $province->id);
        }

        return $return;

    }

    /*
    |--------------------------------------------------------------------------
    | Misc Methods
    |--------------------------------------------------------------------------
    |
    */
    public static function setCapital($province_name, $city_name)
    {
        if (!$city_name) $city_name = $province_name;
        $province = self::where([
            'title'     => $province_name,
            'parent_id' => '0',
        ])->first();
        $city = self::where([
            ['title', $city_name],
            ['parent_id', '!=', '0'],
        ])->first();

        $province->capital_id = $city->id;
        $province->save();
    }

    public function isProvince()
    {
        return !$this->parent_id;
    }

    public function isCapital()
    {
        if ($this->isProvince())
            return false;

        if ($this->province()->capital_id == $this->id)
            return true;
        else
            return false;

    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    */
    public static function combo()
    {
        if (self::$combo and is_array(self::$combo)) {
            return self::$combo;
        } else {
            // @TODO: better to get all data with one query
            $provinces = self::getProvinces()->orderBy('title')->get();
            $output = [];
            $states = self::where('parent_id', '>', '0')->orderBy('parent_id')->get()->toArray();

            foreach ($provinces as $province) {
                $states = self::getCities($province->id)->orderBy('title')->get()->toArray();
                foreach ($states as $idx => $state) {
                    $states[$idx]['title'] = $province->title . " / " . $state['title'];
                }
                $output = array_merge($output, $states);
            }

            self::$combo = $output;

            return $output;
        }
    }


}
