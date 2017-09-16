<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use TahaModelTrait {
        store as protected traitStore;
    }
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'meta' => 'array',
    ];
    protected static $statusesNames = [
        -1 => 'canceled',
        0  => 'on_hold',
        1  => 'succeeded',
    ];

    public function storePosts($postIds, $attributes = [])
    {
        if (!auth()->guest()) {
            if (is_array($postIds)) {
                foreach ($postIds as $postId => $attributes) {
                    $extraData = [];
                    if (!$this->posts->contains($postId)) {
                        $extraData['created_by'] = user()->id;
                    }
                    $extraData['updated_by'] = user()->id;
                    $postIds[$postId] = array_merge($postIds[$postId], $extraData);
                }
            } else {
                $extraData = [];
                if (!$this->posts->contains($postIds)) {
                    $extraData['created_by'] = user()->id;
                }
                $extraData['updated_by'] = user()->id;

                $attributes = array_merge($attributes, $extraData);
            }
        }

        $this->posts()->attach($postIds, $attributes);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */

    public function posts()
    {
        return $this->belongsToMany('App\Models\Post')
            ->withPivot(
                'count',
                'original_price',
                'offered_price',
                'total_price',
                'created_by',
                'updated_by'
            )->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */

    public function getStatusNameAttribute()
    {
        if (array_key_exists($this->status, self::$statusesNames)) {
            return self::$statusesNames[$this->status];
        }

        return 'unknown';
    }

    /**
     * Returns viewable name of order orderer
     *
     * @return string
     */
    public function getOrdererNameAttribute()
    {
        $this->spreadMeta();
        if ($this->user) {
            return $this->user->full_name;
        } else if (!is_null($this->name)) {
            return $this->name;
        } else {
            return trans('people.deleted_user');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Stators
    |--------------------------------------------------------------------------
    |
    */
    public function can($permit = '*')
    {

        return user()->as('admin')->can('orders' . '.' . $permit);
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    */


    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    |
    */

    public static function store($request, $unset_things = [])
    {
        //Convert to Array...
        if (is_array($request)) {
            $data = $request;
        } else {
            $data = $request->toArray();
        }

        if (array_key_exists('status_name', $data) and !array_key_exists('status', $data)) {
            $statusCode = in_array($data['status_name'], self::$statusesNames);
            if ($statusCode !== false) {
                $data['status'] = $statusCode;
            }
        }
        $unset_things[] = 'status_name';

        return self::traitStore($data, $unset_things);
    }

    public static function statusCombo()
    {
        $result = [];
        foreach (self::$statusesNames as $statusName) {
            $result[] = [$statusName, trans('forms.status_text.' . $statusName)];
        }
        return $result;
    }

    public static function statusesNames($statusCode = null)
    {
        if (isset($statusCode)) {
            if (isset(self::$statusesNames[$statusCode])) {
                return self::$statusesNames[$statusCode];
            } else {
                return null;
            }
        }
        return self::$statusesNames;
    }

    public static function statusesCodes($statusCode = null)
    {
        $statusesCodes = array_flip(self::$statusesNames);
        if (isset($statusCode)) {
            if (isset($statusesCodes[$statusCode])) {
                return $statusesCodes[$statusCode];
            } else {
                return null;
            }
        }
        return $statusesCodes;
    }


    /*
    |--------------------------------------------------------------------------
    | Selectors
    |--------------------------------------------------------------------------
    |
    */
    public static function selector($parameters = [])
    {
        $data = array_normalize($parameters, [
            'post_id'      => "0",
            'created_by'   => "",
            'published_by' => "",
            'criteria'     => "succeeded",
            'search'       => "",
            'is_by_admin'  => null,
        ]);

        $table = self::where('id', '>', '0');

        /*-----------------------------------------------
        | Easy Switches ...
        */
        if ($data['post_id']) {
            $table = $table->where('post_id', $data['post_id']);
        }
        if ($data['created_by']) {
            $table = $table->where('created_by', $data['created_by']);
        }
        if ($data['published_by']) {
            $table = $table->where('published_by', $data['published_by']);
        }

        /*-----------------------------------------------
        | Process Criteria ...
        */
        switch ($data['criteria']) {
            case 'all' :
                break;

            case 'all_with_trashed' :
                $table = $table->withTrashed();
                break;

            default:
                if (($statusCode = self::statusesCodes($data['criteria'])) !== false) {
                    $table = $table->where('status', $statusCode);
                } else {
                    $table = $table->where('id', '0');
                }
                break;

        }

        //Return...
        return $table;
    }

}
