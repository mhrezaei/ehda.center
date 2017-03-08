<?php

namespace App\Http\Controllers\Front;

use App\Models\Folder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index()
    {
        $data = array();

//        $data[0]['posttype_id'] = 2;
//        $data[0]['parent_id'] = 0;
//        $data[0]['locale'] = 'fa';
//        $data[0]['slug'] = 'spice';
//        $data[0]['title'] = 'ادویه';
//        $data[0]['image'] = '/photos/shares/advie.jpg';
//
//        $data[1]['posttype_id'] = 2;
//        $data[1]['parent_id'] = 0;
//        $data[1]['locale'] = 'fa';
//        $data[1]['slug'] = 'nuts';
//        $data[1]['title'] = 'آجیل';
//        $data[1]['image'] = '/photos/shares/ajil.jpg';
//
//        $data[2]['posttype_id'] = 2;
//        $data[2]['parent_id'] = 0;
//        $data[2]['locale'] = 'fa';
//        $data[2]['slug'] = 'honey';
//        $data[2]['title'] = 'عسل';
//        $data[2]['image'] = '/photos/shares/asal-2.jpg';
//
//        $data[3]['posttype_id'] = 2;
//        $data[3]['parent_id'] = 0;
//        $data[3]['locale'] = 'fa';
//        $data[3]['slug'] = 'herbal-tea';
//        $data[3]['title'] = 'دمنوش';
//        $data[3]['image'] = '/photos/shares/damnoosh.jpg';
//
//        $data[4]['posttype_id'] = 2;
//        $data[4]['parent_id'] = 0;
//        $data[4]['locale'] = 'fa';
//        $data[4]['slug'] = 'dried-fruit';
//        $data[4]['title'] = 'خشکبار';
//        $data[4]['image'] = '/photos/shares/khoshkbar.jpg';
//
//        $data[5]['posttype_id'] = 2;
//        $data[5]['parent_id'] = 0;
//        $data[5]['locale'] = 'fa';
//        $data[5]['slug'] = 'plum';
//        $data[5]['title'] = 'آلو و لواشک';
//        $data[5]['image'] = '/photos/shares/lavashak.jpg';
//
//        $data[6]['posttype_id'] = 2;
//        $data[6]['parent_id'] = 0;
//        $data[6]['locale'] = 'fa';
//        $data[6]['slug'] = 'fruits-tab';
//        $data[6]['title'] = 'میوه خشک';
//        $data[6]['image'] = '/photos/shares/miveh-khoshk.jpg';
//
//        $data[7]['posttype_id'] = 2;
//        $data[7]['parent_id'] = 0;
//        $data[7]['locale'] = 'fa';
//        $data[7]['slug'] = 'dired-herbs';
//        $data[7]['title'] = 'سبزیجات خشک';
//        $data[7]['image'] = '/photos/shares/sabzijat-khoshk.jpg';
//
//        $data[8]['posttype_id'] = 2;
//        $data[8]['parent_id'] = 0;
//        $data[8]['locale'] = 'fa';
//        $data[8]['slug'] = 'saffron';
//        $data[8]['title'] = 'زعفران';
//        $data[8]['image'] = '/photos/shares/saffron.jpg';
//
//        $data[9]['posttype_id'] = 2;
//        $data[9]['parent_id'] = 0;
//        $data[9]['locale'] = 'fa';
//        $data[9]['slug'] = 'pastry';
//        $data[9]['title'] = 'شیرینی';
//        $data[9]['image'] = '/photos/shares/shirini.jpg';
//
//        $data[10]['posttype_id'] = 2;
//        $data[10]['parent_id'] = 0;
//        $data[10]['locale'] = 'fa';
//        $data[10]['slug'] = 'chocolate';
//        $data[10]['title'] = 'شکلات';
//        $data[10]['image'] = '/photos/shares/shokolat.jpg';
//
//        $data[11]['posttype_id'] = 2;
//        $data[11]['parent_id'] = 0;
//        $data[11]['locale'] = 'fa';
//        $data[11]['slug'] = 'snack';
//        $data[11]['title'] = 'اسنک';
//        $data[11]['image'] = '/photos/shares/snack.jpg';

//        $data[0]['posttype_id'] = 2;
//        $data[0]['parent_id'] = 0;
//        $data[0]['locale'] = 'en';
//        $data[0]['slug'] = 'spice';
//        $data[0]['title'] = 'Spice';
//        $data[0]['image'] = '/photos/shares/advie.jpg';
//
//        $data[1]['posttype_id'] = 2;
//        $data[1]['parent_id'] = 0;
//        $data[1]['locale'] = 'en';
//        $data[1]['slug'] = 'nuts';
//        $data[1]['title'] = 'Nuts';
//        $data[1]['image'] = '/photos/shares/ajil.jpg';
//
//        $data[2]['posttype_id'] = 2;
//        $data[2]['parent_id'] = 0;
//        $data[2]['locale'] = 'en';
//        $data[2]['slug'] = 'honey';
//        $data[2]['title'] = 'Honey';
//        $data[2]['image'] = '/photos/shares/asal-2.jpg';
//
//        $data[3]['posttype_id'] = 2;
//        $data[3]['parent_id'] = 0;
//        $data[3]['locale'] = 'en';
//        $data[3]['slug'] = 'herbal-tea';
//        $data[3]['title'] = 'Herbal tea';
//        $data[3]['image'] = '/photos/shares/damnoosh.jpg';
//
//        $data[4]['posttype_id'] = 2;
//        $data[4]['parent_id'] = 0;
//        $data[4]['locale'] = 'en';
//        $data[4]['slug'] = 'dried-fruit';
//        $data[4]['title'] = 'Dried fruit';
//        $data[4]['image'] = '/photos/shares/khoshkbar.jpg';
//
//        $data[5]['posttype_id'] = 2;
//        $data[5]['parent_id'] = 0;
//        $data[5]['locale'] = 'en';
//        $data[5]['slug'] = 'plum';
//        $data[5]['title'] = 'Plum';
//        $data[5]['image'] = '/photos/shares/lavashak.jpg';
//
//        $data[6]['posttype_id'] = 2;
//        $data[6]['parent_id'] = 0;
//        $data[6]['locale'] = 'en';
//        $data[6]['slug'] = 'fruits-tab';
//        $data[6]['title'] = 'Fruits tab';
//        $data[6]['image'] = '/photos/shares/miveh-khoshk.jpg';
//
//        $data[7]['posttype_id'] = 2;
//        $data[7]['parent_id'] = 0;
//        $data[7]['locale'] = 'en';
//        $data[7]['slug'] = 'dired-herbs';
//        $data[7]['title'] = 'Dired herbs';
//        $data[7]['image'] = '/photos/shares/sabzijat-khoshk.jpg';
//
//        $data[8]['posttype_id'] = 2;
//        $data[8]['parent_id'] = 0;
//        $data[8]['locale'] = 'en';
//        $data[8]['slug'] = 'saffron';
//        $data[8]['title'] = 'Saffron';
//        $data[8]['image'] = '/photos/shares/saffron.jpg';
//
//        $data[9]['posttype_id'] = 2;
//        $data[9]['parent_id'] = 0;
//        $data[9]['locale'] = 'en';
//        $data[9]['slug'] = 'pastry';
//        $data[9]['title'] = 'Pastry';
//        $data[9]['image'] = '/photos/shares/shirini.jpg';
//
//        $data[10]['posttype_id'] = 2;
//        $data[10]['parent_id'] = 0;
//        $data[10]['locale'] = 'en';
//        $data[10]['slug'] = 'chocolate';
//        $data[10]['title'] = 'Chocolate';
//        $data[10]['image'] = '/photos/shares/shokolat.jpg';
//
//        $data[11]['posttype_id'] = 2;
//        $data[11]['parent_id'] = 0;
//        $data[11]['locale'] = 'en';
//        $data[11]['slug'] = 'snack';
//        $data[11]['title'] = 'Snack';
//        $data[11]['image'] = '/photos/shares/snack.jpg';

        $data[0]['posttype_id'] = 2;
        $data[0]['parent_id'] = 0;
        $data[0]['locale'] = 'ar';
        $data[0]['slug'] = 'spice';
        $data[0]['title'] = 'تابل';
        $data[0]['image'] = '/photos/shares/advie.jpg';

        $data[1]['posttype_id'] = 2;
        $data[1]['parent_id'] = 0;
        $data[1]['locale'] = 'ar';
        $data[1]['slug'] = 'nuts';
        $data[1]['title'] = 'جوز';
        $data[1]['image'] = '/photos/shares/ajil.jpg';

        $data[2]['posttype_id'] = 2;
        $data[2]['parent_id'] = 0;
        $data[2]['locale'] = 'ar';
        $data[2]['slug'] = 'honey';
        $data[2]['title'] = 'عسل';
        $data[2]['image'] = '/photos/shares/asal-2.jpg';

        $data[3]['posttype_id'] = 2;
        $data[3]['parent_id'] = 0;
        $data[3]['locale'] = 'ar';
        $data[3]['slug'] = 'herbal-tea';
        $data[3]['title'] = 'شاي أعشاب';
        $data[3]['image'] = '/photos/shares/damnoosh.jpg';

        $data[4]['posttype_id'] = 2;
        $data[4]['parent_id'] = 0;
        $data[4]['locale'] = 'ar';
        $data[4]['slug'] = 'dried-fruit';
        $data[4]['title'] = 'الفواكه المجففة';
        $data[4]['image'] = '/photos/shares/khoshkbar.jpg';

        $data[5]['posttype_id'] = 2;
        $data[5]['parent_id'] = 0;
        $data[5]['locale'] = 'ar';
        $data[5]['slug'] = 'plum';
        $data[5]['title'] = 'الخوخ والفاكهة';
        $data[5]['image'] = '/photos/shares/lavashak.jpg';

        $data[6]['posttype_id'] = 2;
        $data[6]['parent_id'] = 0;
        $data[6]['locale'] = 'ar';
        $data[6]['slug'] = 'fruits-tab';
        $data[6]['title'] = 'الفواكه المجففة';
        $data[6]['image'] = '/photos/shares/miveh-khoshk.jpg';

        $data[7]['posttype_id'] = 2;
        $data[7]['parent_id'] = 0;
        $data[7]['locale'] = 'ar';
        $data[7]['slug'] = 'dired-herbs';
        $data[7]['title'] = 'الخضروات المجففة';
        $data[7]['image'] = '/photos/shares/sabzijat-khoshk.jpg';

        $data[8]['posttype_id'] = 2;
        $data[8]['parent_id'] = 0;
        $data[8]['locale'] = 'ar';
        $data[8]['slug'] = 'saffron';
        $data[8]['title'] = 'زعفران';
        $data[8]['image'] = '/photos/shares/saffron.jpg';

        $data[9]['posttype_id'] = 2;
        $data[9]['parent_id'] = 0;
        $data[9]['locale'] = 'ar';
        $data[9]['slug'] = 'pastry';
        $data[9]['title'] = 'معجنات';
        $data[9]['image'] = '/photos/shares/shirini.jpg';

        $data[10]['posttype_id'] = 2;
        $data[10]['parent_id'] = 0;
        $data[10]['locale'] = 'ar';
        $data[10]['slug'] = 'chocolate';
        $data[10]['title'] = 'شوكولاتة';
        $data[10]['image'] = '/photos/shares/shokolat.jpg';

        $data[11]['posttype_id'] = 2;
        $data[11]['parent_id'] = 0;
        $data[11]['locale'] = 'ar';
        $data[11]['slug'] = 'snack';
        $data[11]['title'] = 'وجبات خفيفة';
        $data[11]['image'] = '/photos/shares/snack.jpg';

        for ($i = 0; $i < count($data); $i++)
        {
            Folder::store($data[$i]);
        }

    }
}
