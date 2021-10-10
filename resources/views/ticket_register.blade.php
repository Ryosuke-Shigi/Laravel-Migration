@extends('layouts.course_select')

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("ＮＯ","商品番号","チケット名","金額","　　　　","　　　　"); ?>

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
                <form action="index3" method="POST" enctype= "multipart/form-data"> <!-- indexは仮 -->
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

                        <!-- $loop->index ループ中のインデックス情報を取得することができる  -->
                        <td class="itemtd">
                                @foreach($index->type_name as $type)
                                    {{ $index->type_name[$loop->index] }}:{{ $index->type_money[$loop->index] }}円<br>
                                @endforeach
                        </td>

{{--                         @if(isset($index->type_money[1]) && isset($index->type_name[1]))
                            <td class="itemtd">{{ $index->type_name[0]}}：{{ $index->type_money[0] }}円<br>
                                                {{ $index->type_name[1]}}：{{ $index->type_money[1] }}円</td>
                        @else
                            <td class="itemtd">{{ $index->type_name}}：{{ $index->type_money }}円<br>
                        @endif --}}


                        <td class="itemtd"><button type="submit" formaction="update_all/{{$index->ticket_code}}">編集</button></td>
                        <td class="itemtd"><button type="submit" formaction="delete_ticket_code_name/{{$index->ticket_code}}/{{$index->ticket_name}}">削除</button></td>


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
