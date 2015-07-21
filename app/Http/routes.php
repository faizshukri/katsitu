<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [ 'as' => 'home', 'uses' => 'HomeController@main'] );

// Authentication routes...
Route::get('login', [ 'as' => 'auth.login', 'uses' => 'Auth\AuthController@getLogin' ]);
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', [ 'as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);

// Registration routes...
Route::get('register', [ 'as' => 'auth.register', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('register', 'Auth\AuthController@postRegister');

// Profile images
Route::get('profile_image/{profile_image}', function($profile_image, \App\Contracts\ImageHandler $imageHandler){
    return $imageHandler->make( storage_path('app/profile_images/' . $profile_image) )->response();
});

Route::group(['middleware' => 'auth'], function(){

    // Account
    Route::get('account', [ 'as' => 'account.index', 'uses' => 'AccountController@index' ]);
    Route::get('account/edit', [ 'as' => 'account.edit', 'uses' => 'AccountController@edit' ]);
    Route::post('account/update', [ 'as' => 'account.update', 'uses' => 'AccountController@update' ]);

    // People
    Route::get('people', [ 'as' => 'people.index', 'uses' => 'PeopleController@index' ]);

    // Events
    Route::get('event', [ 'as' => 'event.index', 'uses' => 'EventController@index' ]);
});

Route::group(['prefix'=>'api/v1'], function(){
    Route::get('locations', 'Api\V1\LocationController@locations');
    Route::get('locations.geojson', 'Api\V1\LocationController@geolocations');

    // Select2 list path
    $select2path = [ 'institutes', 'cities' ];

    // Get select2 list
    Route::get('{category}/{name}', 'Api\V1\Select2SourceController@query')
        ->where('category', '(' . implode('|', $select2path) . ')');

    Route::get('{category}/{id}', 'Api\V1\Select2SourceController@single')
        ->where('category', '(' . implode('|', array_map(function($val){ return str_singular($val); }, $select2path)) . ')');

});

Route::get('{username}', 'AccountController@user');
