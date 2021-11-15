<!--

    プルダウン選択メニュー

-->



@extends('layouts.layout_ticket_list')

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("biz_id","ticket_code","ticket_name","type_contents","ticket_types"); ?>

@section('title')
販売期間
@endsection

@section('layoutcss')
{{asset("css/ticket_list.css")}}
@endsection

@section('content')
<div class="select_section">
    <div class="name_section">リスト一覧表示</div>
    <div class="listfield">
        <table class = "tableitem">
                <!-- 一覧表項目 -->
                <tr class = "titletr">
                @foreach($tabletitles as $index)
                    <td class="titletd">{{ $index }}</td>
                @endforeach

                <!-- 表の中 -->
                @foreach($list as $index)
                @if($ticket_name==$index['ticket_name'])
                    <tr>
                        <td class="itemtd">{{ $index['biz_id'] }}</td>
                        <td class="itemtd">{{ $index['ticket_code'] }}</td>
                        <td class="itemtd">{{ $index['ticket_name'] }}</td>
                        <td class="itemtd">
                            @foreach ($index['ticket_contents'] as $value)
                                {{ $value['type_name'] }}<br>
                            @endforeach
                        </td>
                        <td class="itemtd">
                            @foreach ($index['ticket_types'] as $value)
                                {{ $value['type_name'] }}:{{ $value['type_money'] }}<br>
                            @endforeach
                        </td>
                    </tr>
                @endif
                @endforeach
        </table>
</div>

@endsection

<script>
    function ticket_name_select(obj){
        document.ticket_form.action="sales_period_register/"+obj.value;
    }

</script>
