<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Basic Register and Login POST routes
Route::post('/register', 'Auth\RegisterController@registerAPI')->name('api_user_register');
Route::post('/login', 'Auth\LoginController@loginAPI')->name('api_user_login');

Route::prefix('/client')->group(function() {
    Route::post('/current', array(
        'as' => 'api_client_current',
        'uses' => 'ClientController@current'
    ))->middleware('auth:api');
});

// CRUD to manage user notifications
Route::prefix('/notifications')->group(function() {
    Route::post('/create', array(
        'as' => 'api_notification_create',
        'uses' => 'NotificationsController@create'
    ))->middleware('auth:api');
    Route::post('/read', array(
        'as' => 'api_notification_read',
        'uses' => 'NotificationsController@read'
    ))->middleware('auth:api');
    // General route to update a notification that can be used to update the (unread/read) status too
    Route::post('/update', array(
        'as' => 'api_notification_update',
        'uses' => 'NotificationsController@update'
    ))->middleware('auth:api');
    Route::post('/delete', array(
        'as' => 'api_notification_delete',
        'uses' => 'NotificationsController@delete'
    ))->middleware('auth:api');
});

// Endpoint for Yelp API integration
Route::prefix('/yelp')->group(function() {
    /*
     * Available routes after businesses tag
     * /search                       // Search for businesses by keyword, category, location, price level, etc.
     * /search/phone                 // Search for businesses by phone number.
     * /{transaction_type}/search    // Search for businesses which support food delivery transactions.
     * /{alias}                      // Get rich business data, such as name, address, phone number, photos, Yelp rating, etc...
     * /matches                      // Find the Yelp business that matches an exact input location. Use this to match business data from other sources with Yelp businesses.
     * /{alias}/reviews              // Get up to three review excerpts for a business.
     * */
    Route::post('/businesses/{any}', array(
        'as' => 'api_yelp_business',
        'uses' => 'YelpAPIController@businesses'
    ))->middleware('auth:api')->where('any', '.*');
});
