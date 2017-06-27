<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Api_token extends Model
{
    use TahaModelTrait;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function delete_expired()
    {
        self::where('expired_at','<=',Carbon::now()->toDateTimeString())->delete();
    }

    public static function find_token($token = null)
    {
        if (! $token)
            return false;

        $token = self::where('api_token', $token)->first();
        if ($token)
        {
            return $token;
        }
        else
        {
            return false;
        }
    }
}
