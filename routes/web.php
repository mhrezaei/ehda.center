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
Route::feeds();
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
    Route::get('/mhr', 'ConvertController@images');
    Route::get('/states', 'ConvertController@statesToDomains');
    Route::get('/roles', 'ConvertController@createRoles');
    Route::get('/meta', 'ConvertController@postsMeta');
    Route::get('/posts', 'ConvertController@posts');
    Route::get('/users/{take?}/{loop?}', 'ConvertController@users');
    Route::get('/userRoleCaches', 'ConvertController@userRoleCaches');
    Route::get('/printing/{take?}/{loop?}', 'ConvertController@printing');

    Route::get('/tests', 'ConvertController@tests');
    Route::get('/tests2', 'ConvertController@tests2');
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
    Route::get('/widget/{widget}', 'HomeController@widget');

    Route::group(['prefix' => 'act'], function () {
        Route::get('search-people', 'HomeController@searchPeople');
    });

    /*-----------------------------------------------
    | Account ...
    */
    Route::group(['prefix' => "account"], function () {
        Route::get('/{request_tab?}', 'AccountController@index');
        Route::get('/act/{action}', 'AccountController@action');


        Route::group(['prefix' => 'save'], function () {
            Route::post('/password', 'AccountController@savePassword');
            Route::post('/profile', 'AccountController@saveProfile');
            Route::post('/card-register', 'AccountController@saveCard');
        });
    });

    /*-----------------------------------------------
    | Volunteers ...
    */
    Route::group(['prefix' => 'volunteers', 'middleware' => "can:users-volunteer",], function () {
        Route::get('/', 'VolunteersController@browseChild');
        Route::get('/browse/update/{model_id}/{request_role?}', 'VolunteersController@update');
        //Route::get('/browse/search/{keyword?}', 'VolunteersController@searchChild');
        Route::get('/browse/{domain_slug}/search/{keyword?}', 'VolunteersController@searchChild');
        Route::get('/browse/{request_role?}/{request_tab?}/{volunteer?}/{post?}', 'VolunteersController@browseChild');
        Route::get('/search', 'VolunteersController@search');

        Route::get('/create/{request_role?}/{code_melli?}', 'VolunteersController@createChild');
        Route::get('/edit/{model_id?}', 'VolunteersController@editorChild');
        Route::get('/view/{model_id}', 'VolunteersController@view');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'VolunteersController@saveChild');
            Route::post('/inquiry', 'VolunteersController@inquiry');
            Route::post('/new-role', 'VolunteersController@saveNewRole');
            Route::post('/changes', 'VolunteersController@moderateChanges');
        });

    });

    /*-----------------------------------------------
    | Students
    */
    Route::group(['prefix' => 'students', 'middleware' => "can:users-student",], function () {
        Route::get('/', 'StudentsController@browseChild');
        Route::get('/browse/update/{model_id}/{request_role?}', 'StudentsController@update');
        Route::get('/browse', 'StudentsController@browseChild');
        Route::get('/browse/search/{keyword?}', 'StudentsController@searchChild');
        Route::get('/create/', 'StudentsController@createChild');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'StudentsController@attachRole');
            Route::post('/delete', 'StudentsController@detachRole');
        });
    });


    /*-----------------------------------------------
    | Cards ...
    */
    Route::group(['prefix' => 'cards', 'middleware' => "can:users-card-holder"], function () {
        Route::get('/', 'CardsController@browseChild');
        Route::get('/browse', 'CardsController@browseChild');
        Route::get('/browse/update/{model_id}', 'CardsController@update');
        //Route::get('/stats', 'CardsController@stats'); //TODO
        Route::get('/browse/search/{keyword?}', 'CardsController@searchChild');
        Route::get('/browse/{request_tab}/{volunteer?}/{post?}', 'CardsController@browseChild');
        Route::get('/search', 'CardsController@search');

        Route::get('event-stats/{post_id}', 'CardsController@eventStats');
        Route::get('/view/{model_id}', 'CardsController@view');

        Route::get('/printings/act/{action}', 'CardsController@printingAction');

        //Route::get('/printings/modal/{printing_id}/{modal_action}', 'CardsController@modalActions');
        Route::get('/printings/download_excel/{event_id}', 'CardsController@printingExcelDownload');
        Route::get('/printings/{request_tab?}/{event_id?}/{user_id?}/{volunteer_id?}', 'CardsController@printingBrowse');

        Route::get('/create/{code_melli?}', 'CardsController@createChild');
        Route::get('/edit/{model_id}', 'CardsController@editorChild');

        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'CardsController@saveChild');
            Route::post('/volunteers', 'CardsController@saveForVolunteers');
            Route::post('/inquiry', 'CardsController@inquiry');

            Route::post('/add_to_print', 'CardsController@addToPrintings');
            Route::post('/add_to_print_mass', 'CardsController@addToPrintingsMass');
            Route::post('/delete', 'CardsController@deleteChild');
            //Route::post('/bulk_delete', 'CardsController@bulk_delete');
            //Route::post('/sms', 'CardsController@sms');
            //Route::post('/email', 'CardsController@email');
            Route::post('/bulk_email', 'CardsController@bulk_email');
            Route::post('/print', 'CardsController@single_print');
            Route::post('/bulk_print', 'CardsController@bulk_print');

            Route::post('printings/', 'CardsController@printingActionSave');
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
            Route::post('/statusMass', 'UsersController@saveStatusMass');
            Route::post('/status', 'UsersController@saveStatus');
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
    | Orders ...
    */
    Route::group(['prefix' => 'orders', /*'middleware' => 'can:comments'*/], function () {
        Route::get('update/{item_id}', 'OrdersController@update');
        Route::get('act/{model_id}/{action}', 'OrdersController@singleAction');
        Route::get('{request_tab?}/{switches?}', 'OrdersController@browse');
        Route::group(['prefix' => 'save'], function () {
            Route::post('/', 'OrdersController@save');
//            Route::post('/process', 'OrdersController@process');
//            Route::post('/delete', 'OrdersController@delete');
//            Route::post('/deleteMass', 'OrdersController@deleteMass');
//            Route::post('/undelete', 'OrdersController@undelete');
//            Route::post('/undeleteMass', 'OrdersController@undeleteMass');
//            Route::post('/destroy', 'OrdersController@destroy');
//            Route::post('/destroyMass', 'OrdersController@destroyMass');
//            Route::post('/statusMass', 'OrdersController@statusMass');
        });
    });

    Route::group(['prefix' => 'commenting'], function () {
        Route::get('convert-to-post/{model_id}', 'CommentsController@convertToPost')
            ->name('manage.comments.convert-to-post');
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
            Route::post('/pin', 'PostsController@savePin');
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
            Route::post('role-mass', 'UpstreamController@saveRoleMass');
            Route::post('role-activeness', 'UpstreamController@saveRoleActiveness');
            //Route::post('role-default', 'UpstreamController@saveRoleDefault');
            Route::post('state', 'UpstreamController@saveProvince');
            Route::post('domain', 'UpstreamController@saveDomain');
            Route::post('city', 'UpstreamController@saveCity');
            Route::post('posttype', 'UpstreamController@savePosttype');
            Route::post('posttype-titles', 'UpstreamController@savePosttypeTitles');
            Route::post('category', 'UpstreamController@saveCategory');
            Route::post('downstream', 'UpstreamController@saveDownstream');
            Route::post('package', 'UpstreamController@savePackage');
            Route::post('artisan', 'UpstreamController@artisan');
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

// File
Route::group(['prefix' => 'file'], function () {
    Route::get('{hashid}/{fileName?}', 'FileManagerController@download')->name('file.download');
    Route::get('disposable/{hashString}/{hashid}/{fileName?}', 'FileManagerController@disposableDownload')
        ->name('file.download.disposable');
});

// File Manager
Route::group(['prefix' => 'file-manager', 'middleware' => ['auth', 'is:admin']], function () {
    Route::get('/', 'FileManagerController@index')
        ->name('fileManager.index');
    Route::post('get-list', 'FileManagerController@getList')
        ->name('fileManager.getList');
    Route::post('preview', 'FileManagerController@getPreview')
        ->name('fileManager.preview');
    Route::get('file-details/{fileKey?}', 'FileManagerController@getFileDetails')
        ->name('fileManager.getFileDetails');
    Route::post('file-details', 'FileManagerController@setFileDetails')
        ->name('fileManager.setFileDetails');
    Route::post('delete-file', 'FileManagerController@deleteFile')
        ->name('fileManager.deleteFile');
    Route::post('restore-file', 'FileManagerController@restoreFile')
        ->name('fileManager.restoreFile');
});

// Payment Bank
Route::group(['prefix' => 'payment', 'namespace' => 'Payment'], function () {
    Route::get('/bank-process/{tracking_number}', 'PaymentController@bank_process')->name('bank-process');
});


Route::group(['namespace' => 'Front', 'middleware' => ['DetectLanguage', 'Setting', 'Subdomain']], function () {

    // if not set lang prefix
    Route::get('/', 'FrontController@index')->name('home');

    // If identifier is string and starts with value of config('prefix.routes.post.short')
    Route::get('{identifier}', 'PostController@postVeryShortLink')
        ->name('post.single.very-short')
        ->where('identifier', '^' . config('prefix.routes.post.short') . '(\w|)+$');

    // organ donation card
    Route::get('/card/show_card/{type}/{user_hash_id}/{mode?}', 'OrganDonationCardController@index');

    // events custom landing page
    Route::get('/ramazan', 'LandingPageController@ramazan');
    Route::post('/ramazan', 'LandingPageController@ramazan_count');

    Route::get('/summer', 'LandingPageController@summer');
    Route::post('/summer', 'LandingPageController@summer_count');

    Route::group(['prefix' => '{lang}'], function () {
        Route::get('/', 'FrontController@index')->name('site');

        // tests
//        Route::group(['prefix' => 'test'], function () {
//            Route::get('/', 'TestController@index');
////            Route::get('states', 'TestController@states');
////            Route::get('gallery/archive', 'TestController@gallery_archive');
////            Route::get('gallery/single', 'TestController@gallery_single');
////            Route::get('post/single', 'TestController@post_single');
////            Route::get('post/archive', 'TestController@post_archive');
////            Route::get('volunteers', 'TestController@volunteers');
////            Route::get('faqs', 'TestController@faqs');
////            Route::get('works/send', 'TestController@works_send');
////            Route::get('mail-view', 'TestController@mail_view');
//            Route::get('messages', 'TestController@messages');
////            Route::get('messages/send', 'TestController@messages_send');
//            Route::get('file-manager', 'TestController@fileManager');
//            Route::get('uploader', 'TestController@uploader');
//            Route::get('payment', 'TestController@test');
//        });

        // Contact Us Page
        Route::get('contact', 'FrontController@contact')->name('contact');

        // Sending Client Works
        Route::get('works/send', 'PostController@works_send')->name('users.works.send');

        // Saving Comments (All Posts)
        Route::post('/comment', 'PostController@submit_comment')->name('comment.submit');

        // Register new card
        Route::get('organ_donation_card', 'CardController@index')->name('register_card');
        Route::post('register/card', 'CardController@save_registration')->name('register_card.post');

        // User Routes
        Route::group(['prefix' => 'user', 'middleware' => ['auth', 'is:card-holder']], function () {
            Route::get('dashboard', 'UserController@index')->name('user.dashboard');
            Route::get('profile', 'UserController@profile')->name('user.profile.edit');
            Route::get('drawing', 'UserController@drawing');
            Route::get('events', 'UserController@events');
            Route::post('update', 'UserController@update')->name('user.profile.update');
        });

        // If identifier is string and starts with value of config('prefix.routes.post.short')
        Route::get('{identifier}', 'PostController@show_with_short_url')
            ->name('post.single.short')
            ->where('identifier', '^' . config('prefix.routes.post.short') . '(\w|)+$');
        // If identifier is numeric (left from old version)
        Route::get('{postId}', 'PostController@show_with_exact_id')
            ->where('postId', '[0-9]+');

        // Preview Posts (Single and Archive)
        Route::get('/show-post/{identifier}/{url?}', 'PostController@show_with_full_url')->name('post.single');
        Route::get('/previewPost/{id}/{url?}', 'PostController@show');
        Route::get('/archive/{postType?}/{category?}', 'PostController@archive')->name('post.archive');
        Route::get('/gallery/categories/{postType}', 'GalleryController@show_categories')->name('gallery.categories');
        Route::get('/gallery/posts/{category}', 'GalleryController@show_categories_posts');
        Route::get('/gallery/show/{id}/{url?}', 'GalleryController@show_gallery');

        Route::get('/convert', 'TestController@convertCardsFromMhr');

        // Static pages
        Route::get('faq', 'PostController@faqs');
        Route::get('volunteers/special', 'PostController@special_volunteers')->name('volunteers.special');

        Route::group(['prefix' => 'angels'], function () {
            Route::get('/', 'PostController@angels')->name('angels.list');
            Route::post('find', 'PostController@angels_find')->name('angels.find');
            Route::get('new', 'PostController@new_angel_form')->name('angels.new.form');
            Route::post('new/submit', 'PostController@new_angel_submit')->name('angels.new.submit');
        });

        // Volunteer pages
        Route::group(['prefix' => 'volunteers'], function () {
            Route::get('/', 'VolunteersController@index')->name('volunteer.register.step.1.get');
            Route::post('first-step', 'VolunteersController@register_first_step')
                ->name('volunteer.register.step1.post');
            Route::get('final-step', 'VolunteersController@register_final_step')
                ->name('volunteer.register.step.final.get');
            Route::post('final-step', 'VolunteersController@register_final_step_submit')
                ->name('volunteer.register.step.final.post');
//            Route::get('exam', 'members\VolunteersController@exam');
        });

        // States
        Route::group(['prefix' => 'states'], function () {
            Route::get('/', 'StatesController@map')->name('states.index');
        });

        // Tutorials
        Route::group(['prefix' => 'education'], function () {
            Route::get('/{educationType}', 'EducationController@archive')->name('education.archive');
            Route::post('get-posts', 'EducationController@ajaxGetPosts')->name('education.get-posts.ajax');
        });

        // Products
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', 'ProductsController@archive')->name('products.archive');
            Route::post('purchase', 'ProductsController@purchase')->name('products.purchase');
            Route::get('payment-result/{order}', 'ProductsController@paymentResult')
                ->name('education.paymentResult');
            Route::post('tracking', 'ProductsController@track')->name('products.tracking');
        });

        // Massages
        Route::group(['prefix' => 'messages'], function () {
            Route::get('{limit?}', 'MessagesController@sendMessages')
                ->where('limit', '[0-9]+')
                ->name('messages.send');
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