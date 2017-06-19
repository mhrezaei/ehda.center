<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class SecKeyServiceProvider extends ServiceProvider
{
    private $question;
    private $key;
    private $mask;
    private $lang;
    private $answer;
    private $n1;
    private $n2;
    private $c1;
    private $c2;
    private $session;

    public function register()
    {
    }

    public function generate($lang = 'fa')
    {
        $this->lang = strtolower($lang);

        $this->n1 = rand(1, 10);
        $this->n2 = rand(1, 10);
        $this->c1 = $this->n2c($this->n1);
        $this->c2 = $this->n2c($this->n2);

        if (!$this->lang) $this->lang = "fa";

        $this->makeUniqueKey();
        $this->makeQuestion();
        $this->loadToSession();

        return [
            'question' => $this->question,
            'key'      => $this->key,
        ];
    }

    private function loadToSession()
    {
        $key = "secKey" . $this->key;
        Session::put($key, $this->mask);
    }

    private function makeUniqueKey()
    {
        while (true) {
            $key = rand(1, 32000);
            if (!Session::has($key)) break;
        }

        $this->key = $key;
    }

    private function makeQuestion()
    {
        $choice = rand(1, 2);

        switch ($choice) {
            case 1 :
                $answer = $this->n1 + $this->n2;
                break;
            case 2 :
                $answer = $this->n1 * $this->n2;
                break;

        }

        switch ($this->lang) {
            case "fa" :
                $choice = str_replace(1, " به‌علاوه‌ی ", $choice);
                $choice = str_replace(2, " ضرب‌در ", $choice);
                $sign = " چند می‌شود؟ ";
                break;

            case "en" :
                $choice = str_replace(1, "plus", $choice);
                $choice = str_replace(2, "multiplied by", $choice);
                $sign = "?";
                break;
        }

        $this->question = $this->c1 . " $choice " . $this->c2 . $sign;
        $this->answer = $answer;
        $this->mask = md5($answer);
    }

    private function n2c($number)
    {

        switch ($this->lang) {
            case "fa" :
                $number = str_replace(10, "ده", $number);
                $number = str_replace(0, "صفر", $number);
                $number = str_replace(1, "یک", $number);
                $number = str_replace(2, "دو", $number);
                $number = str_replace(3, "سه", $number);
                $number = str_replace(4, "چهار", $number);
                $number = str_replace(5, "پنج", $number);
                $number = str_replace(6, "شش", $number);
                $number = str_replace(7, "هفت", $number);
                $number = str_replace(8, "هشت", $number);
                $number = str_replace(9, "نه", $number);
                break;

            case "en" :
                $number = str_replace(10, "ten", $number);
                $number = str_replace(0, "zero", $number);
                $number = str_replace(1, "one", $number);
                $number = str_replace(2, "two", $number);
                $number = str_replace(3, "three", $number);
                $number = str_replace(4, "four", $number);
                $number = str_replace(5, "five", $number);
                $number = str_replace(6, "six", $number);
                $number = str_replace(7, "seven", $number);
                $number = str_replace(8, "eight", $number);
                $number = str_replace(9, "nine", $number);
                break;
        }


        return $number;

    }

    public static function getQuestion($lang = 'fa')
    {

        $ME = new SecKeyServiceProvider(app());

        return $ME->generate(strtolower($lang));
    }

    public static function checkAnswer($givenValue, $givenKey)
    {
        return true;
//        dd($givenValue, $givenKey);
        //Receiving....
        $givenValue += 0;
        $givenKey += 0;

        //Interlock...
        if (!$givenValue || !$givenKey) return false;

        //Checking...
        $key = "secKey" . $givenKey;

        if (Session::get($key, false) == md5($givenValue))
            return true;
        else
            return false;

    }

    public static function destroy($key)
    {
        Session::forget($key);
    }

}
