<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DummyServiceProvider extends ServiceProvider
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

	public static function integer($min = 1, $max = 1000)
	{
		return rand($min , $max);
	}

	public static function persianWord($words = 1)
	{
		$array = array_filter(explode(' ' , self::persianText() ));
		$result = "" ;
		for($i=1 ; $i<=$words ; $i++) {
			$word = $array[ rand(0,sizeof($array)-1) ] ;
			if(strlen($word)<5) {
				$i-- ;
				continue ;
			}
			$result .= " ".$word ;
		}

		return $result ;

	}

	public static function persianText($paragraphs = 1)
	{
		$text = "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی است. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می‌طلبد تا با نرم‌افزارها شناخت بیشتری را برای طراحان رایانه‌ای علی‌الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می‌توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد." ;
		$result = null ;
		for($i=1 ; $i<=$paragraphs ; $i++) {
			$result .= "\r\n".$text ;
		}

		return $result ;

	}

	public static function englishText($paragraphs = 1)
	{
		$text = 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
		$result = null ;
		for($i=1 ; $i<=$paragraphs ; $i++) {
			$result .= "\r\n".$text ;
		}

		return $result ;

	}

	public static function englishWord($words = 1)
	{
		$array = array_filter(explode(' ' , self::englishText() ));
		$result = "" ;
		for($i=1 ; $i<=$words ; $i++) {
			$word = $array[ rand(0,sizeof($array)-1) ] ;
			if(strlen($word)<5) {
				$i-- ;
				continue ;
			}
			$result .= " ".$word ;
		}

		return $result ;

	}
}
