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
</div>

@endsection
