<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
*/

Auth::routes();
Route::get('home', 'Auth\LoginController@redirectAfterLogin');
Route::get('logout', 'Auth\LoginController@logout');

/*
|--------------------------------------------------------------------------
| Manage Side
|--------------------------------------------------------------------------
|
*/
Route::group([
	'prefix' => "manage",
	'middleware' => ['auth' , 'is:admin'],
	'namespace' => "Manage",
], function() {
	Route::get('/' , 'HomeController@index');
	Route::get('/index' , 'HomeController@index');

	/*
	| Admins
	*/

	Route::group(['prefix'=>'admins', 'middleware' => 'is:super'] , function() {
		Route::get('/update/{item_id}' , 'AdminsController@update');
		Route::get('/' , 'AdminsController@browse') ;
		Route::get('/browse/{request_tab?}' , 'AdminsController@browse') ;
		Route::get('/create/' , 'AdminsController@editor') ;
		Route::get('/search' , 'AdminsController@search');
		Route::get('/{user_id}/{modal_action}' , 'AdminsController@modalActions');

		Route::group(['prefix'=>'save'] , function() {
			Route::post('/' , 'AdminsController@save');

			Route::post('/change_password' , 'AdminsController@change_password');
			Route::post('/soft_delete' , 'AdminsController@soft_delete');
			Route::post('/undelete' , 'AdminsController@undelete');
			Route::post('/hard_delete' , 'AdminsController@hard_delete');
			Route::post('/permits' , 'AdminsController@permits');
		});
	});

});


/*
|--------------------------------------------------------------------------
| Front Side
|--------------------------------------------------------------------------
|
*/

//Route::get('/home', 'HomeController@index');
