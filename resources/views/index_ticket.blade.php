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
        <div class="buttonsection">
            <form method="post" name="newregister" action="store" enctype="multipart/form-data">
                @csrf
                <button type="submit" class="newcreatebtn">新規登録</button>
            </form>
        </div>
        <div class="listfield">
            @foreach($tabletitles as $index)
                <div class="tableitemtitle">{{ $index }}</div>
            @endforeach




        </div>
    </div>
</div>




@endsection
