<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class DummyServiceProvider extends ServiceProvider
{
	protected static $persian_text = "لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی است. کتابهای زیادی در شصت و سه درصد گذشته، حال و آینده شناخت فراوان جامعه و متخصصان را می‌طلبد تا با نرم‌افزارها شناخت بیشتری را برای طراحان رایانه‌ای علی‌الخصوص طراحان خلاقی و فرهنگ پیشرو در زبان فارسی ایجاد کرد. در این صورت می‌توان امید داشت که تمام و دشواری موجود در ارائه راهکارها و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی و جوابگوی سوالات پیوسته اهل دنیای موجود طراحی اساسا مورد استفاده قرار گیرد." ;
	protected static $english_text = 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.';
	protected static $persian_titles = [
			'اعتراض کسبه بازار رضا به حضور ماموران برای جمع آوری نقره‌های قاچاق' ,
			'چهل روز بعد از حادثه پلاسکو، مردم و مسئولان چه می‌گویند',
			'بیشتر بازیکنان این تیم پناهندگان تبتی هستند',
			'فرمان مهاجرتی ترامپ سفر شهروندان هفت کشور به آمریکا را به طور موقت محدود کرده است',
			'دادگاه ادعا این مردان را رد کرد',
			'این حمله در شب سال نو روی داد',
			'بال پهپادها به فضای مبارزه با فساد هم باز شد' ,
			'دموکرات‌ها در آمریکا: وزیر دادگستری از مقامش استعفا کند' ,
			'درگیری‌ دو گروه کرد در شمال غرب عراق',
			'فرماندهان پلیس کابل و چند شهر دیگر عوض شدند',
			'جف بزوس حالا در اندیشه تحویل کالا در ماه است',
			'پشتیبانی از لاک اسکرین به نسخه اندروید دستیار کورتانا اضافه شد',
			'دسترسی به دستیار گوگل در پیام رسان Allo تسهیل شد',
			'هفت سنگ؛ خیزش شیاطین',
			'تاریخ عرضه نسخه پی سی و مشخصات سخت افزاری مورد نیاز',
			'روزیاتو: ۹ مورد از سنگین وزن ترین رکوردهای جهانی گینس که شما را متعجب خواهند کرد',
			'دادگاه تاریخی مدیر عامل سامسونگ نوزدهم اسفند ماه برگزار می گردد',
			'آمازون در حال توسعه یک دوربین امنیتی خانگی است',
			'توییتر و برداشتن گام های تازه برای محدود کردن فعالیت اکانت هایی با محتوای آزاردهنده',
			'سامسونگ و تاسیس دفتری تازه برای کنترل کیفیت هرچه بیشتر محصولات',
			'دستگاهی که برای جابز حکم سرگرمی را داشت، به لیست از رده خارج شده های اپل پیوست',
			'گوگل برنامه ای برای تولید موبایل پیکسل ارزان قیمت ندارد',
			'کمپانی لنوو تصمیم به حفظ نام و برند موتورولا گرفت',
			'سامسونگ و ترغیب تولیدکنندگان برای بهره گیری از چیپست اگزینوس در هدست های واقعیت مجازی',
			'ساختمان مرکزی میونیک ری، در مونیخ',
			'بازوی ارائه خدمات اولیه بیمه فعالیت می‌نماید',
			'با  حضور سرپرست اداره کل آموزش و پرورش و مدیران بیمه',
			'برای خداوند هیچ کاری غیر ممکن نیست',
			'در تمام زندگی به او اعتماد کن و در حضورش بمان و امیدت به او باشد',
			'صبح بخیر و شادی',
			'اجلاس مدیریت پسماند و حفظ محیط زیست ایران و آلمان',
			'حضور شرکت سهامی بیمه',
			'سقوط پل در تهران',
			'برگزاری دوره آموزشی سیستم رسیدگی به شکایات مشتریان در شرکت بیمه حافظ',
			'ناتوانی صنعت بیمه در پوشش اقتصاد ایران',
			'سفر مدیرعامل بیمه‌ کوثر به استان قزوین',
			'گزارشی از پرونده بیمه توسعه از زبان مدیریت امور حقوقی شرکت بیمه ایران',
			'دفاتر اداری مت‌لایف، در برج مت‌لایف و ساختمان شمالی، نیویورک',
			'اظهارات تند و تیز وزیر اقتصاد درباره فساد اقتصادی',
			'پرودنشال پی‌ال‌سی',
			'کانال گروه اخبار بیمه گران',
			'انفجار در خط تغذیه گاز نیروگاه برق علی‌آباد کتول',
			'رشد سریعتر صنعت بیمه نسبت به کل اقتصاد',
			'سخنرانی دکتر طباطبایی ',
			'درس گفتارهایی درباره افلاطون',
			'سیر تاریخی تحول فهم قرآن',
			'سخنرانی هاشم آقاجری، کریم سلیمانی، محمد مالجو',
			'حق انتقاد در حكومت اسلامي',
			'سخنرانی مصطفی ملکیان',
			'سلامت روان یا انسان خودشکوفا',
			'سخنرانی استاد علی شریعتی ',
			'ویدیوی بازی فوتبال فیلسوفان بزرگ جهان',
			'صحبت های قابل تامل  محمد رضا',
			'شیوه‌های تغییر رفتار در بزرگسالی',
			'سلسله سخنرانی های دکتر فرنودی',
			'این سرزمین مال کیست؛ اسرائیلیان یا فلسطینی‌ها؟',
			'منصور فرهنگ ‌استاد علوم سیاسی ',
			'فلسفه در یونان و روم باستان',
	];



	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{

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
//		$array = array_filter(explode(' ' , self::persianTitle () ));
		$source = self::$persian_titles ;
		$array = [] ;
		foreach($source as $item) {
			$array = array_merge($array , array_filter(explode(' ',$item)));
		}

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

	public static function persianTitle()
	{
		$array = self::$persian_titles ;
		$index = rand(0 , sizeof($array)-1);
		return $array[$index] ;
	}

	public static function persianName()
	{
		$array = [] ;
		$index = rand(0 , sizeof($array)-1);
		return $array[$index] ;
	}

	public static function persianText($paragraphs = 1)
	{
		$text = self::$persian_text ;
		$result = null ;
		for($i=1 ; $i<=$paragraphs ; $i++) {
			$result .= "\r\n".$text ;
		}

		return $result ;

	}

	public static function englishText($paragraphs = 1)
	{
//		$result = "" ;
//		for($i=1 ; $i<=$paragraphs ; $i++) {
//			Artisan::call("inspire") ;
//			$result .= "\r\n". Artisan::output() ;
//		}
//		return $result ;

		$text = self::$english_text ;
		$result = null ;
		for($i=1 ; $i<=$paragraphs ; $i++) {
			$result .= "\r\n".$text ;
		}

		return $result ;

	}

	public static function englishWord($words = 1)
	{
		$result = "" ;
		for($i=1 ; $i<=$words ; $i++) {
			Artisan::call("inspire");
			$array = array_filter(explode(' ' , Artisan::output() ));

			$word = $array[ rand(0,sizeof($array)-1) ] ;
			if(strlen($word)<5) {
				$i-- ;
				continue ;
			}
			$result .= " ".$word ;
		}

		return $result ;

	}

	public static function email()
	{
		$email = null ;
		while(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$email = self::englishWord()."@".self::englishWord() ;
			$forbidden_chars = ["'" , '"' , "/" , "\\" , "," , "." , " "] ;
			foreach($forbidden_chars as $forbidden_char) {
				$email = str_replace($forbidden_char , null , $email) ;
			}

			$email = strtolower($email.".com") ;
		}

		return $email ;
	}
}
