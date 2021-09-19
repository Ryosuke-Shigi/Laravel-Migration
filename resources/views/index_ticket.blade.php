@extends('layouts.ticket_layouts')

@section('title')
チケット登録
@endsection

@section('style')
{{asset("css/index_ticket_style.css")}}
@endsection

<!--メインコンテンツ-->
@section('contents')
<div class="container">
    <div class="left">
        <p>チケット登録</p>
    </div>
    <div class="right">
        <div class="buttonfield">
            <form method="post" name="newregister" action="create">
                @csrf
                <button type="submit">新規登録</button>
            </form>
        </div>
        <div class="listfield">
            <p>登録したものをリスト表示</p>
        </div>
    </div>
</div>




@endsection
