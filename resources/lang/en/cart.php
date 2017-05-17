<?php
return [
    'purchases'             => "Purchases",
    'purchase'              => "Purchase",
    'receipts'              => "Receipts",
    'receipt'               => "Receipt",
    'no_receipt'            => "No Receipt",
    'no_registered_receipt' => "No Receipts Yet",
    'add_new_receipt'       => "Add new receipt.",
    'total:'                => "Total:",
    'invalid_purchase_code' => "Invalid Purchase Code",
    'receipts_count_amount' => ":count receipts (:amount Tomans In Total)",
    'draw'                  => "draw",
    'draw_prepare'          => "Preparing for Draw",
    'redraw_prepare'        => "Draw Again",
    'reverse_results'       => "Reverse Draw Results",
    'drawing_winner'        => "Drawing Winner",
    'take_number_between'   => "Take a number from 1 to :number.",
    'random_number'         => "Random Number",
    'user_already_won'      => "This user has already won in this event.",
    'no_winner_so_far'      => "فعلاً هیچ کس به عنوان برنده انتخاب نشده است.",
    'no_winner_so_far'      => "No Winners Yet",
    'number'                => 'number',
    'add_to_cart'           => 'Add To Cart',
    'unit_price'            => 'Unit Price', 
    'total_price'           => 'Total Price', 
    'settlement'            => 'Settlement', 
    'empty_cart'            => 'Empty Cart', 
    'you_have_a_coupon?'    => 'You have a coupon?', 
    'discount_code'         => 'Discount Code', 
    'invalid_discount_code' => 'Invalid Code', 
    'your_total_payable'    => 'Your Total Payment', 
    'payable'               => 'Payable', 
    'cart_is_empty'         => 'Cart is empty.', 
    'back_to_market'        => 'Back to Market', 

    'units' => [
        'kilogram' => 'Kilogram',
        'package'  => 'Package',
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