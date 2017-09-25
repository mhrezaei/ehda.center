<?php

/*
|--------------------------------------------------------------------------
| Front Side
|--------------------------------------------------------------------------
|
*/



Route::group(['namespace' => 'Front', 'middleware' => ['Setting']], function () {

    // if not set lang prefix
    Route::get('/', function (){
        return redirect('https://ehda.center');
    })->name('home');

    // organ donation card
    Route::get('/card/show_card/{type}/{user_hash_id}/{mode?}/{hash_type?}', 'OrganDonationCardController@index');
});