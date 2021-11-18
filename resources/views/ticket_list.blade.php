<!--

    プルダウン選択メニュー

-->



@extends('layouts.layout_ticket_list')

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("sales_id","チケット名","チケット紹介内容","注意事項",""); ?>

@section('title')
販売期間
@endsection

@section('layoutcss')
{{asset("css/ticket_list.css")}}
@endsection

@section('content')
<div class="select_section">
    <div class="name_section">チケット一覧</div>
    <div class="listfield">
        <table class = "tableitem">
                <!-- 一覧表項目 -->
                <tr class = "titletr">
                @foreach($tabletitles as $index)
                    <td class="titletd">{{ $index }}</td>
                @endforeach

                <!-- 表の中 -->
                @foreach($list2 as $index)
                    <tr>
                        <td class="itemtd">{{ $index['sales_id'] }}</td>
                        <td class="itemtd">{{ $index['ticket_name'] }}</td>
                        <td class="itemtd">
                            @foreach ($index['contents_data'] as $value)
                                {{ $value }}<br>
                            @endforeach
                        </td>
                        <td class="itemtd">
                            @foreach ($index['cautions_text'] as $value)
                                {{ $value }}<br>
                                {{-- <!--{{ $value['type_name'] }}:{{ $value['type_money'] }}<br>--> --}}
                            @endforeach
                        </td>
                        <td>
                            <button></button>
                        </td>
                    </tr>
                @endforeach
        </table>
</div>

@endsection

<script>
    function ticket_name_select(obj){
        document.ticket_form.action="sales_period_register/"+obj.value;
    }

</script>
