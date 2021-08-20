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

/*
 *　ここは　Routes
 * URLの形できたリクエストより　controller　へと分配する
*/

//　｛　名前　｝　で受け取るリクエストのしているものは、名前の部分を変数として扱える様子
//　これでhtml上で色々変更かけたものを送らせることで返すページも違うようにできる・・・

//get("リクエストされたURL" なので
//posts　の　{postId} の　comments　の　{commentId} となる　PostIdとCommentIdは変数のような扱い
//postId と CommentId のところで　１２３　と　４５６　が指定されリクエストされたとする
//返す(return) 123(postId)番目のブログ記事の456(commentId)番目のコメント
//と返す　向こうからのリクエストで変化をつける。
//なのでページのディレクトリ構成でのURLが絶対的なものではなく、ユーザからのリクエストによって変化を出せる！
//ちなみにこれはあくまでサンプルなので　コントローラーに飛ばすということを忘れないこと
Route::get("posts/{postId}/comments/{commentId}",function($postId,$commentId){
    return $postId."番のブログ投稿記事の".$commentId."番目のコメント";});

//　/contact　でリクエストされたら　view　の　contact.blade.php を返す(bladeはお約束です)
Route::get("/contact",function(){
    return view("contact");
});

// /users　でリクエストされたら　UserControllerのindexを呼ぶ
Route::get("/users","UserController@index");

// URL  /posts/数字　のリクエストから　id　を引数にして　PostControllerのfunction showを呼ぶ
Route::get("posts/{id}","PostController@show");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route::resource　を使うことで　Restfull なURLを設定できる。
//  ※ /PostsにアクセスするとPostControllerのindexアクションが実行されるようになる。
//　　　また、ルーティングのURLと仕様するコントローラーを引数に渡すことができる
Route::resource('posts','PostController');

//以下の部分は　Cloud9　で開発している場合はhttpsでアセットを読み込む必要があるため
//APP_ENVの値がlocalである場合はhttps接続でアセットを読み込めるようにしている
if(env('APP_ENV')==="local"){
    URL::forcescheme("https");
}
