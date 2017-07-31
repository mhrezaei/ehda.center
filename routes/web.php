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
| Converters
|--------------------------------------------------------------------------
|
*/
Route::group([ //@TODO: Remove when project fully erected.
    'prefix'    => "convert",
    //'middleware' => ['auth','is:developer'] ,
    'namespace' => "Manage",
], function () {
    Route::get('/', 'ConvertController@index');
    Route::get('/taha', 'ConvertController@createTaha');
    Route::get('/roles', 'ConvertController@createRoles');
    Route::get('/meta', 'ConvertController@postsMeta');
    Route::get('/posts', 'ConvertController@posts');
    Route::get('/users/{take?}/{loop?}', 'ConvertController@users');
    Route::get('/tests', 'ConvertController@tests');
});

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
    | Cards ...
    */
    Route::group(['prefix' => 'cards'], function () { //@TODO: add Permissions
        Route::get('/', 'CardsController@browseRole');
        Route::get('/browse', 'CardsController@browseRole');
        Route::get('/browse/update/{model_id}', 'CardsController@update');
        Route::get('/stats', 'CardsController@stats');
        Route::get('/browse/search/{keyword?}', 'CardsController@searchRole');
        Route::get('/browse/{request_tab}/{volunteer?}/{post?}', 'CardsController@browseRole');
        Route::get('/search', 'CardsController@search');
        Route::get('/reports', 'CardsController@reports');//@TODO: INTACT!

        Route::get('/printings/modal/{printing_id}/{modal_action}', 'PrintingsController@modalActions');
        Route::get('/printings/download_excel/{event_id}', 'PrintingsController@excelDownload');
        Route::get('/printings/{request_tab?}/{event_id?}/{user_id?}/{volunteer_id?}', 'PrintingsController@browse');

        Route::get('/create/{volunteer_id?}', 'CardsController@create');
        Route::get('/{card_id}', 'CardsController@show');
        Route::get('/{card_id}/edit', 'CardsController@editor');
        Route::get('/{card_id}/{modal_action}', 'CardsController@modalActions');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'CardsController@save');
            Route::post('/volunteers', 'CardsController@saveForVolunteers');
            Route::post('/inquiry', 'CardsController@inquiry');

            Route::post('/add_to_print', 'CardsController@add_to_print');
            Route::post('/change_password', 'CardsController@change_password');
            Route::post('/delete', 'CardsController@delete');
            Route::post('/bulk_delete', 'CardsController@bulk_delete');
            Route::post('/sms', 'CardsController@sms');
            Route::post('/email', 'CardsController@email');
            Route::post('/bulk_email', 'CardsController@bulk_email');
            Route::post('/print', 'CardsController@single_print');
            Route::post('/bulk_print', 'CardsController@bulk_print');

            Route::post('printings/bulk_excel', 'PrintingsController@bulkExcel');
            Route::post('printings/bulk_print', 'PrintingsController@bulkPrint');
            Route::post('printings/bulk_confirm', 'PrintingsController@bulkConfirm');
        });
    });


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
            Route::post('/smsMass', 'UsersController@smsMass');
            Route::post('/password', 'UsersController@savePassword');
            Route::post('/permits', 'UsersController@savePermits');

            Route::post('/delete', 'UsersController@delete');
            Route::post('/undelete', 'UsersController@undelete');
            Route::post('/destroy', 'UsersController@destroy');

            Route::get('/role/{user_id}/{role_slug}/{new_status}', 'UsersController@saveRole');
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
        Route::group(['prefix' => 'trans'], function () {
            Route::get('diff', 'TransController@diff');
        });

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

Route::group(['namespace' => 'Front', 'middleware' => ['DetectLanguage', 'Setting', 'Subdomain']], function () {

    // if not set lang prefix
    Route::get('/', 'FrontController@index')->name('home');

    // organ donation card
    Route::get('/card/show_card/{type}/{user_hash_id}/{mode?}', 'OrganDonationCardController@index');

    // landing page
    Route::get('/ramazan', 'LandingPageController@ramazan');
    Route::post('/ramazan', 'LandingPageController@ramazan_count');

    Route::get('/summer', 'LandingPageController@summer');
    Route::post('/summer', 'LandingPageController@summer_count');

    Route::group(['prefix' => '{lang}'], function () {
        Route::get('/', 'FrontController@index')->name('site');

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

        // send users works
        Route::get('works/send', 'PostController@works_send')->name('users.works.send');

        // register new user
        Route::post('/register/new', 'FrontController@register');

        // saving comments for all posts
        Route::post('/comment', 'PostController@submit_comment')->name('comment.submit');

        // register new card
        Route::get('/organ_donation_card', 'CardController@index')->name('register_card');
        Route::post('/register/card', 'CardController@save_registration')->name('register_card.post');
        Route::post('/register/first_step', 'CardController@register_first_step');
        Route::post('/register/second_step', 'CardController@register_second_step');

        // User Routes
        Route::group(['prefix' => 'user', 'middleware' => ['auth', 'is:card-holder']], function () {
            Route::get('dashboard', 'UserController@index')->name('user.dashboard');
            Route::get('profile', 'UserController@profile')->name('user.profile.edit');
            Route::get('drawing', 'UserController@drawing');
            Route::get('events', 'UserController@events');
            Route::post('update', 'UserController@update')->name('user.profile.update');
        });


        /*
        |--------------------------------------------------------------------------
        | CARD HOLDER PANEL
        |--------------------------------------------------------------------------
        | For the holders of cards, in 'members' folder
        */
        // @TODO: view not work check it
        Route::group(['prefix' => 'members', 'middleware' => 'is:card-holder'], function () {
            Route::get('/my_card', 'MembersController@index');
            Route::get('/my_card/edit', 'MembersController@edit_my_card');
            Route::post('/my_card/edit_process', 'MembersController@edit_card_process');
        });

        // another route copy from ehda-b1 project
        Route::get('/{identifier}', 'PostController@show_with_short_url')
            ->where('identifier', '^' . config('prefix.routes.post.short') . '(\w|)+$');
        // if identifier starts with value of config('prefix.routes.post.short')

        Route::get('/show-post/{identifier}/{url?}', 'PostController@show_with_full_url')->name('post.single');
        Route::get('/previewPost/{id}/{url?}', 'PostController@show');
        Route::get('/archive/{postType?}/{category?}', 'PostController@archive')->name('post.archive');
        Route::get('/gallery/categories/{postType}', 'GalleryController@show_categories')->name('gallery.categories');
        Route::get('/gallery/posts/{category}', 'GalleryController@show_categories_posts');
        Route::get('/gallery/show/{id}/{url?}', 'GalleryController@show_gallery');

        Route::get('/convert', 'TestController@convertCardsFromMhr');

        // static pages
        Route::get('faq', 'PostController@faqs');
        Route::get('volunteers/special', 'PostController@special_volunteers')->name('volunteers.special');

        Route::group(['prefix' => 'angels'], function () {
            Route::get('/', 'PostController@angels')->name('angels.list');
            Route::post('find', 'PostController@angels_find')->name('angels.find');
            Route::get('new', 'PostController@new_angel_form')->name('angels.new.form');
            Route::post('new/submit', 'PostController@new_angel_submit')->name('angels.new.submit');
        });

        // volunteer pages
        Route::get('volunteers', 'VolunteersController@index')->name('volunteer.register.step.1.get');
        Route::post('volunteer/first-step', 'VolunteersController@register_first_step')
            ->name('volunteer.register.step1.post');
        Route::get('volunteers/final-step', 'VolunteersController@register_final_step')
            ->name('volunteer.register.step.final.get');
        Route::post('volunteers/final-step', 'VolunteersController@register_final_step_submit')
            ->name('volunteer.register.step.final.post');
        Route::post('volunteer/second_step', 'VolunteersController@register_second_step');
        Route::get('/volunteers/exam', 'members\VolunteersController@exam');

        // States
        Route::group(['prefix' => 'states'], function () {
            Route::get('/', 'StatesController@map');
        });
    });


    // ECG
    Route::group(['prefix' => 'ecg'], function () {
        // Implementation of skillstat
        Route::get('copy', 'ECGController@copy');

        Route::get('simulator', 'ECGController@simulator');
        Route::get('simulator/dev', 'ECGController@simulator_dev')->middleware(['auth', 'is:developer']);
    });
});