<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});


Route::get('/test', function()
{
	$anvil = new CSVLoader();
    //print_r($anvil);

    echo ($anvil->drop());

	//return View::make('hello');
});



/*
|--------------------------------------------------------------------------
| DATA ROUTES
|--------------------------------------------------------------------------
|
|	/data		- 
|	/data/upload	- the data upload console
*/


Route::get('/data',  array('before'=>'auth', 'uses'=>'DataController@showDataRoot'));

Route::get('/data/upload', array('before'=>'auth', 'uses'=>'DataController@showFileUpload'));

Route::get('/data/delete/{id?}', array('before'=>'auth', 'uses' => 'DataController@showFileDelete') )->where('id', 'all|[a-z0-9]+');

//TODO: FIX! when logged out, the system will still respond with a 200OK to dropzone.js (it should display ERROR instead of OK)
Route::post('/data/upload', array('before'=>'auth', 'uses'=>'DataController@processFileUpload'));


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
|
|
*/

// Authentication
Route::get('login', 'AuthController@showLogin');
Route::get('logout', 'AuthController@getLogout');
Route::post('login', 'AuthController@processLogin');


/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
|
|
*/

// Authentication
Route::get('/user', 'UserController@showUserProfile');
Route::get('/user/create', 'UserController@showUserCreate');
Route::post('/user/create', 'UserController@doUserCreate');

/*
|--------------------------------------------------------------------------
| ANALYSIS ROUTES
|--------------------------------------------------------------------------
|
|
*/

Route::get('/data/analyze/{id}', array('before'=>'auth', 'uses'=>'AnalyzeController@showFileAnalyze'))->where('id', 'all|[a-z0-9]+');
Route::get('/data/analyze', array('before'=>'auth', 'uses'=>'AnalyzeController@showAnalyzeRoot'));
