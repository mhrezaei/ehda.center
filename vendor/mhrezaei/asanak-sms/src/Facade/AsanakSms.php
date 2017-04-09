<?php


namespace Asanak\Sms\Facade;

class AsanakSms
{

    public function __construct()
    {

    }

    public static function send($destination, $msgBody)
    {
        $destination = self::purifier_destination($destination);
        if (!$destination)
            return false;
        $URL = config('AsanakSmsConfig.url');
        $msg = urlencode(trim($msgBody));
        $url = $URL
            .'?username='.config('AsanakSmsConfig.username')
            .'&password='.config('AsanakSmsConfig.password')
            .'&source='.config('AsanakSmsConfig.source')
            .'&destination='.$destination.'&message='. $msg;
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
        }
        catch (Exception $ex)
        {
            return $ex->errorMessage();
        }
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