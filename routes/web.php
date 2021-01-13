<?php

use Illuminate\Support\Facades\Route;

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
	return redirect(route('login'));
});

Auth::routes(); //Authentication

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');

Route::get('/register', 'Auth\RegisterController@register')->name('register');
Route::post('/register', 'Auth\RegisterController@register')->name('registerPost');

Route::get('/home', 'HomeController@home')->name('home');
Route::get('/resume/create', 'HomeController@resumeCreate')->name('resumeCreate');
Route::get('/resume/edit/{resume_id?}', 'HomeController@resumeEdit')->name('resumeEdit');

Route::get('/resume/{token?}', 'ResumeController@resumeView')->name('resumeView');

Route::post('api/resume/create', 'Api\ResumeController@createResume')->name('api.createResume');
Route::post('api/resume/update', 'Api\ResumeController@updateResume')->name('api.updateResume');
Route::post('api/resume/delete', 'Api\ResumeController@deleteResume')->name('api.deleteResume');

Route::post('api/resume-list', 'Api\ResumeController@listing')->name('api.resumeListing');