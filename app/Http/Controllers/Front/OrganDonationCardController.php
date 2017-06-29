<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Providers\FaGDServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganDonationCardController extends Controller
{
    public function index($type, $user_hash_id, $mode = null)
    {
        $user = User::findByHashid($user_hash_id);
        if (!$user or !$user->is_an('card-holder'))
        {
            return view('errors.404');
        }

        if ($mode == 'print')
        {
            // print card
            return view('front.members.print_my_card.0', compact('type', 'user', 'mode'));
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

    public function card_mini($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $user->toArray();

        // load persian font
        $font = $this->getPersianFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('cardMini.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $birth_date = echoDate($user['birth_date'], 'Y/m/d', 'fa', true);
        $register_date = echoDate($user['card_registered_at'], 'Y/m/d', 'fa', true);

        // font size
        $font_size = 25;

        // position
        $name_position = imagettfbbox($font_size, 0, $font, $name_full);
        $name_position = $name_position[2] - $name_position[0];

        $name_father_position = imagettfbbox($font_size, 0, $font, $name_father);
        $name_father_position = $name_father_position[2] - $name_father_position[0];

        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];

        $national_position = imagettfbbox($font_size, 0, $font, $user['code_melli']);
        $national_position = $national_position[2] - $national_position[0];

        $birth_date_position = imagettfbbox($font_size, 0, $font, $birth_date);
        $birth_date_position = $birth_date_position[2] - $birth_date_position[0];

        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
        $register_date_position = $register_date_position[2] - $register_date_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);

        // Add the text
        imagettftext($img, $font_size, 0, (500 - $card_no_position), 173, $black, $font, $user['card_no']);
        imagettftext($img, $font_size, 0, (500 - $name_position), 212, $black, $font, $name_full);
        imagettftext($img, $font_size, 0, (500 - $name_father_position), 254, $black, $font, $name_father);
        imagettftext($img, $font_size, 0, (500 - $national_position), 300, $black, $font, $user['code_melli']);
        imagettftext($img, $font_size, 0, (500 - $birth_date_position), 341, $black, $font, $birth_date);
        imagettftext($img, $font_size, 0, (500 - $register_date_position), 382, $black, $font, $register_date);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_single($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $user->toArray();

        // load persian font
        $font = $this->getPersianFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('cardSingle.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $birth_date = echoDate($user['birth_date'], 'Y/m/d', 'fa', true);
        $register_date = echoDate($user['card_registered_at'], 'Y/m/d', 'fa', true);

        // font size
        $font_size = 25;

        // position
        $name_position = imagettfbbox($font_size, 0, $font, $name_full);
        $name_position = $name_position[2] - $name_position[0];

        $name_father_position = imagettfbbox($font_size, 0, $font, $name_father);
        $name_father_position = $name_father_position[2] - $name_father_position[0];

        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];

        $national_position = imagettfbbox($font_size, 0, $font, $user['code_melli']);
        $national_position = $national_position[2] - $national_position[0];

        $birth_date_position = imagettfbbox($font_size, 0, $font, $birth_date);
        $birth_date_position = $birth_date_position[2] - $birth_date_position[0];

        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
        $register_date_position = $register_date_position[2] - $register_date_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);

        // Add the text
        imagettftext($img, $font_size, 0, (500 - $card_no_position), 173, $black, $font, $user['card_no']);
        imagettftext($img, $font_size, 0, (500 - $name_position), 212, $black, $font, $name_full);
        imagettftext($img, $font_size, 0, (500 - $name_father_position), 254, $black, $font, $name_father);
        imagettftext($img, $font_size, 0, (500 - $national_position), 300, $black, $font, $user['code_melli']);
        imagettftext($img, $font_size, 0, (500 - $birth_date_position), 341, $black, $font, $birth_date);
        imagettftext($img, $font_size, 0, (500 - $register_date_position), 382, $black, $font, $register_date);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_social($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $user->toArray();

        // load persian font
        $font = $this->getPersianFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('cardSocial.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $register_date = echoDate($user['card_registered_at'], 'Y/m/d', 'fa', true);

        // font size
        $font_size = 21;
        $name_font_size = 30;

        // position
        $name_position = imagettfbbox($name_font_size, 0, $font, $name_full);
        $name_position = $name_position[2] - $name_position[0];

        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];

        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
        $register_date_position = $register_date_position[2] - $register_date_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);

        // Add the text
        imagettftext($img, $font_size, 0, (590 - $card_no_position), 260, $black, $font, $user['card_no']);
        imagettftext($img, $name_font_size, 0, (525 - $name_position), 205, $black, $font, $name_full);
        imagettftext($img, $font_size, 0, (597 - $register_date_position), 318, $black, $font, $register_date);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_full($user, $mode)
    {
        ini_set("error_reporting","E_ALL & ~E_NOTICE & ~E_STRICT");

        $user = $user->toArray();

        // load persian font
        $font = $this->getPersianFont();
        $enFont = $this->getEnglishFont();

        // set header
        $this->setHeader($mode, $user['card_no']);

        // orginal image
        $img = imagecreatefrompng($this->getDefaultImage('finalCart.png'));

        // data
        $name_full = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $birth_date = echoDate($user['birth_date'], 'Y/m/d', 'fa', true);
        $register_date = echoDate($user['card_registered_at'], 'Y/m/d', 'fa', true);

        // font size
        $font_size = 30;

        // position
        $name_position = imagettfbbox($font_size, 0, $font, $name_full);
        $name_position = $name_position[2] - $name_position[0];

        $name_father_position = imagettfbbox($font_size, 0, $font, $name_father);
        $name_father_position = $name_father_position[2] - $name_father_position[0];

        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];

        $national_position = imagettfbbox($font_size, 0, $font, $user['code_melli']);
        $national_position = $national_position[2] - $national_position[0];

        $birth_date_position = imagettfbbox($font_size, 0, $font, $birth_date);
        $birth_date_position = $birth_date_position[2] - $birth_date_position[0];

        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
        $register_date_position = $register_date_position[2] - $register_date_position[0];

        $email_position = imagettfbbox(40, 0, $font, $user['email']);
        $email_position = $email_position[2] - $email_position[0];

        $mobile_position = imagettfbbox(40, 0, $enFont, $user['tel_mobile']);
        $mobile_position = $mobile_position[2] - $mobile_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);

        // Add the text
        imagettftext($img, $font_size, 0, (850 - $card_no_position), 567, $black, $font, $user['card_no']);
        imagettftext($img, $font_size, 0, (850 - $name_position), 620, $black, $font, $name_full);
        imagettftext($img, $font_size, 0, (850 - $name_father_position), 665, $black, $font, $name_father);
        imagettftext($img, $font_size, 0, (850 - $national_position), 720, $black, $font, $user['code_melli']);
        imagettftext($img, $font_size, 0, (850 - $birth_date_position), 772, $black, $font, $birth_date);
        imagettftext($img, $font_size, 0, (850 - $register_date_position), 822, $black, $font, $register_date);
        imagettftext($img, 40, 0, (1850 - $mobile_position), 2115, $black, $font, $user['tel_mobile']);
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
        return public_path('assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $image_name);
    }

    public function getPersianFont()
    {
        return public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'BNazanin.ttf');
    }

    public function getEnglishFont()
    {
        return public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'calibri.ttf');
    }
}
