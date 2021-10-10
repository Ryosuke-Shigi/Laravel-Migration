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
    <div class="left">
            <form method="post">
            @csrf
                <div class="buttonsection">
                    <button type="submit" class="btn" formaction="index">チケット登録</button>
                    <button type="submit" class="btn" formaction="sales_period_index">販売期間登録</button>
                </div>
            </form>
    </div>

    <div class="right">
        <!-- 新規登録ボタン -->
        <div class="buttonsection">
            <form method="post" name="newregister" action="sales_period" enctype="multipart/form-data">
                @csrf
                <button method="POST" type="submit" class="newcreatebtn">新規登録</button>
            </form>
        </div>


        <!--チケット登録画面-->
        @yield('content')



    </div>
</div>




@endsection
