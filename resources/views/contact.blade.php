
<!--

    Blade とは　Viewをつくるために用意されたテンプレートエンジン（見た目を作る手助けをしてくれる機能）

    データと、HTMLテンプレートを合わせて　ユーザに見せるＨＴＭＬをなんかアレする！

    そういうことをする領域！
-->
<!--layouts.appを継承-->
@extends('layouts.app')

@section('title', 'お問い合わせページ') <!--app.blade.php（原型）のtitleのところに　お問い合わせページ　を流し込めるのだ-->

@section('content')                   <!--なのでこの場合　app.blade.php（原型）のcontentというところにこの内容を流し込める-->
<div>
  <form>
    <div class="form-group">
    <label for="exampleFormControlInput1">メールアドレス</label>
      <input type="email" class="form-control" placeholder="name@example.com">
    </div>
    <div class="form-group">
    <label for="exampleFormControlTextarea1">お問い合わせ</label>
      <textarea class="form-control"rows="5"></textarea>
    </div>
  </form>
</div>
@endsection
