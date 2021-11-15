<!--

    プルダウン選択メニュー

-->



@extends('layouts.layout_ticket_list')

@section('title')
販売期間
@endsection

@section('layoutcss')
{{asset("css/ticket_list.css")}}
@endsection

@section('content')
<div class="select_section">
    <div class="name_section">チケット名を選択してください</div>
{{--     <form name="ticket_form" action="" method="POST" enctype= "multipart/form-data">
    @csrf
    <div class="pullfield">
        <select name="ticket_name" id="ticket_name" class="ticket_name" onchange="ticket_name_select(this)">
            <option>商品番号選択</option>
            @foreach($table as $index)
                <option value="{{ $index->ticket_name }}/{{ $index->tickets_kind }}">{{ $index->ticket_name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" id="sales_period_register_btn" class="btnstyle" >選択</button>
    <button type="submit" class="btnstyle" formaction="sales_period_index">戻る</button>
    </form> --}}
</div>

@endsection

{{-- <script>
    function ticket_name_select(obj){
        document.ticket_form.action="sales_period_register/"+obj.value;
    }

</script> --}}
