<?php
return [
	'person' => "نفر",
	'people' => "اشخاص",
	'modules' => "ماژول‌ها",
	'deleted_user' => "یک کاربر سابق",
	'user' => "کاربر",
	'users' => "کاربران",
	'site_users' => "کاربران مجموعه",
	'user_role' => "نقش کاربری",
	'without_role' => "بدون نقش",

	'commands' => [
		'permit' => "نقش‌ها و مجوزها",
		'change_password' => "تغییر گذرواژه",
		'block' => "مسدودسازی",
		'unblock' => "رفع مسدودی",
		'login_as' => "لاگین به جای ایشان",
	     'create_new_user' => "افزودن :role_title جدید",
	     'all_users' => "همه کاربران",
	],

	'admins' => [
		'title' => "مدیران مجموعه",
		'create' => "افزون مدیر جدید",
		'super_admin' => "مدیر کل",
		'ordinary_admin' => "مدیر عملیات",
		'developer' => "برنامه‌نویس",
	],

	'criteria' => [
		'all' => "همه" ,
		'actives' => "فعال‌ها",
		'pending' => "منتظر تأیید",
	     'banned' => "مسدودها",
	     'bin' => "زباله‌دان",
	],

	"form" => [
			"deleted_person" => '[؟]' ,
			"notify-with-email" => 'به کاربر از طریق ایمیل اطلاع‌رسانی شود.' ,
			"notify-with-sms" => 'به کاربر از طریق پیامک اطلاع‌رسانی شود.' ,
			"notify" => 'به کاربر از طریق پیامک و ایمیل اطلاع‌رسانی شود.' ,
			"will-be-notified" => 'به کاربر از طریق پیامک و ایمیل اطلاع‌رسانی می‌شود.' ,
			"default_password" => 'شماره‌ی تلفن همراه به عنوان گذرواژه در نظر گرفته می‌شود و کاربر در اولین ورود ملزم به تغییر خواهد بود.' ,
			"hard_delete_notice" => 'این حذف غیر قابل بازگشت خواهد بود.' ,
			'delete_notice_when_has_role' => "تمام نقش‌های این کاربر از دسترس خارج خواهند شد.",
			"password_hint" => 'حداقل هشت کاراکتر حساس به کوچکی و بزرگی حروف. تمام ارقام به انگلیسی تبدیل می‌شوند.' ,
	          'password_change_sms' => "رمز عبور شما در سایت :site_title تغییر یافت: :new_password",
	          'as_a' => "به عنوان :role_title",
	          'now_without' => "اکنون چنین نقشی ندارد.",
	          'now_active' => "اکنون در این نقش فعال است.",
	          'now_blocked' => "اکنون این نقش را دارد، ولی مسدود است.",
	          'detach_this_role' => "حذف این نقش",
	          'attach_this_role' => "افزودن این نقش",
	],
];