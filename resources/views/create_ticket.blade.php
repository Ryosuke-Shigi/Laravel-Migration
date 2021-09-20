@extends('layouts.ticket_layouts')

@section('title')
新規登録画面
@endsection

@section('style')
{{asset("css/create_ticket_style.css")}}
@endsection

<!--メインコンテンツ-->
@section('contents')

<form method="post" action="create" enctype="multipart/form-data">
@csrf
<div class="container">
    <div class="contenttitle">概要</div>
        <div class="itemname_line">ジャンル大</div>
        <div class="itemname_multi"><input type="checkbox" id="genre_code1" name="genre_code1"><label for="genre_code1">ジャンル大１</label></div>
        <div class="itemname_line">ジャンル小</div>
        <div class="itemname_multi"><input type="checkbox" id="genre_code2" name="genre_code2"><label for="genre_code2">ジャンル小１</label></div>
        <div class="itemname_line">チケット名</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_name" name="ticket_name"></div>
        <div class="itemname_line">概要</div>
        <div class="itemsector"><input class="textbox" type="text" id="overview" name="overview"></div>
        <div class="itemname_line">画像</div>
        <div class="itemsector"><input class="textbox" type="text" id="overview_image" name="overview_image"></div><div class="buttonsector"></div>


    <div class="contenttitle">詳細</div>
        <div class="itemname_line">紹介</div>
        <div class="itemsector"><input class="textbox" type="text" id="introduction" name="introduction"></div><div class="buttonsector"></div>
        <div class="itemname_line">重要注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="important_notes" name="important_notes"></div>
        <div class="itemname_line">チケット紹介</div>
        <div class="itemsector"><input class="textbox" type="text" id="contents_type" name="contents_type"></div>
        <div class="itemname_line">紹介画像</div>
        <div class="itemsector"><input class="textbox" type="text" id="contents_image" name="contents_image"></div><div class="buttonsector"></div>
        <div class="itemname_line">注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="detail_notes" name="detail_notes"></div>
        <div class="itemname_line">お問合せ</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_remarks" name="ticket_remarks"></div>

    <div class="contenttitle">商品設定</div>
        <div class="itemname_line">商品番号</div>
        <div class="itemsector"><input class="textbox" type="text" id="ticket_code" name="ticket_code"></div><div class="buttonsector"></div>
        <div class="itemname_line">確認オプション</div>
        <div class="itemsector"><input type="checkbox" id="minors_flag" name="minors_flag"><label for="minors_flag">未成年フラグ</label></div><div class="buttonsector"></div>
        <div class="itemname_line">キャンセル料<br>発生期限（分）</div>
        <div class="itemsector"><input class="textbox" type="text" id="cancel_limit" name="cancel_limit"></div>
        <div class="itemname_line">料金</div>
        <div class="price_section">
            <div class="item_section">
                <div class="item_name">名称１</div>
                <input class="item_text" type="text" id="type_name01" name="type_name01">
            </div>
            <div class="item_section">
                <div class="item_name">単価１</div>
                <input class="item_text" type="text" id="type_money01" name="type_money01">
            </div>
            <div class="item_section">
                <div class="item_name">キャンセル料１</div>
                <input class="item_text" type="text" id="cancel_rate01" name="cancel_rate01">
            </div>
            <div class="item_section">
                <div class="item_name">名称２</div>
                <input class="item_text" type="text" id="type_name02" name="type_name02">
            </div>
            <div class="item_section">
                <div class="item_name">単価２</div>
                <input class="item_text" type="text" id="type_money02" name="type_money02">
            </div>
            <div class="item_section">
                <div class="item_name">キャンセル料２</div>
                <input class="item_text" type="text" id="cancel_rate02" name="cancel_rate02">
            </div>
        </div>
        <div class="itemname_line">注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="item_notes" name="item_notes"></div>

    <div class="contenttitle">チケット設定</div>
        <input type="radio" name="tickets_kind" id="ticket_free" value="1"><label for="ticket_free">フリーチケット</label>
        <input type="radio" name="tickets_kind" id="ticket_specified" value="2"><label for="ticket_specified">指定チケット</label>

    <div class="contenttitle">サービス設定</div>
    <div class="itemname_line">サービス名</div>
    <div class="itemsector"><input class="textbox" type="text" id="svc_name" name="svc_name"></div>
    <div class="itemname_line">内容・注意事項</div>
    <div class="itemsector"><input class="textbox" type="text" id="svc_cautions01" name="service_content01"></div>
    <div class="itemname_line"></div>
    <div class="itemsector"><input class="textbox" type="text" id="svc_cautions02" name="service_content02"></div><div class="buttonsector"></div>
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
