<!--

    プルダウン選択メニュー

-->



@extends('layouts.sales_period.sales_period_select')

@section('title')
販売期間
@endsection

@section('layoutcss')
{{asset("css/index_ticket_style.css")}}
@endsection

@section('content')
<div class="select_section">
    <div class="name_section">商品番号</div>
    <form name="ticket_form" action="" method="POST" enctype= "multipart/form-data">
    @csrf
    <div class="pullfield">
        <select name="ticket_name" id="ticket_name" class="ticket_name">
            @foreach($table as $index)
                <option value="{{ $index->ticket_name }}">{{ $index->ticket_name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btnstyle" formaction="sales_period_register/{{ $index->ticket_name }}">選択</button>
    <button type="submit" class="btnstyle" formaction="sales_period_index">戻る</button>
    </form>
</div>

@endsection
