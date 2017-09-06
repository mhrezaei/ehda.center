<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPosts extends Model
{
    use TahaModelTrait, SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'meta'         => 'array',
    ];
}
