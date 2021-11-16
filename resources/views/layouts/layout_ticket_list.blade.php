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

                        @if($loop->first)
                            <!-- 一番最初は同じボタンなどないので　作成 -->
                            <button type="submit" class="btn" formaction="../ticket_list/{{ $value['ticket_code'] }}">{{ $value['ticket_name'] }}</button>
                        @else
                            <!-- これ以前に　同名でボタンを作っていないか確認する -->
                            @for($i=0;$i<$index;$i++)
                                <!--もし同名であれば　ボタンを作らずfor文を抜ける-->
                                @if($value['ticket_name']==$list[$i]['ticket_name'])
                                    @break;
                                @endif
                                <!-- 上記条件にひっかからずラストまで来たら、同じチケット名はないので　ボタンをつくる -->
                                @if($i==$index-1)
                                    <button type="submit" class="btn" formaction="../ticket_list/{{ $value['ticket_code'] }}">{{ $value['ticket_name'] }}</button>
                                @endif
                            @endfor
                        @endif
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
