<?php
return [
	'purchases'             => "خریدها",
	'purchase'              => "خرید",
	'receipts'              => "رسیدهای خرید",
	'receipt'               => "رسید خرید",
	'no_receipt'            => "بدون رسید",
	'no_registered_receipt' => "فعلاً هیچ رسیدی ثبت نشده.",
	'add_new_receipt'       => "کد قرعه‌کشی جدید را اینجا وارد کنید",
	'total:'                => "در کل:",
	'invalid_purchase_code' => "کد قرعه‌کشی اشتباه است.",
	'receipts_count_amount' => ":count رسید خرید (در کل :amount تومان)",
	'draw'                  => "قرعه‌کشی",
	'draw_prepare'          => "آماده‌سازی برای قرعه‌کشی",
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