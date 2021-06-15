<?php

use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'auth'], function(){
	
});
route::resource('/bidding', 'BiddingController',['except' => ['create']]);
Route::get('/opening/{bidding}', 'PagesController@show')->name('openingPage');
Auth::routes(['register'=>false, 'reset'=>false]);
Route::get('/', 'PagesController@index')->name('index');
Route::get('/home', 'PagesController@index')->name('home');
