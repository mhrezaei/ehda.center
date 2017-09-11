<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use TahaModelTrait, SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'meta' => 'array',
    ];

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
}
