@extends('layouts.ticket_layouts')

@section('title')
新規登録画面
@endsection

@section('style')
{{asset("css/create_ticket_style.css")}}

@endsection

<!--メインコンテンツ-->
@section('contents')
<script src="{{ asset('js/app.js') }}" defar></script>   <!--js/app.jsを読み込む-->
<form method="post" action="create" enctype="multipart/form-data">
@csrf

<div class="container">
    <div class="contenttitle">概要</div>
        <div class="itemname_line">ジャンル大</div>
        <div class="itemname_multi"><input type="checkbox" id="genre_code1" name="genre_code1"><label for="genre_code1">ジャンル大１</label></div>
        <div class="itemname_line">ジャンル小</div>
        <div class="itemname_multi"><input type="checkbox" id="genre_code2" name="genre_code2"><label for="genre_code2">ジャンル小１</label></div>
        <div class="itemname_line">チケット名</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_name" name="ticket_name" value="{{ old('ticket_name') }}"></div>
        @if($errors->first('ticket_name')!=NULL)
            <div class="errormsg">※ {{ $errors->first('ticket_name') }}</div>
        @endif
        <div class="itemname_line">概要</div>
        <div class="itemsector"><input class="textbox" type="text" id="overview" name="overview" value="{{ old('overview') }}"></div>
        <div class="itemname_line">画像</div>
        <div class="itemsector"><input disabled class="textbox" type="text" id="overview_image" name="overview_image" value="{{ old('overview_image') }}"></div><div class="buttonsector"><input type="file" class="filebtn" id="file_overview" onchange="overview_select(this)"></div>
    <div class="contenttitle">詳細</div>
        <div class="itemname_line">紹介</div>
        <div class="itemsector"><input disabled class="textbox" type="text" id="introduction" name="introduction" value="{{ old('introduction') }}"></div><div class="buttonsector"><input type="file" class="filebtn" id="file_intro" onchange="intro_select(this)"></div>
        <div class="itemname_line">重要注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="important_notes" name="important_notes" value="{{ old('important_notes') }}"></div>
        <div class="itemname_line">チケット紹介</div>
        <div class="itemsector"><input class="textbox" type="text" id="contents_data" name="contents_data" value="{{ old('contents_data') }}"></div>
        <div class="itemname_line">紹介画像</div>
        <div class="itemsector"><input disabled class="textbox" type="text" id="contents_image" name="contents_image" value="{{ old('contents_image') }}"></div><div class="buttonsector"><input type="file" class="filebtn" id="file_intro" onchange="contents_select(this)"></div>
        <div class="itemname_line">注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="detail_notes" name="detail_notes" value="{{ old('detail_notes') }}"></div>
        <div class="itemname_line">お問合せ</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_remarks" name="ticket_remarks" value="{{ old('ticket_remarks') }}"></div>

    <div class="contenttitle">商品設定</div>
        <div class="itemname_line">商品番号</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_code" name="ticket_code" value="{{ old('ticket_code') }}"></div>
        @if($errors->first('ticket_code')!=NULL)
            <div class="errormsg">※ {{ $errors->first('ticket_code') }}</div>
        @endif
        <div class="itemname_line">確認オプション</div>
        <div class="itemsector"><input type="checkbox" id="minors_flag" name="minors_flag"><label for="minors_flag">未成年フラグ</label></div>
        <div class="itemname_line">キャンセル料<br>発生期限（分）</div>
        <div class="itemsector"><input class="textbox" type="text" id="cancel_limit" name="cancel_limit" value = "{{ old('cancel_limit') }}"></div>
        @if($errors->first('cancel_limit')!=NULL)
            <div class="errormsg">※ {{ $errors->first('cancel_limit') }}</div>
        @endif
        <div class="itemname_line">料金</div>




        <div class="price_section" id="inputarea">
            <div class="item_section">
                <div class="item_name">名称</div>
                <input class="item_text" type="text" id="type_name01" name="type_name[]">
            </div>
            <div class="item_section">
                <div class="item_name">単価</div>
                <input class="item_text" type="text" id="type_money01" name="type_money[]">
            </div>
            <div class="item_section">
                <div class="item_name">キャンセル料</div>
                <input class="item_text" type="text" id="cancel_rate01" name="cancel_rate[]">
            </div>

            <button type="button" class="addbtn" id="itemadd">追加</button>
        </div>
        @foreach($errors->get('type_money.*') as $key=>$message)
            <!--〇〇番目の価格が入力されていません　など
                substr($key,strrpos($key,".")+1)+1 最後から . までの文字をとる
            -->
            <div class="errormsg">{{substr($key,strrpos($key,".")+1)+1}}番目：{{  $errors->first($key) }}</div>
        @endforeach



    <div class="itemname_line">注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="item_notes" name="item_notes" value="{{ old('item_notes') }}"></div>

    <div class="contenttitle">チケット設定</div>
        <input type="radio" name="tickets_kind" id="ticket_free" value="1"><label for="ticket_free">フリーチケット</label>
        <input type="radio" name="tickets_kind" id="ticket_specified" value="2"><label for="ticket_specified">指定チケット</label>

    <div class="contenttitle">サービス設定</div>
    <div class="itemname_line">サービス名</div>
    <div class="itemsector"><input class="textbox" type="text" id="svc_name" name="svc_name" value="{{ old('svc_name') }}"></div>
    <div class="itemname_line">内容・注意事項</div>
    <div class="itemsector"><input class="textbox" type="text" id="svc_cautions01" name="service_content01" value="{{ old('service_content01') }}"></div>
    <div class="itemname_line"></div>
    <div class="itemsector"><input disabled class="textbox" type="text" id="svc_cautions02" name="service_content02" value="{{ old('service_content02') }}"></div><div class="buttonsector"><input type="file" class="filebtn" id="svc_file" onchange="svc_select(this)"></div>
    <div class="itemname_multi">サービス種類</div>
        <input type="radio" name="svc_type" id="svc_type01" value="1"><label for="svc_type01">サービス１</label>
        <input type="radio" name="svc_type" id="svc_type02" value="2"><label for="svc_type02">サービス２</label>

    <!--登録・戻るボタン-->
    <div class="footer">
        <button class="button" type="submit">登録する</button>
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
