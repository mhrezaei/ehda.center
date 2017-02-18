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
	Route::get('/account' , 'HomeController@account');
	Route::post('/password' , 'HomeController@changePassword');

	/*-----------------------------------------------
	| Admins ...
	*/



	Route::group(['prefix'=>'admins', 'middleware' => 'is:super'] , function() {
		Route::get('/update/{item_id}/{adding?}' , 'AdminsController@update');
		Route::get('/' , 'AdminsController@browse') ;
		Route::get('/browse/{request_tab?}' , 'AdminsController@browse') ;
		Route::get('/create/' , 'AdminsController@create') ;
		Route::get('/search' , 'AdminsController@search');
		Route::get('/{user_id}/{modal_action}' , 'AdminsController@singleAction');

		Route::group(['prefix'=>'save'] , function() {
			Route::post('/' , 'AdminsController@save');

			Route::post('/password' , 'AdminsController@password');
			Route::post('/delete' , 'AdminsController@delete');
			Route::post('/undelete' , 'AdminsController@undelete');
			Route::post('/destroy' , 'AdminsController@destroy');
			Route::post('/permits' , 'AdminsController@permits');
		});
	});


	/*-----------------------------------------------
	| Upstream ...
	*/
	Route::group(['prefix' => 'upstream', 'middleware' => 'is:developer'] , function() {
		Route::get('/{request_tab?}' , 'UpstreamController@index') ;
		Route::get('/{request_tab}/search/' , 'UpstreamController@search') ;
		Route::get('/edit/{request_tab?}/{item_id?}/{parent_id?}' , 'UpstreamController@editor') ;
		Route::get('/{request_tab}/{item_id}/{parent_id?}' , 'UpstreamController@item') ;

		Route::group(['prefix' => 'save'] , function() {
			Route::post('state' , 'UpstreamController@saveProvince');
			Route::post('city' , 'UpstreamController@saveCity');
			Route::post('branch' , 'UpstreamController@saveBranch');
			Route::post('department' , 'UpstreamController@saveDepartment');
			Route::post('category' , 'UpstreamController@saveCategory');
			Route::post('downstream' , 'UpstreamController@saveDownstream');
			Route::post('downstream_value' , 'UpstreamController@setDownstream');
			Route::post('login_as' , 'UpstreamController@loginAs');
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
