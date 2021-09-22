@extends('layouts.ticket_layouts')

@section('title')
チケット登録
@endsection

@section('style')
{{asset("css/index_table3_style.css")}}
@endsection

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("システムＩＤ","事業者ＩＤ","商品番号","チケット紹介内容区分","チケット紹介内容index","チケット紹介内容"); ?>

<!--メインコンテンツ-->
@section('contents')
<div class="container">
        <div class="list">
        <table class = "tableitem">
            <tr class = "titletr">
            @foreach($tabletitles as $index)
                <td class="titletd">{{ $index }}</div></td>
            @endforeach
            @foreach($table as $record)
                <tr>
                    <td class="itemtd">{{ $record['id'] }}</td>
                    <td class="itemtd">{{ $record['biz_id'] }}</td>
                    <td class="itemtd">{{ $record['ticket_code'] }}</td>
                    <td class="itemtd">{{ $record['contents_type'] }}</td>
                    <td class="itemtd">{{ $record['contents_index'] }}</td>
                    <td class="itemtd">{{ $record['contents_data'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="page">
        {{ $table->links() }}
    </div>
</div>




@endsection
