@extends('layouts.ticket_layouts')

@section('title')
チケット登録
@endsection

@section('style')
{{asset("css/update_table5_style.css")}}
@endsection

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("名称","単価","キャンセル料"); ?>

<!--メインコンテンツ-->
@section('contents')
<div class="container">
    <div class="datatitle">
        事業ID：{{ $table[0]->biz_id}}　商品番号：{{ $table[0]->biz_id}}　チケットネーム：{{ $table[0]->ticket_name}}<br>
        『　編集画面　』
    </div>
    <div class="list">
        <table class = "tableitem">
            <tr class = "titletr">
            @foreach($tabletitles as $index)
                <td class="titletd">{{ $index }}</div></td>
            @endforeach
            <form action="../update/{{$table[0]->ticket_code}}" method="POST" enctype= "multipart/form-data">
            @csrf
            @foreach($table as $record)
                <tr>
                    <input type="hidden" name="id[]" value={{ $record->id }}>
                    <input type="hidden" name="type_id[]" value={{ $record->type_id }}>
                    <td class="itemtd"><input type="text" class="textbox" name="type_name[]" value={{ $record->type_name }}></td>
                    <td class="itemtd"><input type="text" class="textbox" name="type_money[]" value={{ $record->type_money }}></td>
                    <td class="itemtd"><input type="text" class="textbox" name="cancel_rate[]" value={{ $record->cancel_rate }}></td>
                </tr>

            @endforeach
        </table>
    </div>
    <div class="buttonarea"><button type="submit">編集</button></div>
    </form>
</div>




@endsection
