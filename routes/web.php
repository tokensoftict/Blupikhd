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
use Illuminate\Support\Facades\Auth;

Route::get('/', ['as' => 'index', "uses" => 'FrontPageController@index']);
Route::get('/blogs', ['as' => 'blogs', "uses" => 'FrontPageController@blog']);
Route::get('/blogs/{permerlink}', ['as' => 'view_blog', "uses" => 'FrontPageController@view_blog']);
Route::post('/comment/{post_id}', ['as' => 'comment', "uses" => 'FrontPageController@comment']);
Route::get('/contact', ['as' => 'contact', "uses" => 'FrontPageController@contact']);
Route::get('/faqs', ['as' => 'faqs', "uses" => 'FrontPageController@faqs']);
Route::get('/terms', ['as' => 'terms', "uses" => 'FrontPageController@terms']);
Route::get('/partners', ['as' => 'partners', "uses" => 'FrontPageController@partners']);

Route::get('/topupwallet', ['as' => 'topupwallet', "uses" => 'FrontPageController@topupwallet']);

Route::get('/payment', ['as' => 'paypal.payment', 'uses' => 'PayPalPaymentController@payWithpaypal']);
Route::get('/payment/status',['as' => 'paypal.status', 'uses' => 'PayPalPaymentController@getPaymentStatus']);


Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');

Route::get('auth/twitter', 'Auth\TwitterController@cbTwitter');
Route::get('auth/twitter/callback', 'Auth\TwitterController@loginwithTwitter');

Route::get('stripe', 'StripePaymentController@stripe')->name('stripe.paymentform');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');


Route::get('auth/facebook', 'Auth\FacebookController@redirectToFacebook');
Route::get('auth/facebook/callback', 'Auth\FacebookController@handleFacebookCallback');


/*
Route::prefix('stripe')->as('stripe.')->group(function () {
    Route::get('success', ['as' => 'success', 'uses' => 'FrontPageController@success']);
    Route::get('cancel', ['as' => 'cancel', 'uses' => 'FrontPageController@cancel']);
});
*/
Route::prefix('user')->as('user.')->group(function () {
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'FronendDashboardController@dashboard']);
    Route::post('transfer_fund', ['as' => 'transfer_fund', 'uses' => 'FronendDashboardController@transfer_fund']);
    Route::post('subscribe_to_plan', ['as' => 'subscribe_to_plan', 'uses' => 'FronendDashboardController@subscribe_to_plan']);
    Route::post('top_up_wallet', ['as' => 'top_up_wallet', 'uses' => 'FronendDashboardController@top_up_wallet']);
    Route::match(['get','post'],'profile', ['as' => 'profile', 'uses' => 'FronendDashboardController@profile']);
    Route::match(['get','post'],'wallet', ['as' => 'wallet', 'uses' => 'FronendDashboardController@wallet']);
    Route::match(['get','post'],'contact', ['as' => 'contact', 'uses' => 'FronendDashboardController@contact']);
    Route::match(['get','post'],'movie_request', ['as' => 'movie_request', 'uses' => 'FronendDashboardController@movie_request']);
    Route::match(['get','post'],'load_programme_ajax', ['as' => 'load_programme_ajax', 'uses' => 'FronendDashboardController@load_programme_ajax']);
});

Route::prefix('frontpagelogin')->as('frontpagelogin.')->group(function () {
    Route::match(['post','get'],'loginprocess', ['as' => 'loginprocess', 'uses' => 'FrontPageController@loginprocess']);
});

Route::prefix('registration')->as('registration.')->group(function () {
    Route::get('/', ['as' => 'index', 'uses' => 'FrontPageController@signupform']);
    Route::match(['post','get'],'create_account', ['as' => 'create_account', 'uses' => 'FrontPageController@create_account']);
    Route::match(['post','get'],'add_funds_to_wallet', ['as' => 'add_funds_to_wallet', 'uses' => 'FrontPageController@add_funds_to_wallet']);

    Route::get('success', ['as' => 'success', 'uses' => 'FrontPageController@success']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'FrontPageController@logout']);

    Route::get('{plan_id}/chooseplan', ['as' => 'chooseplan', 'uses' => 'FrontPageController@chooseplan']);

});

Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
    Route::get('/', ['as' => 'dashboard', "uses" => 'HomeController@index']);

    Route::get('/home', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

    Route::match(['get','post'],'/forgotpassword/{token}', ['as' => 'forgotpassword', 'uses' => 'HomeController@forgotpassword']);

    Route::post('/login', ['as' => 'login_process', 'uses' => 'Auth\LoginController@login']);

    Route::get('/posts/{link}', ['as' => 'posts', 'uses' => 'HomeController@posts']);

    Route::get('logout', ['as' => 'logout', 'uses' => 'HomeController@logout']);
});

Route::group(['prefix' => 'facebook/auth'], function () {
    Route::get('/', [\App\Http\Controllers\SocialController::class, 'redirectToProvider']);
    Route::get('/callback', [\App\Http\Controllers\SocialController::class, 'handleProviderCallback']);
});


Route::group(['middleware' => ['auth']], function(){

    Route::prefix('profile')->group(function () {
        Route::match(['get', 'post'], 'password/change', ['as' => 'password.change', 'uses' => 'HomeController@changePassword', 'visible' => true]);
    });

    Route::prefix('blog')->as('blog.')->group(function () {

        Route::get('lists', ['as' => 'lists', 'uses' => 'BlogController@lists']);
        Route::get('create', ['as' => 'create', 'uses' => 'BlogController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'BlogController@store']);

        Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'BlogController@edit']);
        Route::get('{id}/delete', ['as' => 'delete', 'uses' => 'BlogController@delete']);
        Route::get('comments', ['as' => 'comments', 'uses' => 'BlogController@comments']);
        Route::get('approved_comments/{id}', ['as' => 'approved_comments', 'uses' => 'BlogController@approved_comments']);
        Route::get('delete_comments/{id}', ['as' => 'delete_comments', 'uses' => 'BlogController@delete_comments']);
    });

    Route::prefix('aws')->as('aws.')->group(function () {
        Route::get('/list',  ['as' => 'list', 'uses' => 'AwsFileManagerController@index'] );
        Route::match(['get', 'post'], '/create', ['as' => 'create', 'uses' => 'AwsFileManagerController@create'] );
        Route::get('{awsFileManager}/delete_file',  ['as' => 'delete', 'uses' => 'AwsFileManagerController@delete_file'] );
        Route::get('{awsBucket}/delete_bucket',  ['as' => 'delete_bucket', 'uses' => 'AwsFileManagerController@delete_bucket'] );
        Route::get('{awsBucket}/list_files',  ['as' => 'list_files', 'uses' => 'AwsFileManagerController@list_files'] );
        Route::get('/upload_form',  ['as' => 'upload_form', 'uses' => 'AwsFileManagerController@upload_form']);
        Route::post('/upload-single',  ['as' => 'uploadSingle', 'uses' => 'AwsFileManagerController@uploadSingle']);
        Route::post('/upload-multiple',  ['as' => 'uploadMultiple', 'uses' => 'AwsFileManagerController@uploadMultiple']);

        Route::post('/upload-single-custom',  ['as' => 'uploadSingleCustom', 'uses' => 'AwsFileManagerController@uploadSingleCustom']);
        Route::post('/upload-multiple-custom', ['as' => 'uploadMultipleCustom', 'uses' => 'AwsFileManagerController@uploadMultipleCustom']);
    });

    Route::prefix('subscribers')->as('subscribers.')->group(function () {

        Route::get('list_notification', ['as' => 'list_notification', 'uses' => 'SubscriberController@list_notification']);
        Route::match(['get','post'],'new_notification', ['as' => 'new_notification', 'uses' => 'SubscriberController@new_notification']);
        Route::get('{id}/delete_notification', ['as' => 'delete_notification', 'uses' => 'SubscriberController@delete_notification']);
        Route::match(['get','post'],'{id}/update_notification', ['as' => 'update_notification', 'uses' => 'SubscriberController@update_notification']);

        Route::get('lists', ['as' => 'lists', 'uses' => 'SubscriberController@lists']);
        Route::get('list_notification', ['as' => 'list_notification', 'uses' => 'SubscriberController@list_notification']);
        Route::get('active', ['as' => 'active', 'uses' => 'SubscriberController@active']);
        Route::match(['post','get'],'add_fund_to_wallet', ['as' => 'add_fund_to_wallet', 'uses' => 'SubscriberController@add_fund_to_wallet']);
        Route::get('series_request', ['as' => 'series_request', 'uses' => 'SubscriberController@series_request']);
        Route::get('movies_request', ['as' => 'movies_request', 'uses' => 'SubscriberController@movies_request']);
        Route::get('{id}/delete_movies_request', ['as' => 'delete_movies_request', 'uses' => 'SubscriberController@delete_movies_request']);
        Route::match(['post','get'],'{id}/approve_movies_request', ['as' => 'approve_movies_request', 'uses' => 'SubscriberController@approve_movies_request']);
        Route::match(['post','get'],'{id}/schedule_movies_request', ['as' => 'schedule_movies_request', 'uses' => 'SubscriberController@schedule_movies_request']);
    });


    Route::prefix('users')->as('users.')->group(function () {
        Route::get('lists', ['as' => 'lists', 'uses' => 'UserController@lists']);
        Route::match(['get','post'],'edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit']);
        Route::match(['get','post'],'add', ['as' => 'add', 'uses' => 'UserController@add']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'UserController@delete_users']);
    });

    Route::prefix('category')->as('category.')->group(function () {
        Route::get('lists', ['as' => 'lists', 'uses' => 'CategoryController@lists']);
        Route::post('{id}/edit', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
        Route::get('{id}/delete', ['as' => 'delete', 'uses' => 'CategoryController@delete']);
        Route::post('store', ['as' => 'store', 'uses' => 'CategoryController@store']);
    });

    Route::prefix('tag')->as('tag.')->group(function () {
        Route::get('lists', ['as' => 'lists', 'uses' => 'TagController@lists']);
        Route::post('{id}/edit', ['as' => 'edit', 'uses' => 'TagController@edit']);
        Route::get('{id}/delete', ['as' => 'delete', 'uses' => 'TagController@delete']);
        Route::post('store', ['as' => 'store', 'uses' => 'TagController@store']);
    });

    Route::prefix('setting')->as('setting.')->group(function () {
        Route::match(['post','get'],'access_mode', ['as' => 'access_mode', 'uses' => 'SettingController@access_mode']);
        Route::get('schedule', ['as' => 'schedule', 'uses' => 'SettingController@schedule']);
        Route::get('form', ['as' => 'form', 'uses' => 'SettingController@form']);

        Route::get('{id}/edit', ['as' => 'edit', 'uses' => 'SettingController@form']);
        Route::get('{id}/delete', ['as' => 'delete', 'uses' => 'SettingController@delete']);
        Route::post('process', ['as' => 'process', 'uses' => 'SettingController@process']);

        Route::match(['post','get'],'paypal', ['as' => 'paypal', 'uses' => 'SettingController@paypal']);

        Route::match(['post','get'],'googleplay', ['as' => 'googleplay', 'uses' => 'SettingController@googleplay']);
        Route::match(['post','get'],'appleplay', ['as' => 'appleplay', 'uses' => 'SettingController@appleplay']);

        Route::match(['post','get'],'stripe', ['as' => 'stripe', 'uses' => 'SettingController@stripe']);

        Route::match(['post','get'],'ads_mananger', ['as' => 'ads_mananger', 'uses' => 'SettingController@ads_mananger']);
        Route::match(['post','get'],'terms_condition', ['as' => 'terms_condition', 'uses' => 'SettingController@terms_condition']);

        Route::match(['post','get'],'frontpagesettings', ['as' => 'frontpagesettings', 'uses' => 'SettingController@frontpagesettings']);
    });

    Route::prefix('transaction')->as('transaction.')->group(function () {
        Route::get('wallet', ['as' => 'wallet', 'uses' => 'TransactionController@wallet']);
        Route::get('topup', ['as' => 'topup', 'uses' => 'TransactionController@topup']);
        Route::get('{id}/delete_wallet_topup', ['as' => 'delete_wallet_topup', 'uses' => 'TransactionController@delete_wallet_topup']);
        Route::get('{id}/delete_wallet_transaction', ['as' => 'delete_wallet_transaction', 'uses' => 'TransactionController@delete_wallet_transaction']);
    });

    Route::prefix('plan')->as('plan.')->group(function () {
        Route::get('lists', ['as' => 'lists', 'uses' => 'PlanController@lists']);
        Route::get('new', ['as' => 'new', 'uses' => 'PlanController@add']);
        Route::post('process', ['as' => 'process', 'uses' => 'PlanController@process']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'PlanController@edit']);
    });

});
