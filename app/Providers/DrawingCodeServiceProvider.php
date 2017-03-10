<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DrawingCodeServiceProvider extends ServiceProvider
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

    public static function create_uniq($timestamp, $invoice_price)
    {
        $timestamp = str_split(ltrim($timestamp, '1'));
        $invoice_price_count = strlen($invoice_price);
        $invoice_price = str_split($invoice_price);
        $uniq_code = $invoice_price_count;

        // reverse invoice price reduce per character from 9
        $reverse_invoice_price = '';
        for ($i = 0; $i < $invoice_price_count; $i++)
        {
            $reverse_invoice_price .= (9 - $invoice_price[$i]);
        }

        // ezafeh kardan adad be character haye reverse invoice price ta tedad character ha be 9 beresad
        $invoice_price_be_change = $reverse_invoice_price;
        for ($i = 0, $a = 1; $i < 9 - $invoice_price_count; $i++)
        {
            $invoice_price_be_change .= $a++;
        }

        // mix time stamp and invoice be change character
        for ($i = 0; $i < 9; $i++)
        {
            $uniq_code .= $timestamp[$i];
            $uniq_code .= $invoice_price_be_change[$i];
        }

        // change 1 and 5 uniq code character
        $character1 = $uniq_code[0];
        $character5 = $uniq_code[4];
        $uniq_code[0] = $character5;
        $uniq_code[4] = $character1;

        // controller number
        $starter = 9 - $uniq_code[0];
        if ($starter <= 2)
        {
            $starter = 8;
        }

        // create controller number
        $sum = 0;
        for ($i = 18; $i > 0; $i--)
        {
            if ($starter < 2)
            {
                $starter = 8;
            }
            $sum += ($uniq_code[$i] * $starter);
            $starter--;
        }
        $sum += $uniq_code[0];
        $sum = str_split($sum);

        // create uniq code character 20
        $uniq_code .= (9 - $sum[count($sum) - 1]);

        // change 20 and 15 uniq code character
        $character15 = $uniq_code[14];
        $character20 = $uniq_code[19];
        $uniq_code[14] = $character20;
        $uniq_code[19] = $character15;

        // separator
        $final_uniq_code = '';
        for ($i = 0; $i <= 19; $i++)
        {
            if ($i == 4 or $i == 9 or $i == 14)
            {
                $seperator = '-';
            }
            else
            {
                $seperator = '';
            }
            $final_uniq_code .= $uniq_code[$i] . $seperator;
        }

        return $final_uniq_code;
    }

    public static function check_uniq($uniq)
    {
        if (strlen($uniq) != 20 or ! is_numeric($uniq))
        {
            return false;
        }

        $uniq = str_split($uniq);
        $data = array();

        // change 20 and 15 uniq code character
        $character15 = $uniq[14];
        $character20 = $uniq[19];
        $uniq[14] = $character20;
        $uniq[19] = $character15;

        $controller = 9 - $uniq[19];

        // controller number
        $starter = 9 - $uniq[0];
        if ($starter <= 2)
        {
            $starter = 8;
        }

        // create controller number
        $sum = 0;
        for ($i = 18; $i > 0; $i--)
        {
            if ($starter < 2)
            {
                $starter = 8;
            }
            $sum += ($uniq[$i] * $starter);
            $starter--;

        }
        $sum += $uniq[0];
        $sum = str_split($sum);

        if ($sum[count($sum) - 1] != $controller)
            return false;

        // change 1 and 5 uniq code character
        $character1 = $uniq[0];
        $character5 = $uniq[4];
        $uniq[0] = $character5;
        $uniq[4] = $character1;

        $data['date'] = 1 . $uniq[1] . $uniq[3] . $uniq[5] . $uniq[7] . $uniq[9] . $uniq[11] . $uniq[13] . $uniq[15] . $uniq[17];

        $price = $uniq[2] . $uniq[4] . $uniq[6] . $uniq[8] . $uniq[10] . $uniq[12] . $uniq[14] . $uniq[16] . $uniq[18];
        $price = str_split($price);
        $data['price'] = '';
        for ($i = 0; $i < $uniq[0]; $i++)
        {
            $data['price'] .= (9 - $price[$i]);
        }

        return $data;
    }
}
