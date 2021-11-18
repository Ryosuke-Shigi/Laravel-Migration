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
                    @php $flg=false; @endphp
                    @foreach($list as $index=>$value)
                            <!-- ボタン配置 -->
                            <button type="submit" class="btn" formaction="../ticket_list/{{ $value['ticket_code'] }}">{{ $value['ticket_name'] }}</button>
                    @endforeach
                </div>
            </form>
    </div>
    <!-- 右・上部メニュー -->
    <div class="right">
        <!-- 戻るボタン -->
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
