<?php
namespace App\Traits;


use App\Providers\SettingServiceProvider;
use Illuminate\Support\Facades\Session;

trait GlobalControllerTrait
{
    public function getDomain()
    {
        $lang = \Request::segment(1);

        if ($lang == 'fa' or $lang == 'en' or $lang == 'ar')
        {
            $result = $lang;
        }
        else
        {
            $result = 'fa';
        }

        return $result;
    }
}