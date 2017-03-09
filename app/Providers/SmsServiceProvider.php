<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function send($destination, $msgBody)
    {
        $destination = self::purifier_destination($destination);
        if (!$destination)
            return false;
        $URL = "http://panel.asanak.ir/webservice/v1rest/sendsms";
        $msg = urlencode(trim($msgBody));
        $url = $URL.'?username='.self::config()['username'].'&password='.self::config()['password'].'&source='.self::config()['source'].'&destination='.$destination.'&message='. $msg;
        $headers[] = 'Accept: text/html';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        try
        {
            if(($return = curl_exec($process)))
            {
                return $return;
            }
        } catch (Exception $ex)
        {
            return $ex->errorMessage();
        }
    }

    private static function config()
    {
        return [
            'username' => 'ehda88190180',
            'password' => 'ehda!centerAsanak88190',
            'source'   => '02188190180',
        ];

    }

    private static function purifier_destination($dis)
    {
        $destination = '';
        if (is_array($dis))
        {
            for($i = 0; $i < count($dis); $i++)
            {
                if(is_numeric($dis[$i]) and strlen($dis[$i]) == 11)
                {
                    $destination .= $dis[$i] . ',';
                }
            }
        }
        else
        {
            if(is_numeric($dis) and strlen($dis) == 11)
            {
                $destination = $dis;
            }
        }

        return $destination;
    }
}
