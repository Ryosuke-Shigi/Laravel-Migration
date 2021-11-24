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
//販売期間登録画面
Route::get("sales_period_index","TicketController@sales_period_index");
Route::post("sales_period_index","TicketController@sales_period_index");

//チケット一覧表示画面
Route::get("ticket_list","TicketController@ticket_list_init");
Route::post("ticket_list","TicketController@ticket_list_init");
Route::get("ticket_list/{ticket_code}","TicketController@ticket_list");
Route::post("ticket_list/{ticket_code}","TicketController@ticket_list");

//新規登録画面
Route::get("create","TicketController@create");
Route::post("create","TicketController@create");
//削除
Route::get('delete/{ticket_code?}/{ticket_name?}',"TicketController@delete");
Route::post('delete/{ticket_code?}/{ticket_name?}',"TicketController@delete");
Route::get('delete_ticket_code_name/{ticket_code?}/{ticket_name?}',"TicketController@delete_ticket_code_name");
Route::post('delete_ticket_code_name/{ticket_code?}/{ticket_name?}',"TicketController@delete_ticket_code_name");
//販売期間登録　削除
Route::get('delete_ticket_code_sales_period/{ticket_code?}',"TicketController@delete_ticket_code_sales_period");
Route::post('delete_ticket_code_sales_period/{ticket_code?}',"TicketController@delete_ticket_code_sales_period");

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

//販売期間登録　商品番号選択
Route::get("sales_period","TicketController@sales_period");
Route::post("sales_period","TicketController@sales_period");
//販売期間　チケット登録画面
Route::get("sales_period_register/{ticket_name}/{tickets_kind?}","TicketController@sales_period_register");
Route::post("sales_period_register/{ticket_name}/{tickets_kind?}","TicketController@sales_period_register")->middleware('free_specialized');
//販売期間登録　
Route::get("sales_period_create","TicketController@sales_period_create");
Route::post("sales_period_create","TicketController@sales_period_create");
Route::get("sales_period_free_create","TicketController@sales_period_free_create");
Route::post("sales_period_free_create","TicketController@sales_period_free_create");
Route::get("sales_period_specialized_create","TicketController@sales_period_specialized_create");
Route::post("sales_period_specialized_create","TicketController@sales_period_specialized_create");


//POSTを使用した登録画面
Route::get('view_ticket_reserve','TicketController@view_ticket_reserve');
Route::post('view_ticket_reserve','TicketController@view_ticket_reserve');
//POSTを使用した登録処理
Route::get('ticket_reserve','TicketController@ticket_reserve');
Route::post('ticket_reserve','TicketController@ticket_reserve');


//チケット購入後　購入ありがとうございました　画面
Route::get('ticket_thanks','TicketController@ticket_thanks');



//POSTを使用した登録画面（枚数のみ選択）
Route::get('view_ticket_code_reserve/{ticket_code}/{sales_id}','TicketController@view_ticket_code_reserve');
Route::post('view_ticket_code_reserve/{ticket_code}/{sales_id}','TicketController@view_ticket_code_reserve');
//POSTを使用した登録（枚数のみ選択）
Route::get('ticket_code_reserve/{ticket_code}/{sales_id}','TicketController@ticket_code_reserve');
Route::post('ticket_code_reserve/{ticket_code}/{sales_id}','TicketController@ticket_code_reserve');







//編集画面　販売期間
Route::get('update_sales_period/{ticket_code?}/{sales_id?}',"TicketController@update_sales_period");
Route::post('update_sales_period/{ticket_code?}/{sales_id?}',"TicketController@update_sales_period");
//編集処理　販売期間
Route::get('put_sales_period/{ticket_code?}/{sales_id?}',"TicketController@put_sales_period");
Route::post('put_sales_period/{ticket_code?}/{sales_id?}',"TicketController@put_sales_period");








//ログイン　追加
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
