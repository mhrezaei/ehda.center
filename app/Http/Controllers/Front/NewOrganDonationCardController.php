<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Providers\FaGDServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewOrganDonationCardController extends Controller
{
    public function index($type, $user_hash_id, $mode = null, $hash_type = 'id')
    {
        $card_type = ['mini', 'single', 'social', 'full'];
        $card_mode = ['show', 'download', 'print'];
        $hash_types = ['id', 'st'];

        if (!in_array($type, $card_type))
        {
            $type = 'mini';
        }

        if (!in_array($mode, $card_mode))
        {
            $mode = 'show';
        }

        if (!in_array($hash_type, $hash_types))
        {
            $hash_type = 'id';
        }


        if ($hash_type == 'id')
        {
            $user = User::findByHashid($user_hash_id, 'ehda_card_' . $type);
            if (!$user or !$user->is_an('card-holder'))
            {
                return view('errors.404');
            }
        }
        elseif ($hash_type == 'st')
        {
            $user = decrypt($user_hash_id);
        }
        else
        {
            return view('errors.404');
        }



        if ($mode == 'print')
        {
            if ($hash_type == 'st')
                $user = json_decode(json_encode($user), false);

            // print card
            return view('front.members.print_my_card.new', compact('type', 'user', 'mode'));
        }
        else
        {
            // show and download card
            switch ($type)
            {
                case "mini":
                    return $this->card_mini($user, $mode);
                    break;
                case "single":
                    return $this->card_single($user, $mode);
                    break;
                case "social":
                    return $this->card_social($user, $mode);
                    break;
                case "full":
                    return $this->card_full($user, $mode);
                    break;
                default:
                    return $this->card_mini($user, $mode);
            }
        }
    }

    public function serverProcess($type, $user_hash_id, $mode = null)
    {
        $card_type = ['mini', 'single', 'social', 'full'];
        $card_mode = ['show', 'download', 'print'];
        $external_server = 'https://s2.ehda.center/card/show_card_new/';

        if (!in_array($type, $card_type))
        {
            $type = 'mini';
        }

        if (!in_array($mode, $card_mode))
        {
            $mode = 'show';
        }

        $user = User::findByHashid($user_hash_id, 'ehda_card_' . $type);
        if (!$user or !$user->is_an('card-holder'))
        {
            return view('errors.404');
        }

        if (setting('external_server')->gain() and $user->setGenerateCardServer())
        {
            $url = $external_server . $type . '/' . encrypt($user->generateCardData()) . '/' . $mode . '/' . 'st';
            return redirect($url);
        }
        else
        {
            return $this->index($type, $user_hash_id, $mode);
        }
    }

    public function card_mini($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $this->preUser($user);

        // load persian font
        $font = $this->getPersianFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('cardMini.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');

        $data = $user['card_no'] . ' - ' . $name_full;

        // font size
        $font_size = 25;

        // position
        $data_position = imagettfbbox($font_size, 0, $font, $data);
        $data_position = $data_position[2] - $data_position[0];
        $data_position = (800 - $data_position) / 2;

        // Create some colors
        $black = imagecolorallocate($img, 66, 66, 66);

        // Add the text
        imagettftext($img, $font_size, 0, $data_position, 910, $black, $font, $data);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_single($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $this->preUser($user);

        // load persian font
        $font = $this->getPersianFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('cardSingle.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');

        $data = $user['card_no'] . ' - ' . $name_full;

        // font size
        $font_size = 25;

        // position
        $data_position = imagettfbbox($font_size, 0, $font, $data);
        $data_position = $data_position[2] - $data_position[0];
        $data_position = (800 - $data_position) / 2;

        // Create some colors
        $black = imagecolorallocate($img, 66, 66, 66);

        // Add the text
        imagettftext($img, $font_size, 0, $data_position, 380, $black, $font, $data);


        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_social($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $this->preUser($user);

        // load persian font
        $font = $this->getPersianFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('cardSocial.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
//        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
//        $register_date = echoDate($user['card_registered_at'], 'Y/m/d', 'fa', true);

        // font size
        $font_size = 21;
        $name_font_size = 34;

        // position
        $name_position = imagettfbbox($name_font_size, 0, $font, $name_full);
        $name_position = $name_position[2] - $name_position[0];
        $name_position = ((750 - $name_position) / 2) + 150;


        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];
        $card_no_position = ((750 - $card_no_position) / 2) + 150;

//        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
//        $register_date_position = $register_date_position[2] - $register_date_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);
        $blue = imagecolorallocate($img, 1, 174, 240);

        // Add the text
        imagettftext($img, $name_font_size, 0, $name_position, 1080, $blue, $font, $name_full);
        imagettftext($img, $name_font_size, 0, $card_no_position, 1140, $blue, $font, $user['card_no']);
//        imagettftext($img, $font_size, 0, (597 - $register_date_position), 318, $black, $font, $register_date);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_full($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $this->preUser($user);

        // load persian font
        $font = $this->getPersianFont();
        $enFont = $this->getEnglishFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('finalCart.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $data = $user['card_no'] . ' - ' . $name_full;
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $birth_date = echoDate($user['birth_date'], 'Y/m/d', 'fa', true);
        $register_date = echoDate($user['card_registered_at'], 'Y/m/d', 'fa', true);

        // font size
        $font_size = 30;

        // position
        $email_position = imagettfbbox(40, 0, $font, $user['email']);
        $email_position = $email_position[2] - $email_position[0];

        $mobile_position = imagettfbbox(40, 0, $enFont, $user['mobile']);
        $mobile_position = $mobile_position[2] - $mobile_position[0];

        $data_position = imagettfbbox($font_size, 0, $font, $data);
        $data_position = $data_position[2] - $data_position[0];
        $data_position = ((800 - $data_position) / 2) + 1272;

        // Create some colors
        $black = imagecolorallocate($img, 66, 66, 66);

        // Add the text
        imagettftext($img, $font_size, 0, $data_position, 825, $black, $font, $data);
        imagettftext($img, 40, 0, (1850 - $mobile_position), 2115, $black, $font, $user['mobile']);
        imagettftext($img, 40, 0, (1850 - $email_position), 2190, $black, $enFont, $user['email']);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function setHeader($mode, $file_name)
    {
        header("Content-type: image/png");
        if($mode == 'print')
        {
            header('Content-Disposition: filename=' . 'کارت_اهدای_عضو_' . $file_name . '.png');
        }
        elseif($mode == 'download')
        {
            header('Content-Description: File Transfer');
            header('Content-Type: image/png');
            header('Content-Disposition: attachment; filename="' . $file_name . '.png"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: filename=' . 'کارت_اهدای_عضو_' . $file_name . '.png');
        }
    }

    public function getDefaultImage($image_name)
    {
        return public_path('assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'card' . DIRECTORY_SEPARATOR  . $image_name);
    }

    public function getPersianFont()
    {
        return public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'BNazanin.ttf');
    }

    public function getEnglishFont()
    {
        return public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'calibri.ttf');
    }

    public function preUser($user)
    {
        if (is_object($user))
        {
            return $user->toArray();
        }
        else
        {
            return $user;
        }
    }
}
