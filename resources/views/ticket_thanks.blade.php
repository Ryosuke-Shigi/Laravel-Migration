@extends('layouts.ticket_layouts')

@section('title')
新規登録画面
@endsection

@section('style')
{{asset("css/ticket_reserve_style.css")}}

@endsection

<!--メインコンテンツ-->
@section('contents')
<script src="{{ asset('js/app.js') }}" defar></script>   <!--js/app.jsを読み込む-->
<form method="post" action="create" enctype="multipart/form-data">
@csrf

<div class="container">
    <div class="Messages">
        チケット購入ありがとうございました
    </div>

    <div class="footer">
        <button class="button" type="submit" formaction="../../ticket_list">チケット一覧に戻る</button>
    </div>
</div>
</form>


@endsection

<!--HTML読込前にjavascript実行に入るとエラーが出る？？　deferを入れるとよい-->
