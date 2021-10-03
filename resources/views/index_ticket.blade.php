@extends('layouts.ticket_layouts')

@section('title')
チケット登録
@endsection

@section('style')
{{asset("css/index_ticket_style.css")}}
@endsection

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("ＮＯ","商品番号","チケット名","金額","　　　　","　　　　"); ?>

<!--メインコンテンツ-->
@section('contents')
<div class="container">
    <div class="left">
            <form method="post">
            @csrf
                <div class="buttonsection">
                    <button type="submit" class="btn" formaction="index">チケット登録</button>
                    <button type="submit" class="btn" formaction="store">チケット登録２</button>
                </div>
            </form>
    </div>
    <div class="right">
        <!-- 新規登録ボタン -->
        <div class="buttonsection">
            <form method="post" name="newregister" action="store" enctype="multipart/form-data">
                @csrf
                <button method="POST" type="submit" class="newcreatebtn">新規登録</button>
            </form>
        </div>

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


                        <td class="itemtd"><button type="submit" formaction="update_types/{{$index->ticket_code}}">編集</button></td>
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
    </div>
</div>




@endsection
