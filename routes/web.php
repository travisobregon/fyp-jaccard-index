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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('home', 'HomeController@index');

Route::resource('films', 'FilmsController');

Route::get('suggestions', 'SuggestionsController@index');

Route::post('ratings', 'RatingsController@store');

Route::get('metrics', 'MetricsController');
