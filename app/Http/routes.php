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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix'=>'api'], function (){
    Route::get('/login', 'UserController@login');
    Route::get('/set_name', 'UserController@setName');
    Route::post('/set_avatar', 'UserController@setAvatar');
    Route::get('/get_user_type', 'UserController@getUserType');
    Route::get('/apply_admin', 'UserController@applyAdmin');
    Route::get('/get_can_apply', 'UserController@getCanApply');
    Route::post('/apply_course', 'CourseController@applyCourse');
    Route::get('/load_courses', 'CourseController@loadCourses');
});

Route::get('/add_theme','ThemeController@addTheme');

