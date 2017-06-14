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
Route::get('manage/heyCheck', 'Front\FrontController@heyCheck');
//Route::get('postsConverter', 'Front\TestController@postsConverter');

/*
|--------------------------------------------------------------------------
| Manage Side
|--------------------------------------------------------------------------
|
*/
Route::group([
    'prefix'     => "manage",
    'middleware' => ['auth', 'is:admin'],
    'namespace'  => "Manage",
], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/index', 'HomeController@index');
    Route::get('/account', 'HomeController@account');
    Route::post('/password', 'HomeController@changePassword');

    /*-----------------------------------------------
    | Users ...
    */
    Route::group(['prefix' => "users", 'middleware' => "can:users",], function () {
        Route::get('/update/{item_id}/{request_role}', 'UsersController@update');
        Route::get('browse/{role}/search/{keyword?}', 'UsersController@search');
        Route::get('browse/{role}/{request_tab?}', 'UsersController@browse');
        Route::get('create/{role}', 'UsersController@create');
        Route::get('/act/{model_id}/{action}/{option?}', 'UsersController@singleAction');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'UsersController@save');
            Route::post('/password', 'UsersController@savePassword');
            Route::post('/permits', 'UsersController@savePermits');
            Route::get('/role/{user_id}/{role_slug}/{new_status}', 'UsersController@saveRole');
            Route::post('/delete', 'UsersController@delete');
            Route::post('/undelete', 'UsersController@undelete');
            Route::post('/destroy', 'UsersController@destroy');
            Route::post('/receipt', 'UsersController@saveNewReceipt');
        });
    });

    /*-----------------------------------------------
    | Comments ...
    */
    Route::group(['prefix' => 'comments', 'middleware' => 'can:comments'], function () {
        Route::get('/update/{item_id}', 'CommentsController@update');
        Route::get('/act/{model_id}/{action}', 'CommentsController@singleAction');
        Route::get('{request_tab?}/{switches?}', 'CommentsController@browse');
        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'CommentsController@save');
            Route::post('/process', 'CommentsController@process');
            Route::post('/delete', 'CommentsController@delete');
            Route::post('/deleteMass', 'CommentsController@deleteMass');
            Route::post('/undelete', 'CommentsController@undelete');
            Route::post('/undeleteMass', 'CommentsController@undeleteMass');
            Route::post('/destroy', 'CommentsController@destroy');
            Route::post('/destroyMass', 'CommentsController@destroyMass');
            Route::post('/statusMass', 'CommentsController@statusMass');
        });

    });


    /*-----------------------------------------------
    | Posts ...
    */
    Route::group(['prefix' => 'posts'], function () {
        Route::get('/update/{item_id}', 'PostsController@update');
        Route::get('/tab_update/{posttype}/{request_tab?}/{switches?}', 'PostsController@tabUpdate');

        Route::get('/act/{model_id}/{action}/{option?}', 'PostsController@singleAction');
        Route::get('/check_slug/{id}/{type}/{locale}/{slug?}/p', 'PostsController@checkSlug');

        Route::get('/{posttype}', 'PostsController@browse');
        Route::get('/{posttype}/create/{locale?}/{sisterhood?}', 'PostsController@create');
        Route::get('{posttype}/edit/{post_id}', 'PostsController@editor');
        //		Route::get('{posttype}/searched' , 'PostsController@searchResult');
        Route::get('{posttype}/{locale}/search', 'PostsController@search');
        Route::get('/{posttype}/{request_tab?}/{switches?}', 'PostsController@browse');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'PostsController@save');
            Route::post('/delete', 'PostsController@delete');
            Route::post('/undelete', 'PostsController@undelete');
            Route::post('/destroy', 'PostsController@destroy');
            Route::post('/clone', 'PostsController@makeClone');
            Route::post('/deleteMass', 'PostsController@deleteMass');
            Route::post('/undeleteMass', 'PostsController@undeleteMass');
            Route::post('/destroyMass', 'PostsController@destroyMass');
            Route::post('/owner', 'PostsController@changeOwner');
            Route::post('/good', 'PostsController@saveGood');
        });

    });

    /*-----------------------------------------------
    | Club ...
    */
    Route::group(['prefix' => "club",], function () {
        Route::group(['prefix' => 'save'], function () {
            Route::post('/draw_prepare', 'ClubController@drawPrepare');
            Route::post('/draw_select', 'ClubController@drawSelect');
            Route::get('/draw_delete/{key}', 'ClubController@drawDelete');
        });
    });


    /*-----------------------------------------------
    | Settings ...
    */
    Route::group(['prefix' => 'settings', 'middleware' => 'can:super'], function () {
        Route::get('/', 'SettingsController@index');
        Route::get('/tab/{request_tab?}', 'SettingsController@index');
        Route::get('/search', 'SettingsController@search');
        Route::get('/update/{model_id}', 'SettingsController@update');
        Route::get('/act/{model_id}/{action}/{option?}', 'SettingsController@singleAction');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'SettingsController@save');
            Route::post('/posttype', 'SettingsController@savePosttypeDownstream');
            Route::post('/pack', 'SettingsController@savePack');
        });
    });

    /*-----------------------------------------------
    | Categories ...
    */
    Route::group(['prefix' => 'categories', 'middleware' => 'can:super'], function () {
        Route::get('/', 'CategoriesController@index');
        Route::get('/update/{id}', 'CategoriesController@update');
        Route::get('/browse/{type}/{locale}', 'CategoriesController@index');
        Route::get('/create/folder/{type}/{locale}', 'CategoriesController@createFolder');
        Route::get('/create/{folder_id}/', 'CategoriesController@createCategory');
        Route::get('/edit/folder/{folder_id}', 'CategoriesController@editFolder');
        Route::get('/edit/{category_id}', 'CategoriesController@editCategory');
        Route::group(['prefix' => 'save'], function () {
            Route::post('', 'CategoriesController@saveCategory');
            Route::post('folder', 'CategoriesController@saveFolder');
        });
    });


    /*-----------------------------------------------
    | Upstream ...
    */
    Route::group(['prefix' => 'upstream', 'middleware' => 'is:developer'], function () {
        Route::get('/{request_tab?}', 'UpstreamController@index');
        Route::get('/{request_tab}/search/', 'UpstreamController@search');
        Route::get('/edit/{request_tab?}/{item_id?}/{parent_id?}', 'UpstreamController@editor');
        Route::get('/{request_tab}/{item_id}/{parent_id?}', 'UpstreamController@item');

        Route::group(['prefix' => 'save'], function () {
            Route::post('role', 'UpstreamController@saveRole');
            Route::post('role-titles', 'UpstreamController@saveRoleTitles');
            Route::post('role-activeness', 'UpstreamController@saveRoleActiveness');
            Route::post('role-default', 'UpstreamController@saveRoleDefault');
            Route::post('state', 'UpstreamController@saveProvince');
            Route::post('domain', 'UpstreamController@saveDomain');
            Route::post('city', 'UpstreamController@saveCity');
            Route::post('posttype', 'UpstreamController@savePosttype');
            Route::post('posttype-titles', 'UpstreamController@savePosttypeTitles');
            Route::post('department', 'UpstreamController@saveDepartment');
            Route::post('category', 'UpstreamController@saveCategory');
            Route::post('downstream', 'UpstreamController@saveDownstream');
            Route::post('package', 'UpstreamController@savePackage');
            Route::post('login_as', 'UpstreamController@loginAs');
        });
    });
});


/*
|--------------------------------------------------------------------------
| Front Side
|--------------------------------------------------------------------------
|
*/
Route::group(['namespace' => 'Auth', 'prefix' => '{lang}', 'middleware' => ['DetectLanguage', 'Setting']], function () {
    // reset password
    Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm');
    Route::post('/password/reset', 'ForgotPasswordController@sendResetLinkEmail');
    Route::get('/password/token/{haveCode?}', 'ForgotPasswordController@getToken');
    Route::post('/password/token', 'ForgotPasswordController@checkToken');
    Route::get('/password/new', 'ForgotPasswordController@newPassword');
    Route::post('/password/new', 'ForgotPasswordController@changePassword');

});

// uplaod url to b used in dropzone
Route::group(['prefix' => 'file'], function () {
    Route::post('upload', 'DropzoneController@upload_file')->name('dropzone.upload');
    Route::post('remove', 'DropzoneController@remove_file')->name('dropzone.remove');
});

Route::group(['namespace' => 'Front', 'middleware' => ['DetectLanguage', 'Setting']], function () {
    Route::get('/', 'FrontController@index');

    // tests
    Route::group(['prefix' => 'test'], function () {
        Route::get('/', 'TestController@index');
        Route::get('states', 'TestController@states');
        Route::get('gallery/archive', 'TestController@gallery_archive');
        Route::get('gallery/single', 'TestController@gallery_single');
        Route::get('post/single', 'TestController@post_single');
        Route::get('post/archive', 'TestController@post_archive');
        Route::get('volunteers', 'TestController@volunteers');
        Route::get('faqs', 'TestController@faqs');
        Route::get('works/send', 'TestController@works_send');
    });
    Route::get('about', 'TestController@about');


    Route::post('/register/new', 'FrontController@register');

    // saving comments for all posts
    Route::post('/comment', 'PostController@submit_comment')->name('comment.submit');

});
