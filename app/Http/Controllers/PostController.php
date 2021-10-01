<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /*
    *指定された投稿のIDを表示
    *@param int $id
    *@return String
    */
    //POST内のSHOWに
    public function show($post){
        return view("posts.show",compact("post"));  //$post は　/posts/:idから自動的に該当する投稿データを取得している
    }                                               //compact("post")でviewに変数を渡している

    //indexでコントローラーにきたら
    public function index(){
        //return view('posts.index'); //viewのpostsのindex.blade.phpを返す

        $post=Post::all();     //作成されている投稿をすべて取得
        return view("posts.index",compact("posts"));
    }

    //indexでcreateをリクエストーーー
    public function create(){
        return view("posts.create");
    }

    public function stores(Request $request){
        $post=new Post();   //新しいPostモデルのデータを作成
        $post->title = $request->input("title");    //カラムごと受け取る
        $post->config=$request->input("content");
        $post->save();                              //モデルデータを保存

        ///posts/:idというURLへリダイレクト
        return redirect()->route("posts.show",["id"=>$post->id])->with("message","Post was successfully created.");
    }

    public function edit(Post $post){
        return view("posts.edit",compact("post"));
    }

    public function destroy(POst $post){
        $post->delete();
        return redirect()->route("posts.index");//indexへリダイレクト
    }

    public function update(Request $request,Post $post){
        $post->title=$request->input("title");
        $post->content=$request->input("content");
        $post->save();

        return redirect()->route("posts.show",["id"=>$post->id])->with("message","Post was successfully updated.");
    }

}
