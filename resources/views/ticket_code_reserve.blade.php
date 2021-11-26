@extends('layouts.ticket_layouts')

@section('title')
新規登録画面
@endsection

@section('style')
{{asset("css/ticket_code_reserve_style.css")}}

@endsection

<!--メインコンテンツ-->
@section('contents')
<script src="{{ asset('js/app.js') }}" defar></script>   <!--js/app.jsを読み込む-->
<form method="post" action="" enctype="multipart/form-data">
@csrf

<div class="container">
    <div class="contenttitle">sales_id:{{ $sales_id }}　ticket_code:{{ $ticket_code }}　のチケット購入（POST）</div>
    <!-- types -->
    @foreach($values['tickets'] as $index=>$value)
        <div class="itemname_line">{{ $value['type_name'] }}</div>
        <div class="itemsector"><input class="textbox" type="text" id="money" name="type_money[]" readonly=true value={{ $value['type_money'] }}>円</div>
        <input type="number" onchange="sum_money()" class="buy_num" id="buy_num[]" name="buy_num[]" max={{ $values['ticket_max_num'] }} min={{ $values['ticket_min_num'] }} value={{ old('buy_num.'.$index) }}>
        <input type="hidden" name="type_id[]" value={{ $value['type_id'] }}>
        <input type="hidden" name="ticket_max_num" value = {{ $values['ticket_max_num'] }}>
        <input type="hidden" name="ticket_min_num" value = {{ $values['ticket_min_num'] }}>
    @endforeach
    <div class="error_message">
        <!-- $errors->all()でまとめてとれる -->
        @foreach($errors->messages() as $index=>$error)
            {{ $error[0] }}<br>
        @endforeach
    </div>
    <div class="itemname_line">現在価格</div>
    <div class="itemsector"><input class="textbox2" type="text" id="buy_money" name="buy_money" readonly=true>円</div>
    <input type="hidden" name="ticket_interval_start" value={{ $values['ticket_interval_start'] }}>

    <!--登録・戻るボタン-->
    <div class="footer">
        <button class="button" type="submit" formaction="../../ticket_code_reserve/{{$ticket_code}}/{{$sales_id}}">購入する</button>
        <button class="button" type="submit" formaction="../../ticket_list">戻る</button>
    </div>
</div>
</form>

@endsection

@section('js')
    <script>
        //初期金額表示
        let totalNum = 0;
        //金額　複数　「クラス」で取得
        let buy_money = document.getElementById('buy_money');
        //金額
        let money = document.getElementsByClassName('textbox');
        //購入数
        let buy_num = document.getElementsByClassName('buy_num');
        for(let i=0;i<money.length;i++){
            totalNum += buy_num[i].value*money[i].value;
        }
        buy_money.value = totalNum;



        //購入数を変更したらonchange="sum_mone()"で合計金額を出す
        function sum_money(){
            totalNum = 0;
            for(let i=0;i<money.length;i++){
                totalNum += buy_num[i].value*money[i].value;
            }
            buy_money.value = totalNum;
        }


    </script>
@endsection
<!--HTML読込前にjavascript実行に入るとエラーが出る？？　deferを入れるとよい-->
