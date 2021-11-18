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
    <div class="contenttitle">チケット購入（POST)</div>
        <div class="itemname_line">user_id</div>
        <div class="itemsector"><input class="textbox" type="text" id="user_id" name="user_id" value="{{ old('ticket_name') }}"></div>
        <div class="itemname_line">biz_id</div>
        <div class="itemsector"><input class="textbox" type="text" id="biz_id" name="biz_id" value="{{ old('ticket_name') }}"></div>
        <div class="itemname_line">ticket_code</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_code" name="ticket_code" value="{{ old('ticket_name') }}"></div>
        <div class="itemname_line">sales_id</div>
        <div class="itemsector"><input class="textbox" type="text" id="sales_id" name="sales_id" value="{{ old('ticket_name') }}"></div>
        <div class="itemname_line">interval_start</div>
        <div class="itemsector"><input class="textbox" type="date" id="interval_start" name="interval_start" value="{{ old('ticket_name') }}"></div>
        <div class="price_section" id="inputarea">
            <div class="item_section">
                <div class="item_name">type_ID</div>
                <input class="item_text" type="text" id="type_id" name="type_id[]">
            </div>
            <div class="item_section">
                <div class="item_name">金額</div>
                <input class="item_text" type="text" id="type_money" name="type_money[]">
            </div>
            <div class="item_section">
                <div class="item_name">購入枚数</div>
                <input class="item_text" type="text" id="buy_num" name="buy_num[]">
            </div>

            <button type="button" class="addbtn" id="typesadd">追加</button>


        </div>
{{--         @foreach($errors->get('type_money.*') as $key=>$message)
            <!--〇〇番目の価格が入力されていません　など
                substr($key,strrpos($key,".")+1)+1 最後から . までの文字をとる
            -->
            <div class="errormsg">{{substr($key,strrpos($key,".")+1)+1}}番目：{{  $errors->first($key) }}</div>
        @endforeach --}}

    <!--登録・戻るボタン-->
    <div class="footer">
        <button class="button" type="submit" formaction="ticket_reserve">登録する</button>
        <button class="button" type="submit" formaction="index">戻る</button>
    </div>
</div>
</form>


@endsection

<!--HTML読込前にjavascript実行に入るとエラーが出る？？　deferを入れるとよい-->


<script>
    //追加ボタンを押したとき
/*       function addclick(){
        alert('onore-');
    }
 */
    //各ボタンのボタン要素を取得
    //概要のファイル
    //
    //  ファイルを選択すると　ファイル名がテキストに表示される
    //
    //
    function overview_select(obj){
        document.getElementById('overview_image').value=obj.value;
    }
    //詳細の　紹介ファイル
    function intro_select(obj){
        document.getElementById('introduction').value=obj.value;
    }
    //詳細の　紹介画像ファイル
    function contents_select(obj){
        document.getElementById('contents_image').value=obj.value;
    }
    //サービスの内容・注意事項のファイル
    function svc_select(obj){
        document.getElementById('svc_cautions02').value=obj.value;
    }
</script>
