<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileDownloads extends Model
{
    use TahaModelTrait, SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'meta' => 'array',
    ];

    public function file()
    {
        return $this->belongsTo('App\Models\File');
    }
}
