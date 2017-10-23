<?php
return [
    'types' => [
        'meta_hint'           => 'متاها را به صورت key:type بنویسید و با کامای انگلیسی (,) از هم جدا کنید. اگر نوع مشخص نشود، text در نظر گرفته می‌شود. نوع‌های مجاز: ',
        'delete_alert_posts'  => 'اصلاً به سرنوشت :count پستی که به این شاخه تعلق دارد فکر کرده‌ای عزیز جان؟',
        'delete_alert'        => 'این حذف به شیوه‌ی نرم انجام می‌شود، اما در داخل برنامه راهی برای بازیافت وجود ندارد.',
        'locales_hint'        => "زبان‌ها را با کامای انگلیسی (,) از هم جدا کنید. اگر هیچ چیز ننویسید، فقط زبان فارسی فعال می‌شود.",
        'order_hint'          => "اگر صفر بگذارید، به جای این که در منوی سمت راست مدیریت ظاهر شود، به منوی تنظیمات می‌رود.",
        'locale_titles'       => "عنوان در زبان‌های دیگر",
        'upload_configs_hint' => "تنظیمات مربوط به آپلود فایل و به صورت آرایه. اگر خالی باشد، آپلود فایل برای این گونه خاموش می‌شود.",
    ],

    'features' => [
        'history_system'    => "تاریخچه گردش کار",
        'full_history'      => "متن کامل در تاریخچه",
        'meaning'           => "ویژگی‌ها",
        'locales'           => "زبان‌های دیگر",
        'pin'               => "سنجاق",
        'slug'              => "تنظیم نامک",
        'download'          => "دانلود فایل",
        'abstract'          => "چکیده",
        'title'             => "عنوان",
        'title2'            => "عنوان دوم",
        'seo'               => "بررسی‌های سئو",
        'long_title'        => "عنوان خیلی طولانی",
        'text'              => "متن",
        'featured_image'    => "تصویر شاخص",
        'rss'               => "آراس‌اس",
        'price'             => "فیلد قیمت",
        'basket'            => "سبد خرید",
        'comment'           => "پذیرش دیدگاه",
        'rate'              => "امتیازدهی",
        'domains'           => "دامنه‌پذیری",
        'album'             => "فایل‌های ضمیمه",
        'category'          => "دسته‌بندی",
        'cat_image'         => "عکس دسته‌بندی",
        'searchable'        => "قابل جست‌وجو",
        'listable'          => "قابل فهرست شدن",
        'preview'           => "پیش‌نمایش در حین ایجاد",
        'digest'            => "نمایش در پیشخوان",
        'schedule'          => "تنظیم زمان انتشار",
        'keywords'          => "کلیدواژه",
        'register'          => "ثبت نام اشخاص",
        'event'             => "رویداد",
        'visibility_choice' => "امکان انتخاب رؤیت‌پذیری",
        'template_choice'   => "امکان انتخاب قالب",
        'developers_only'   => "انحصاری برنامه‌نویسان",
        'feedback'          => "بازخورد",
        'tags'              => 'برچسب‌ها',
        'similar_things'    => '<span>::things</span> مشابه',
        'related_things'    => '::things مرتبط',
        'maybe_you_like'    => '<span>شاید</span> علاقمند باشید',
        'dont_miss'         => 'از دست ندهید',
    ],

    'templates' => [
        'post'      => "مطلب",
        'album'     => "آلبوم",
        'slideshow' => "اسلایدشو",
        'dialogue'  => "گفت‌وگو",
        'faq'       => "پرسش و پاسخ",
        'product'   => "محصول فروشگاهی",
        'event'     => "رویداد",
        'special'   => "ویژه",
    ],

    'categories' => [
        'meaning'                  => "دسته‌بندی‌ها",
        'folder'                   => "پوشه",
        'folders'                  => "پوشه‌ها",
        'new_folder'               => "افزودن پوشه جدید",
        'new_category'             => "افزودن دسته جدید",
        'folder_delete_notice'     => "دسته‌بندی‌های زیرمجموعه، بدون پوشه خواهند شد.",
        'no_folder'                => "بدون پوشه‌ها",
        'category_enabled_content' => "محتوای قابل دسته‌بندی",
    ],

    'criteria' => [
        'all'       => "همه",
        'published' => "منتشرشده‌ها",
        'scheduled' => "صف انتشار",
        'pending'   => "منتظر تأیید",
        'drafts'    => "پیش‌نویس‌ها",
        'my_posts'  => "نوشته‌های من",
        'my_drafts' => "پیش‌نویس‌های من",
        'bin'       => "زباله‌دان",
        'approved'  => "تأییدشده‌ها",
        'private'   => "خصوصی‌ها",
    ],

    'visibility' => [
        'title'   => "رؤیت‌پذیری",
        'limited' => "محدود",
        'public'  => "عمومی",
    ],

    'form' => [
        'global'                              => 'سراسری',
        'copy'                                => "رونوشت",
        'copy_of'                             => "رونوشت از",
        'published_post'                      => "مطلب منتشر شده",
        'publish'                             => "انتشار",
        'approved_post'                       => "مطلب پذیرفته‌شده",
        'copy_status_hint'                    => "در حال دستکاری یک رونوشت هستید.",
        'title_placeholder'                   => "عنوان را اینجا وارد کنید",
        'title2_placeholder'                  => "عنوان دوم را در صورت نیاز اینجا وارد کنید",
        'add_second_title'                    => "افزودن عنوان دوم",
        'save_draft'                          => "ذخیره پیش‌نویس",
        'preview'                             => "پیش‌نمایش",
        'rejected'                            => "مردود",
        'view_in_site'                        => "نمایش در سایت",
        'publish'                             => "انتشار",
        'save_and_publish'                    => "ذخیره‌سازی و انتشار",
        'update_button'                       => "به روز رسانی",
        'send_for_approval'                   => "ارسال به سردبیر",
        'adjust_publish_time'                 => "تنظیم زمان انتشار",
        'refer_back'                          => "بازگشت به نویسنده (رد)",
        'refer_to'                            => "ارجاع به شخص دیگر",
        'referred_to_target'                  => "ارجاع داده شد به «:target».",
        'unpublish'                           => "لغو انتشار",
        'delete'                              => "انتقال به زباله‌دان",
        'history'                             => "تاریخچه",
        'discard_schedule'                    => "به صورت خودکار تنظیم شود",
        'is_available'                        => "به تعداد کافی",
        'is_not_available'                    => "کسر موجودی (توقف فروش)",
        'sale_settings'                       => "تنظیم فروش ویژه",
        'sale_panel'                          => "فروش ویژه",
        'template'                            => "قالب نمایش",
        'options'                             => "گزینگان",
        'this_page'                           => "همین صفحه",
        'delete_alert_for_unsaved_post'       => "این نوشته ذخیره نشده و آنچه نوشته‌اید برای همیشه از بین می‌رود.",
        'delete_alert_for_copies'             => "شما در حال دستکاری یک رونوشت هستید. همین رونوشت را به زباله‌دان می‌فرستید؟",
        'delete_alert_for_published_post'     => "این مطلب منتشر شده است. پس از انتقال به زباله‌دان، از دسترس کاربران خارج می‌شود.",
        'delete_this_copy'                    => "همین رونوشت پاک شود",
        'unpublish_warning'                   => "لغو انتشار، مطلب را از دسترس کاربران خارج می‌کند و به حالت منتظر تأیید درمی‌آورد.",
        'sure_unpublish'                      => "مطمئنم! لغو انتشار!",
        'delete_original_post'                => "نوشته اصلی پاک شود",
        'slug'                                => "نامک (فقط حروف انگلیسی)",
        'valid_slug'                          => "احتمالاً مورد قبول است.",
        'invalid_slug'                        => "این نامک قبول نیست.",
        'slug_will_be_changed_to'             => "به :approved_slug تغییرش می‌دهیم.",
        'no_slug'                             => "نوشته بدون نامک، هیچ اشکالی ندارد.",
        'discount_percent_in_parentheses'     => "(:percent٪ تخفیف)",
        'quick_edit'                          => "ویرایش مختصر",
        'clone'                               => "کپی‌برداری",
        'make_a_clone'                        => "کپی را بساز",
        'make_a_clone_and_save_to_drafts'     => "فقط کپی را بساز",
        'make_a_clone_and_get_me_there'       => "کپی را بساز و صفحه‌اش را باز کن",
        'clone_made_feedback'                 => "کپی را ساختیم و به عنوان پیش‌نویس شما ذخیره کردیم.",
        'clone_is_a_sister'                   => "این کپی، ترجمه‌ای از همین مطلب کنونی‌ست.",
        'translation_already_made'            => "ترجمه با این زبان وجود دارد.",
        'approval'                            => "پذیرش",
        'deleted_post'                        => "مطلب پاک‌شده",
        'automatically_change_english_digits' => "ارقام متن، به فارسی تبدیل شوند",
        'post_creator'                        => "سازنده مطلب",
        'post_owner'                          => "مالک مطلب",
        'new_post_owner'                      => "مالک جدید",
        'change_post_owner'                   => "تغییر مالک مطلب",
        'copy_suggestion_when_cannot_publish' => "این مطلب مورد پذیرش سردبیر قرار گرفته و ویرایش امکان‌پذیر نیست، مگر آن که رونوشتی از آن تهیه کنید.",
        'copy_suggestion_when_can_publish'    => "این مطلب نهایی شده است. برای تغییرات گسترده، بهتر است رونوشتی از آن تهیه کنید.",
        'copy_suggestion_deny'                => "همیشه می‌توانید با دکمه‌ی «ذخیره پیش‌نویس» همین کار را بکنید.",
        'reflect_in_global'                   => "انعکاس در دامنه‌ی سراسری",
        'reflect_in_global_short'             => "انعکاس سراسری",
        'new_content'                         => "محتوای جدید",
        'create_new_post'                     => "ایجاد مطلب جدید",
        'for_each_number_of'                  => "برای هر تعداد :name",
        'thumb_sizes'                         => "اندازه تصاویر بندانگشتی",
        'thumb_sizes_hint'                    => "در هر سطر، اول طول و بعد ارتفاع عکس، وسطشان x، همه به انگلیسی",
        'gallery_thumb_size'                  => 'اندازه بندانگشتی آلبوم',
        'gallery_thumb_size_hint'             => "اول طول و بعد ارتفاع عکس، وسطش x، همه به انگلیسی",
        'info'                                => "اطلاعات",
        'rss_title'                           => "فید",
    ],

    'comments' => [
        'singular'               => "دیدگاه",
        'plural'                 => "دیدگاه‌ها",
        'users_comments'         => "دیدگاه کاربران",
        'reply'                  => "پاسخ",
        'replies'                => "پاسخ‌ها",
        'dialogue'               => "گفت‌وگو",
        'dialogue_with_number'   => "گفت‌وگو (:number)",
        'one_of_replies'         => "یکی از :number پاسخ",
        'process'                => "اقدام روی دیدگاه",
        'reply_via_email_too'    => "پاسخ، از طریق ایمیل نیز ارسال شود",
        'reply_or_change_status' => "درج پاسخ جدید یا تغییر وضعیت",
    ],

    'album' => [
        'singular'          => "آلبوم عکس",
        'add_photo'         => "افزودن عکس جدید",
        'label_placeholder' => 'عنوان عکس ـ‌ اختیاری',
        'link_placeholder'  => 'لینک مرجع ـ اختیاری',
        'remove'            => 'حذف این عکس',
    ],

    'files' => [
        'title'             => "فایل‌های ضمیمه",
        'add_file'          => "افزودن فایل جدید",
        'label_placeholder' => 'عنوان فایل ـ‌ اختیاری',
        'link_placeholder'  => 'لینک مرجع ـ اختیاری',
        'remove'            => 'حذف این فایل',
    ],

    'filters' => [
        'filters'       => 'فیلتر‌ها',
        'no_category'   => 'بدون دسته‌بندی',
        'available'     => 'موجود',
        'special_sale'  => 'فروش ویژه',
        'reset_filters' => 'تخلیه فیلترها',
        'range_from'    => 'از',
        'range_to'      => 'تا',
    ],

    'packs' => [
        'plural'            => "بسته‌های تحویل کالا",
        'single'            => "بسته",
        'add'               => "افزودن بسته جدید",
        'edit'              => "ویرایش بسته",
        'activate'          => "فعال کردن دوباره",
        'deactivate'        => "غیر فعال کردن",
        'deactivate_notice' => "غیر فعال کردن، این بسته‌بندی را از دسترس خارج خواهد ساخت.",
    ],

    'pin' => [
        'put_command'    => "ثبت سنجاق",
        'remove_command' => "حذف سنجاق",
        'description'    => "مطلب سنجاق‌شده، در بالای مطالب دیگر نمایش داده می‌شود.",
        'put_alert'      => "سنجاق کنونی از بین می‌رود.",
        'remove_alert'   => "دیگر سنجاقی در کار نخواهد بود.",
        'pinned'         => "سنجاق‌شده",
    ],
];