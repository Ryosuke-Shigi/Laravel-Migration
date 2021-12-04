@extends('layouts.sales_management_layout')

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("ＮＯ","商品番号","チケット名","販売期間","券種","価格","キャンセル料","売上枚数","キャンセル料なし","キャンセル料あり","合計金額","　　　　"); ?>

@section('title')
チケット登録
@endsection

@section('layoutcss')
{{asset("css/sales_management.css")}}
@endsection

@section('content')

    <!-- 表 -->
    <div class="listfield">
        <form action="" method="POST" enctype= "multipart/form-data"> <!-- indexは仮 -->
            <table class = "tableitem">
                @csrf
                    <!-- 一覧表項目 -->
                    <tr class = "titletr">
                    @foreach($tabletitles as $index)
                        <td class="titletd">{{ $index }}</td>
                    @endforeach

                    <!-- 表の中 -->
                    @foreach($table['tickets'] as $index)
                    <tr>
                        <td class="itemtd">{{ $index['biz_id'] }}</td>
                        <td class="itemtd">{{ $index['ticket_code'] }}</td>
                        <td class="itemtd">{{ $index['ticket_name'] }}</td>
                        <td class="itemtdTime">{{ $index['sales_interval_start'] }}<br>～<br>{{ $index['sales_interval_end'] }}</td>
                        <td class="itemtd">{{ $index['type_name'] }}</td>
                        <td class="itemtd">{{ $index['type_money'] }}円</td>
                        <td class="itemtd">{{ $index['cancel_money'] }}</td>
                        <td class="itemtd">{{ $index['buy_num'] }}</td>
                        <td class="itemtd">{{ $index['no_cancel_num'] }}</td>
                        <td class="itemtd">{{ $index['cancel_num'] }}</td>
                        <td class="itemtdTotalmoney">{{ $index['total_money'] }}</td>
                        <td class="itemtd"><button type="button" formaction="">詳細</button></td>
                    </tr>
                    @endforeach
            </table>
        </form>
    </div>
    <div class="listpage">
        <form action="sales_management_list" method="post" name="salesmanagementPageform" enctype= "multipart/form-data">
            @csrf
            @if($table['lastpage']>1)
                <select name="page" id="salesmanagementPage" onchange="selectpage()">
                @for($i=1;$i<=$table['lastpage'];$i++)
                    @if($i==$page)
                        <option value={{ $i }} selected>{{ $i }}</option>
                    @else
                        <option value={{ $i }}>{{ $i }}</option>
                    @endif
                @endfor
                </select>
            @endif
        <input type="hidden" name="interval_start" value={{ $interval_start }}>
        <input type="hidden" name="interval_end" value={{ $interval_end }}>
    </form>
    </div>

@endsection

@section('js')
<script>
    function selectpage(){
        document.salesmanagementPageform.submit();
    }
</script>
@endsection
