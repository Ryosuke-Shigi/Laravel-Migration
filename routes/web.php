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

アドレスでリクエストされたものより　コントローラーへ分配します

*/


/* ROUTES 一番最初にリクエストが通るところです　リクエストされたものを分配します
    でもこの形では基本使わない　コントローラーで制御しているので */
//route::get "public static function route　というのがrouteクラスにあるのかと思う"
//なので Route　を宣言していないが扱えている　static　だから
//アロー演算子ー＞でなくスコープ定義演算子：：なのもそのため
/* Route::get('/',function () {    //memo ：：はクラス内に定義されているものを呼び出すもの get関数を読んでいる
    return view('welcome'); //コントローラーでも return view(名前)で返している　ここでコールバック関数なのは　コントローラーがないときでも動くようにするため
}); //無 *///名関数で条件を制御する　コールバック関数　を使っている様子

//コントローラーを通して表示
// アドレス末尾「hello」をリクエストされたら
//　http->controllers->HelloControllerの　top（@top)　へ
//Route::get("top","HelloController@top");
//Route::get("/","HelloController@top");

//introduction　でリクエストがきたら　HelloControllerのintroductionへ
Route::get("introduction","HelloController@introduction");
//contact でリクエストがきたら　HelloControllerのcontactへ
Route::get("contact","HelloController@contact");

Route::post("store","HelloController@store");



//課題用　トップ
Route::get("/","TestController@top");
Route::get("Top","TestController@top");
//homework 1-1
Route::post("Answer","TestController@Answer");
//homework 1-2
Route::post("Answer2","TestController@Answer2");
//homework 1-3
Route::post("Answer3","TestController@Answer3");

//laravel演習（ファイル読込);
Route::post("Taxifare","TestController@Taxifare",);

//チケット登録画面
Route::get("index","TicketController@index");
Route::post("index","TicketController@index");
//新規登録画面
Route::get("create","TicketController@create");
Route::post("create","TicketController@create");
//削除
Route::get('delete/{ticket_code?}/{ticket_name?}',"TicketController@delete");
Route::post('delete/{ticket_code?}/{ticket_name?}',"TicketController@delete");
Route::get('delete_ticket_code_name/{ticket_code?}/{ticket_name?}',"TicketController@delete_ticket_code_name");
Route::post('delete_ticket_code_name/{ticket_code?}/{ticket_name?}',"TicketController@delete_ticket_code_name");
//編集
Route::get('update/{ticket_code?}',"TicketController@update");
Route::post('update/{ticket_code?}',"TicketController@update");
Route::get('update_types/{ticket_code?}',"TicketController@update_types");
Route::post('update_types/{ticket_code?}',"TicketController@update_types");
Route::get('update_all/{ticket_code?}',"TicketController@update_all");
Route::post('update_all/{ticket_code?}',"TicketController@update_all");
//登録
Route::get("store","TicketController@store");
Route::post("store","TicketController@store");

//table3一覧表示
Route::get("index3","TicketController@index3");
Route::get("index3","TicketController@index3");


//ログイン　追加
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
