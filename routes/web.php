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
	| Users ...
	*/
	Route::group(['prefix' => "users", 'middleware' => "can:users",] , function() {
		Route::get('/update/{item_id}' , 'UsersController@update');
		Route::get('browse/{role}/search/{keyword?}' , 'UsersController@search');
		Route::get('browse/{role}/{request_tab?}' , 'UsersController@browse');
		Route::get('create/{role}' , 'UsersController@create');
		Route::get('/act/{model_id}/{action}/{option?}' , 'UsersController@singleAction');

		Route::group(['prefix'=>'save'] , function() {
			Route::post('/' , 'UsersController@save');
			Route::post('/password' , 'UsersController@savePassword');
			Route::post('/permits' , 'UsersController@savePermits');
			Route::post('/role' , 'UsersController@saveRole');
			Route::post('/delete' , 'UsersController@delete');
			Route::post('/undelete' , 'UsersController@undelete');
			Route::post('/destroy' , 'UsersController@destroy');
		});
	});




	/*-----------------------------------------------
	| Posts ...
	*/
	Route::group(['prefix'=>'posts'] , function() {
		Route::get('/update/{item_id}' , 'PostsController@update');
		Route::get('/tab_update/{posttype}/{request_tab?}/{switches?}' , 'PostsController@tabUpdate') ;

		Route::get('/check_slug/{id}/{type}/{locale}/{slug?}/p' , 'PostsController@checkSlug');
		Route::get('/act/{model_id}/{action}' , 'PostsController@singleAction');

		Route::get('/{posttype}' , 'PostsController@browse') ;
		Route::get('/{posttype}/create/{locale?}/{sisterhood?}' , 'PostsController@create');
		Route::get('{posttype}/edit/{post_id}' , 'PostsController@editor');
//		Route::get('{posttype}/searched' , 'PostsController@searchResult');
		Route::get('{posttype}/{locale}/search' , 'PostsController@search');
		Route::get('/{posttype}/{request_tab?}/{switches?}' , 'PostsController@browse') ;

		Route::group(['prefix'=>'save'] , function() {
			Route::post('/' , 'PostsController@save');
			Route::post('/delete' , 'PostsController@delete');
			Route::post('/undelete' , 'PostsController@undelete');
			Route::post('/destroy' , 'PostsController@destroy');
			Route::post('/clone' , 'PostsController@makeClone');
		});

	});


	/*-----------------------------------------------
	| Settings ...
	*/
	Route::group(['prefix'=>'settings'  , 'middleware' => 'can:super'], function() {
		Route::get('/' , 'SettingsController@index') ;
		Route::get('/tab/{request_tab?}' , 'SettingsController@index') ;
		Route::get('/search' , 'SettingsController@search');
		Route::get('/update/{model_id}' , 'SettingsController@update');
		Route::get('/act/{model_id}/{action}/{option?}' , 'SettingsController@singleAction');

		Route::group(['prefix' => 'save'] , function() {
			Route::post('/', 'SettingsController@save');
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
			Route::post('role' , 'UpstreamController@saveRole');
			Route::post('state' , 'UpstreamController@saveProvince');
			Route::post('city' , 'UpstreamController@saveCity');
			Route::post('posttype' , 'UpstreamController@savePosttype');
			Route::post('department' , 'UpstreamController@saveDepartment');
			Route::post('category' , 'UpstreamController@saveCategory');
			Route::post('downstream' , 'UpstreamController@saveDownstream');
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

Route::group(['namespace' => 'Front', 'middleware' => ['DetectLanguage', 'Setting']], function () {
    Route::get('/', 'FrontController@index');
    Route::post('/register/new', 'FrontController@register');

    Route::group(['prefix' => '{lang}', 'middleware' => ['UserIpDetect']], function () {

        Route::get('/', 'FrontController@index');

    });

});
