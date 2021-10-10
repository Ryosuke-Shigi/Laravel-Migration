@extends('layouts.ticket_layouts')

@section('title')
    @yield('title')
@endsection
@section('style')
    {{asset("css/ticket_register.css")}}
@endsection

@section('contents')

<div class="container">
    <div class="pagetitle">
        @if($table->tickets_kind==1)
            フリーチケット登録画面
        @else
            指定チケット登録画面
        @endif
    </div>
    <div class="item_name">商品番号</div>
    <div class="item_data">{{ $table->ticket_code }}</div>
    <div class="item_name">チケット販売種類</div>
    <div class="item_data">
        @if($table->tickets_kind==1)
            フリーチケット
        @else
            指定チケット
        @endif
    </div>
    <div class="item_name">サービス</div>
    <div class="item_data">{{ $table->svc_name }}</div>
    <div class="title">販売期間</div>
    <form action="index" method="POST" enctype= "multipart/form-data">
    @csrf

    <!-- 商品番号　チケット販売種類　を送信するために用意 -->
    <input type="hidden" name="ticket_code" value={{ $table->ticket_code }}>
    <input type="hidden" name="tickets_kind" value={{ $table->tickets_kind }}>

    <div class="input_field">
        <div class="item_field">
            <div class="item_name">販売日付（開始）</div>
            <input type="date" name="sales_interval_start_date" class="item_box"></input><!--tables02-->
        </div>
        <div class="item_field">
            <div class="item_name">販売時間（開始）</div>
            <input type="time" name="sales_interval_start_times" class="item_box"></input><!--tables02-->
        </div>
        <div class="item_field">
            <div class="item_name">販売日付（終了）</div>
            <input type="date" name="sales_interval_end_date" class="item_box"></input><!--tables02-->
        </div>
        <div class="item_field">
            <div class="item_name">販売時間（終了）</div>
            <input type="time" name="sales_interval_end_timess" class="item_box"></input><!--tables02-->
        </div>
    </div>

    <!--フリーチケット-->
    @if($table->tickets_kind==1)
        <div class="input_field">
            <div class="item_field">
                <div class="item_name">有効期限（開始）</div>
                <input type="date" name="ticket_interval_start" class="item_box"></input><!--tables07-->
            </div>
            <div class="item_field">
                <div class="item_name">有効期限（終了）</div>
                <input type="date" name="ticket_interval_end" class="item_box"></input><!--tables07-->
            </div>
        </div>
        <div class="input_field">
            <div class="item_field">
                <div class="item_name">販売枚数</div>
                <input type="text" name="ticket_num" class="item_box"></input><!--tables07-->
            </div>
            <div class="item_field">
                <div class="item_name">一枚あたりの最小枚数</div>
                <input type="text" name="ticket_min_num" class="item_box"></input><!--tables07-->
            </div>
            <div class="item_field">
                <div class="item_name">１枚あたりの最大枚数</div>
                <input type="text" name="ticket_max_num" class="item_box"></input><!--tables07-->
            </div>
        </div>

    @else<!-- 指定チケット -->
        <div class="input_field">
            <div class="item_field">
                <div class="item_name">有効期限</div>
                <input type="text" name="ticket_interval" class="item_box"></input>
            </div>
        </div>
        <div class="title">予約日</div>
        <div class="input_field">
            <div class="item_field">
                <div class="item_name">利用可能日</div>
                <input type="date" name="ticket_buy_date" class="item_box"></input>
            </div>
            <div class="item_field">
                <div class="item_name">販売枚数</div>
                <input type="text" name="ticket_num" class="item_box"></input>
            </div>
            <div class="item_field">
                <div class="item_name">１枚あたりの最小枚数</div>
                <input type="text" name="ticket_min_num" class="item_box"></input>
            </div>
            <div class="item_field">
                <div class="item_name">１枚あたりの最大枚数</div>
                <input type="text" name="ticket_max_num" class="item_box"></input>
            </div>

        </div>
    @endif
    <div class="footer">
        <button type="submit" class="btn" formaction="{{ asset('sales_period_create') }}">登録</button>
        <button type="submit" class="btn" formaction="{{ asset('sales_period') }}">戻る</button>
    </div>
    </form>
</div>

@endsection
