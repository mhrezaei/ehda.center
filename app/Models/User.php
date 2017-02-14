<?php

namespace App\Models;

use App\Traits\PermitsTrait;
use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable , TahaModelTrait , PermitsTrait , SoftDeletes;

    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    public static $meta_fields = ['preferences'] ;

    protected $casts = [
         'meta' => 'array' ,
         'newsletter' => 'boolean' ,
         'password_force_change' => 'boolean' ,
         'published_at' => 'datetime' ,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role')->withPivot('permissions');
    }

    /*
    |--------------------------------------------------------------------------
    | Normal Modules
    |--------------------------------------------------------------------------
    |
    */
    public function preference($slug)
    {
        $this->spreadMeta() ;
        $preferences = array_normalize($this->preferences , [
            'max_rows_per_page' => "50",
        ]);

        return $preferences[$slug];
    }


    /*
    |--------------------------------------------------------------------------
    | Selectors
    |--------------------------------------------------------------------------
    |
    */
    public static function selector($parameters=[])
    {
        extract(array_normalize($parameters , [
             'role' => "user",
             'criteria' => "actives",
        ]));

        //Process Role...
        $table = Role::findBySlug($role)->users() ;
        if(!user()->isDeveloper()) {
            $table = $table->whereNotIn('email' , ['chieftaha@gmail.com' , 'mr.mhrezaei@gmail.com']);
        }

        //Process Criteria....
        switch($criteria) {
            case 'actives' :
                break;

            case 'blocked' :
                $table = $table->onlyTrashed()->whereColumn('deleted_by', '!=', 'id');
                break;

            case 'deleted' :
                $table = $table->onlyTrashed()->whereColumn('deleted_by', 'id');
                break;

            case 'bin' :
                $table = $table->onlyTrashed();
                break;

            default:
                $table = $table->where('users.id' , '0');

        }

        //Return...
        return $table ;
    }
}
