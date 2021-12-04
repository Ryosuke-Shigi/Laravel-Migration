@extends('layouts.ticket_layouts')

@section('title')
    @yield('title')
@endsection

@section('style')
    @yield('layoutcss')
@endsection

<!--メインコンテンツ-->
@section('contents')
<div class="container">

        <!--チケット登録画面-->
        @yield('content')

    </div>
</div>




@endsection
