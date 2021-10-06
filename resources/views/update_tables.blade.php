@extends('layouts.ticket_layouts')

@section('title')
チケット登録
@endsection

@section('style')
{{asset("css/update_tables_style.css")}}
@endsection

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("名称","単価","キャンセル料"); ?>

<!--メインコンテンツ-->
@section('contents')
<div class="container">
    <div class="datatitle">
        事業ID：{{ $table1[0]->biz_id}}　商品番号：{{ $table1[0]->biz_id}}　チケットネーム：{{ $table1[0]->ticket_name}}<br>
        『　編集画面　』
    </div>
    <form action="../update/{{$table1[0]->ticket_code}}" method="POST" enctype= "multipart/form-data">
    @csrf
    <div class="list">
            <div class="contenttitle">概要</div>
            <div class="itemname_line">ジャンル大</div>
            <div class="itemname_multi">
                <input type="checkbox" id="genre_code1" name="genre_code1" @if($table1[0]->genre_code1!=0) checked=true @endif>
                <label for="genre_code1">ジャンル大１</label>
            </div>
            <div class="itemname_line">ジャンル小</div>
            <div class="itemname_multi">
                <input type="checkbox" id="genre_code2" name="genre_code2" @if($table1[0]->genre_code2!=0) checked=true @endif>
                <label for="genre_code2">ジャンル小１</label>
            </div>
            <div class="itemname_line">チケット名</div>
            <div class="itemsector"><input disabled class="textbox" type="text" id="ticket_name" name="ticket_name" @if(isset($table1[0]->ticket_name)) value="{{ $table1[0]->ticket_name }}" @endif></div>
            <div class="itemname_line">概要</div>
            <div class="itemsector"><input class="textbox" type="text" id="overview" name="overview" @if(isset($table3[0]->contents_data)) value="{{ $table3[0]->contents_data }}" @endif></div>
            <div class="itemname_line">画像</div>
            <div class="itemsector"><input disabled class="textbox" type="text" id="overview_image" name="overview_image"></div><div class="buttonsector"><input type="file" class="filebtn" id="file_overview" onchange="overview_select(this)"></div>
        <div class="contenttitle">詳細</div>
            <div class="itemname_line">紹介</div>
            <div class="itemsector"><input disabled class="textbox" type="text" id="introduction" name="introduction"></div><div class="buttonsector"><input type="file" class="filebtn" id="file_intro" onchange="intro_select(this)"></div>
            <div class="itemname_line">重要注意事項</div>
            <div class="itemsector"><input class="textbox" type="text" id="important_notes" name="important_notes" @if(isset($table4[0]->cautions_text)) value="{{ $table4[0]->cautions_text }}" @endif></div>
            <div class="itemname_line">チケット紹介</div>
            <div class="itemsector"><input class="textbox" type="text" id="contents_data" name="contents_data" @if(isset($table3[1]->contents_data)) value="{{ $table3[1]->contents_data }}" @endif></div>
            <div class="itemname_line">紹介画像</div>
            <div class="itemsector"><input disabled class="textbox" type="text" id="contents_image" name="contents_image"></div><div class="buttonsector"><input type="file" class="filebtn" id="file_intro" onchange="contents_select(this)"></div>
            <div class="itemname_line">注意事項</div>
            <div class="itemsector"><input class="textbox" type="text" id="detail_notes" name="detail_notes" @if(isset($table4[1]->cautions_text)) value="{{ $table4[1]->cautions_text }}" @endif></div>
            <div class="itemname_line">お問合せ</div>
            <div class="itemsector"><input class="textbox" type="text" id="ticket_remarks" name="ticket_remarks" @if(isset($table1[0]->ticket_remarks)) value="{{ $table1[0]->ticket_remarks }}" @endif></div>

        <div class="contenttitle">商品設定</div>
            <div class="itemname_line">商品番号</div>
            <div class="itemsector"><input disabled class="textbox" type="text" id="ticket_code" name="ticket_code" @if(isset($table1[0]->ticket_code)) value="{{ $table1[0]->ticket_code }}" @endif></div>
            <div class="itemname_line">確認オプション</div>
            <div class="itemsector">
                <input type="checkbox" id="minors_flag" name="minors_flag" @if($table1[0]->minors_flag!=0) checked=true @endif>
                <label for="minors_flag">未成年フラグ</label>
            </div>
            <div class="itemname_line">キャンセル料<br>発生期限（分）</div>
            <div class="itemsector"><input class="textbox" type="text" id="cancel_limit" name="cancel_limit" @if(isset($table1[0]->cancel_limit)) value="{{ $table1[0]->cancel_limit }}" @endif></div>
            <div class="itemname_line">料金</div>

            <!--名称・金額・キャンセル料-->
            <table class = "tableitem">
                <tr class = "titletr">
            @foreach($tabletitles as $index)
                <td class="titletd">{{ $index }}</div></td><!--項目名-->
            @endforeach
                </tr>
            @foreach($table5 as $record)
                <tr>
                    <input type="hidden" name="id[]" value={{ $record->id }}>
                    <input type="hidden" name="type_id[]" value={{ $record->type_id }}>
                    <td class="itemtd"><input type="text" class="textbox" name="type_name[]" value={{ $record->type_name }}></td>
                    <td class="itemtd"><input type="text" class="textbox" name="type_money[]" value={{ $record->type_money }}></td>
                    <td class="itemtd"><input type="text" class="textbox" name="cancel_rate[]" value={{ $record->cancel_rate }}></td>
                </tr>
            @endforeach
        </table>

        <div class="itemname_line">注意事項</div>
        <div class="itemsector"><input class="textbox" type="text" id="item_notes" name="item_notes" @if(isset($table4[2]->cautions_text)) value="{{ $table4[2]->cautions_text }}" @endif></div>

        <div class="contenttitle">チケット設定</div>
        <input type="radio" name="tickets_kind" id="ticket_free" value="1" @if($table1[0]->tickets_kind==1) checked=true @endif><label for="ticket_free">フリーチケット</label>
        <input type="radio" name="tickets_kind" id="ticket_specified" value="2" @if($table1[0]->tickets_kind==2) checked=true @endif><label for="ticket_specified">指定チケット</label>

        <div class="contenttitle">サービス設定</div>
        <div class="itemname_line">サービス名</div>
        <div class="itemsector"><input class="textbox" type="text" id="svc_name" name="svc_name" @if(isset($table6[0]->svc_name)) value="{{ $table6[0]->svc_name }}" @endif></div>
        <div class="itemname_line">内容・注意事項</div>
        <div class="itemsector"><input disabled  class="textbox" type="text" id="svc_cautions01" name="service_content01" @if(isset($table6[0]->svc_cautions)) value="{{ $table6[0]->svc_cautions }}" @endif></div>
        <div class="itemname_line"></div>
        <div class="itemsector"><input disabled class="textbox" type="text" id="svc_cautions02" name="service_content02"></div><div class="buttonsector"><input type="file" class="filebtn" id="svc_file" onchange="svc_select(this)"></div>
        <div class="itemname_multi">サービス種類</div>
            <input type="radio" name="svc_type" id="svc_type01" value="1" @if($table6[0]->svc_type==1) checked=true @endif><label for="svc_type01">サービス１</label>
            <input type="radio" name="svc_type" id="svc_type02" value="2" @if($table6[0]->svc_type==2) checked=true @endif><label for="svc_type02">サービス２</label>
    </div>


    <div class="buttonarea"><button type="submit">編集</button></div>
    </form>
</div>

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


@endsection
