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
    <!-- サイドメニュー -->
    <div class="left">
            <form method="post">
            @csrf
                <div class="buttonsection">
                    @foreach($list as $index)
                        <button type="submit" class="btn" formaction="../ticket_list/{{ $index['ticket_name'] }}">{{ $index['ticket_name'] }}</button>
                    @endforeach
                </div>
            </form>
    </div>
    <!-- 右・上部メニュー -->
    <div class="right">
        <!-- 新規登録ボタン -->
        <div class="buttonsection">
            <form method="post" name="newregister" action="../index" enctype="multipart/form-data">
                @csrf
                <button method="POST" type="submit" class="returnbtn">戻る</button>
            </form>
        </div>


        <!--チケット登録画面-->
        @yield('content')



    </div>
</div>




@endsection
