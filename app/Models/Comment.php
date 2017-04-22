<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use TahaModelTrait;

    public static $meta_fields = ['text'];
    protected $guarded = ['id'];
    protected $casts = [
        'meta' => 'array',
        'newsletter' => 'boolean',
        'password_force_change' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getCreatorAttribute()
    {
        $user = User::find($this->created_by);
        if ($user) {
            return $user;
        } else {
            return new User();
        }
    }

    //
}
