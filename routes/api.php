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

Route::get('/index','api\ticketsController@index');

//webAPI
Route::get('/tickets','api\ticketsController@sales_interval');


//post ２０２１年１１月６日～
Route::post('/tickets/reserve','api\ticketsController@ticket_reserve');
