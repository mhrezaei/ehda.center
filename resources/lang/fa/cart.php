<?php
return [
    'purchases' => "خریدها",
    'purchase' => "خرید",
    'receipts' => "رسیدهای خرید",
    'receipt' => "رسید خرید",
    'no_receipt' => "بدون رسید",
    'no_registered_receipt' => "فعلاً هیچ رسیدی ثبت نشده.",
    'add_new_receipt' => "کد قرعه‌کشی جدید را اینجا وارد کنید",
    'total:' => "در کل:",
    'invalid_purchase_code' => "کد قرعه‌کشی اشتباه است.",
    'receipts_count_amount' => ":count رسید خرید (در کل :amount تومان)",
    'draw' => "قرعه‌کشی",
    'draw_prepare' => "آماده‌سازی برای قرعه‌کشی",
    'redraw_prepare' => "قرعه‌کشی دوباره",
    'reverse_results' => "معکوس‌سازی نتایج قرعه‌کشی",
    'drawing_winner' => "برنده قرعه‌کشی",
    'drawing_winners' => "برندگان قرعه‌کشی",
    'drawing_video' => "ویدئو قرعه‌کشی",
    'take_number_between' => "عددی را بین ۱ تا :number انتخاب کنید",
    'random_number' => "عدد شانسی",
    'user_already_won' => "این کاربر یک بار در همین رویداد برنده شده است!",
    'no_winner_so_far' => "فعلاً هیچ کس به عنوان برنده انتخاب نشده است.",
    'number' => 'تعداد',
    'add_to_cart' => 'افزودن به سبد',

    'units' => [
        'kilogram' => 'کیلوگرم',
        'package' => 'بسته',
    ]
];

/*  Drawing Sequence
------------------------
	√    select row and make sure it is an event
	-    update `operation_integer` on all receipts to 0
	-    update `operation_integer` and `operation_string` on all users to 0
	-    update `operation_integer` on the relevant receipts to 1
	-    calculate each user purchase and put on `operation_integer`
	-    make numbers for each user and put on `operation_string`
	-    search the final number in `operation_string`

*/