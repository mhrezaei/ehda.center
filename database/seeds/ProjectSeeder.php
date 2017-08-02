<?php
// @TODO: Delete this file

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		/*--------------------------------------------------------------------------
		| Users ...
		*/
		DB::table('users')->insert([
			[
				'code_melli' => "0074715623",
				'email' => "chieftaha@gmail.com",
				'name_first' => "طاها",
				'name_last' => "کامکار",
				'password' => bcrypt('11111111'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'code_melli' => "0012071110",
				'email' => "mr.mhrezaei@gmail.com",
				'name_first' => "محمدهادی",
				'name_last' => "رضایی",
				'password' => bcrypt('11111111'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'code_melli' => "",
				'email' => "admin@yasnateam.com",
				'name_first' => "ادمین",
				'name_last' => "یسنا",
				'password' => bcrypt('11111111'),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			]
		]);

		/*-----------------------------------------------
		| Posttypes ...
		*/
		
		

		DB::table('posttypes')->insert([
			[
				'slug' => "pages",
				'order' => "1",
				'title' => "برگه‌ها",
				'header_title' => "",
				'features' => "featured_image title text comment gallery visibility_choice searchable template_choice preview keyword",
				'meta' => json_encode([
					'singular_title' => "برگه",
					'template' => "post",
					'icon' => "file-o",
				]),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "products",
				'order' => "2",
				'title' => "محصولات",
				'header_title' => "",
				'features' => "title text featured_image  rss    rate  album  category  keywords  searchable  preview        basket  digest  schedule comment     price  download abstract  seo template_choice  slug  title2",
				'meta' => json_encode([
					'singular_title' => "محصول",
					'template' => "product",
					'icon' => "gift",
					'feature_meta' => "sale_price:text, sale_expires_at:text, package_id:text , download_file:file, abstract:text, seo_status:text, title2:text",
					'locales' => "fa,en,ar",
					'optional_meta' => "edu_city:text , home_address:textarea , home_tel:text", //@TODO: Remove this line on production
				]),
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
		]);

		/*-----------------------------------------------
		| Settings ...
		*/
		
		

		DB::table('settings')->insert([
			[
				'slug' => "site_title",
				'title' => "عنوان سایت",
				'category' => "upstream",
				'data_type' => "text",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('یسناوب') ,
				'default_value' => 'یسناوب' ,
				'developers_only' => 1,
				'is_resident' => 1,
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "site_locales",
				'title' => "زبان‌های سایت",
				'category' => "upstream",
				'data_type' => "array",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('fa') ,
				'default_value' => "fa\r\nen" ,
				'developers_only' => 1,
				'is_resident' => 1,
//				'is_sensitive' => "true",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "site_activeness",
				'title' => "فعالیت سایت",
				'category' => "template",
				'data_type' => "boolean",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('1') ,
				'default_value' => '1' ,
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "overall_activeness",
				'title' => "فعالیت هسته‌ی سایت",
				'category' => "upstream",
				'data_type' => "boolean",
				'default_value' => '1' ,
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('1') ,
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "ssl_available",
				'title' => "آمادگی اس‌اس‌ال",
				'category' => "upstream",
				'data_type' => "boolean",
//				'default_value' => \Illuminate\Support\Facades\Crypt::encrypt('0') ,
				'default_value' => '0' ,
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "currency",
				'title' => "واحد پول",
				'category' => "template",
				'data_type' => "text",
				'default_value' => 'تومان' ,
				'developers_only' => 0,
				'is_resident' => "1",
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "site_logo",
				'title' => "لوگوی سایت",
				'category' => "template",
				'data_type' => "photo",
				'default_value' => 'assets/images/yasnateam-logo.png',
				'developers_only' => 0,
				'is_resident' => "1",
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "address",
				'title' => "آدرس",
				'category' => "contact",
				'data_type' => "textarea",
				'default_value' => 'تهران، پلاگ 1',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "1",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "telephone",
				'title' => "تلفن تماس",
				'category' => "contact",
				'data_type' => "array",
				'default_value' =>"02122222222\r\n021333333331",
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "email",
				'title' => "ایمیل",
				'category' => "contact",
				'data_type' => "array",
				'default_value' => "someone@somewhere.com\r\nname@website.com",
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "telegram_link",
				'title' => "تلگرام",
				'category' => "contact",
				'data_type' => "text",
				'default_value' => 'http://telegram.me/account',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "twitter_link",
				'title' => "توییتر",
				'category' => "contact",
				'data_type' => "text",
				'default_value' => 'http://twitter.com/account',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "facebook_link",
				'title' => "فیسبوک",
				'category' => "contact",
				'data_type' => "text",
				'default_value' => 'http://fb.com/account',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "instagram_link",
				'title' => "اینستاگرام",
				'category' => "contact",
				'data_type' => "text",
				'default_value' => 'http://instagram.com/account',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "aparat_link",
				'title' => "آپارات",
				'category' => "contact",
				'data_type' => "text",
				'default_value' => 'http://aparat.com/account',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "location",
				'title' => "موقعیت مکانی",
				'category' => "contact",
				'data_type' => "array",
				'default_value' => "35.7448\r\n51.3753",
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "receiving_comments",
				'title' => "دریافت نظرات",
				'category' => "contact",
				'data_type' => "boolean",
				'default_value' => '1',
				'developers_only' => 0,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "password_token_expire_time",
				'title' => "مدت اعتبار کد بازیابی گذرواژه (دقیقه)",
				'category' => "database",
				'data_type' => "text",
				'default_value' => '30',
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "site_url",
				'title' => "آدرس سایت",
				'category' => "database",
				'data_type' => "text",
				'default_value' => url(''),
				'developers_only' => 1,
				'is_resident' => "0",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'slug' => "default_role",
				'title' => "نقش کاربری پیش‌فرض",
				'category' => "template",
				'data_type' => "text",
				'default_value' => 'member',
				'developers_only' => 1,
				'is_resident' => "1",
				'is_localized' => "0",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
		]);

		/*-----------------------------------------------
		| Roles ...
		*/
		
		

		DB::table('roles')->insert([
			[
				'slug' => "manager",
				'title' => "مدیر",
				'plural_title' => "مدیران",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
				'is_admin' => "1" ,
				'modules' => json_encode([
						'posts' => ['create','edit','publish','report','delete','bin'] ,
				]),
			],
			[
				'slug' => "member ",
				'title' => "کاربر",
				'plural_title' => "کاربران",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
				'is_admin' => "0" ,
				'modules' => null,
			],
		]);

		DB::table('role_user')->insert([
			[
				'user_id' => 1,
				'role_id' => 1,
				'permissions' => "super",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'user_id' => 2,
				'role_id' => 1,
				'permissions' => "super",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
			[
				'user_id' => 3,
				'role_id' => 1,
				'permissions' => "super",
				'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
			],
		]);

		/*-----------------------------------------------
		| Packages ...
		*/
		DB::table('units')->insert([
			[
				'slug' => "numbers",
				'title' => "عدد",
				'is_continuous' => false,
			],
			[
				'slug' => "packs",
				'title' => "بسته",
				'is_continuous' => false,
			],
			[
				'slug' => "gr",
				'title' => "گرم",
				'is_continuous' => true,
			],
			[
				'slug' => "kg",
				'title' => "کیلوگرم",
				'is_continuous' => true,
			],
		]);
		

	}
}
