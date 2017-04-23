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

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

}
