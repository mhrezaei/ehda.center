<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Comment extends Model
{
    use TahaModelTrait, SoftDeletes;

    public static $meta_fields = [
        'text',
        'image_files',
        'audio_files',
        'video_files',
        'text_files',
        'text_content',
        'description',
        'name',
        'city',
        'donation_date',
        'donor_name',
        'submitter_name',
        'submitter_phone',
    ];
    protected $guarded = ['id'];
    protected $casts = [
        'meta'         => 'array',
        'published_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    |
    */
    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function posttype()
    {
        $posttype = Posttype::findBySlug($this->type);

        if ($posttype) {
            return $posttype;
        } else {
            return new Posttype();
        }
    }

    public function getPosttypeAttribute()
    {
        return $this->posttype();
    }

    public function children()
    {
        return self::where('replied_on', $this->id);
    }

    public function parent()
    {
        if ($this->replied_on) {
            $parent = self::withTrashed()->find($this->replied_on);
            if (!$parent) {
                $parent = new Comment();
                $parent->post_id = $this->post_id;
                $parent->id = $this->replied_on;
            }
            return $parent;
        } else {
            return $this;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Cache Management
    |--------------------------------------------------------------------------
    |
    */
    public function cacheRegenerateOnInsertOrDelete()
    {
        $this->post->cacheUpdateComments();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    |
    */
    public function getStatusAttribute()
    {
        if ($this->trashed()) {
            return 'deleted';
        }
        if ($this->published_at and $this->is_private) {
            return 'private';
        } elseif ($this->published_at) {
            return 'published';
        } else {
            return 'pending';
        }

    }

    public function getSiteLinkAttribute()
    {
        return url(); //@TODO: Complete This!
    }

    public function getIsPublicAttribute()
    {
        return boolval($this->published_at) and !$this->is_private;
    }

    /**
     * Returns viewable name of comment sender
     *
     * @return string
     */
    public function getSenderNameAttribute()
    {
        $this->spreadMeta();
        if ($this->user) {
            return $this->user->full_name;
        } else if (!is_null($this->submitter_name)) {
            return $this->submitter_name;
        } else if (!is_null($this->name)) {
            return $this->name;
        } else {
            return trans('people.deleted_user');
        }
    }

    public function getCityNameAttribute()
    {
        $this->spreadMeta();
        $city = State::findBySlug($this->city, 'id');
        if ($city->exists and !$city->isProvince()) {
            return $city->province->title . ' - ' . $city->title;
        }
        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | Stators
    |--------------------------------------------------------------------------
    |
    */
    public function can($permit = '*')
    {
        return user()->as('admin')->can('comments-', $this->type . '.' . $permit);
    }

    public function canPublish()
    {
        return $this->can('publish');
    }

    /*
    |--------------------------------------------------------------------------
    | Selectors
    |--------------------------------------------------------------------------
    |
    */
    public static function selector($parameters = [])
    {
        extract(array_normalize($parameters, [
            'type'         => "all",
            'post_id'      => "0",
            'user_id'      => "0",
            'replied_on'   => "0",
            'email'        => "",
            'ip'           => "",
            'created_by'   => "",
            'published_by' => "",
            'criteria'     => "approved",
            'search'       => "",
            'is_by_admin'  => null,
        ]));

        $table = self::where('id', '>', '0');

        /*-----------------------------------------------
        | Process Type ...
        */
        if (is_string($type) and str_contains($type, 'feature:')) {
            $feature = str_replace('feature:', null, $type);
            $type = Posttype::withFeature($feature); //returns an array of posttypes
        }

        //when an array of selected posttypes are requested
        if (is_array($type)) {
            $table = $table->whereIn('type', $type);
        } //when 'all' posttypes are requested
        elseif ($type == 'all') {
            // nothing required here :)
        } //when an specific type is requested
        else {
            $table = $table->where('type', $type);
        }

        /*-----------------------------------------------
        | Easy Switches ...
        */
        if ($post_id) {
            $table = $table->where('post_id', $post_id);
        }
        if ($user_id) {
            $table = $table->where('user_id', $user_id);
        }
        if ($replied_on !== null) {
            $table = $table->where('replied_on', $replied_on);
        }
        if ($email) {
            $table = $table->where('email', $email);
        }
        if ($ip) {
            $table = $table->where('ip', $ip);
        }
        if ($created_by) {
            $table = $table->where('created_by', $created_by);
        }
        if ($published_by) {
            $table = $table->where('published_by', $published_by);
        }
        if ($is_by_admin !== null) {
            $table = $table->where('is_by_admin', $is_by_admin);
        }

        /*-----------------------------------------------
        | Process Criteria ...
        */
        switch ($criteria) {
            case 'all' :
                break;

            case 'all_with_trashed' :
                $table = $table->withTrashed();
                break;

            case 'approved' :
                $table = $table->whereNotNull('published_at');
                break;

            case 'public' :
                $table = $table->whereNotNull('published_at')->where('is_private', 0);
                break;

            case 'private' :
                $table = $table->where('is_private', '1');
                break;

            case 'pending':
                $table = $table->whereNull('published_at');
                break;

            case 'bin' :
                $table = $table->onlyTrashed();
                break;

            default:
                $table = $table->where('id', '0');
                break;

        }

        /*-----------------------------------------------
        | Process Search ...
        */
        if ($search) {
            $table = $table->whereRaw(self::searchRawQuery($search));
        }


        //Return...
        return $table;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    */
    public function statusCombo()
    {
        return [
            ['pending', trans('forms.status_text.pending')],
            ['published', trans('forms.status_text.published')],
            ['private', trans('forms.status_text.private')],
            //['deleted', trans('forms.status_text.deleted')],
        ];
    }

    public static function tab2permit($request_tab)
    {
        switch ($request_tab) {
            case 'search' :
                $permit = '*';
                break;

            case 'pending':
                $permit = '*';
                break;

            case 'published' :
            case 'privates' :
                $permit = '*';
                break;

            default :
                $permit = $request_tab;
        }

        return $permit;

    }

    public static function checkManagePermission($posttype, $criteria)
    {
        $permit = self::tab2permit($criteria);

        if ($posttype) {
            return user()->as('admin')->can("comments-$posttype.$permit");
        } else {
            return user()->as('admin')->can("comments");
        }
    }

    public function saveStatus($new_status)
    {
        if ($this->status == $new_status) {
            return true;
        }

        switch ($new_status) {
            case 'pending' :
                $this->published_at = null;
                $this->published_by = 0;
                break;

            case 'published' :
                $this->published_at = Carbon::now()->toDateTimeString();
                $this->publised_by = user()->id;
                $this->is_private = false;
                break;

            case 'private' :
                $this->published_at = Carbon::now()->toDateTimeString();
                $this->publised_by = user()->id;
                $this->is_private = true;
                break;

            case 'approve' :
                $this->published_at = Carbon::now()->toDateTimeString();
                $this->publised_by = user()->id;
                break;

            default :
                return false;

        }

        return $this->suppressMeta()->save();

    }

}
