@extends('layouts.sales_period.sales_period_index')

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("ＮＯ","チケット名","チケット種類","販売期間","販売枚数","　　　　","　　　　"); ?>

@section('title')
チケット登録
@endsection

@section('layoutcss')
{{asset("css/index_ticket_style.css")}}
@endsection

@section('content')
        <!-- 表 -->
        <div class="listfield">
            <table class = "tableitem">
                <form method="POST" enctype= "multipart/form-data"> <!-- indexは仮 -->
                @csrf
                    <!-- 一覧表項目 -->
                    <tr class = "titletr">
                    @foreach($tabletitles as $index)
                        <td class="titletd">{{ $index }}</td>
                    @endforeach

                    <!-- 表の中 -->
                    @foreach($table as $index)
                    @if($index->id !== 0)
                    <tr>
                        <td class="itemtd">{{ $index->biz_id }}</td>
                        <td class="itemtd">{{ $index->ticket_code }}</td>
                        <td class="itemtd">{{ $index->ticket_name }}</td>
                        <td class="itemtd">{{ $index->sales_interval_start }}<br>～{{ $index->sales_interval_end }}</td>
                        <td class="itemtd">{{ $index->ticket_num }}</td>
                        <!-- $loop->index ループ中のインデックス情報を取得することができる  -->



                        <td class="itemtd"><button type="submit" formaction="update_all/{{$index->ticket_code}}">編集</button></td>
                        <td class="itemtd"><button type="submit" formaction="delete_ticket_code_sales_period/{{$index->ticket_code}}">削除</button></td>


                    </tr>
                    @endif
                    @endforeach
                </form>
            </table>
        </div>
        <div class="listpage">
            {{ $table->links() }}
        </div>
@endsection
