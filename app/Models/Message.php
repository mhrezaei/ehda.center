<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use TahaModelTrait;

    public static $meta_fields = [
        'subject',
        'template',
    ];
    protected $guarded = ['id'];
    protected $casts = [
        'meta'         => 'array',
    ];
}
