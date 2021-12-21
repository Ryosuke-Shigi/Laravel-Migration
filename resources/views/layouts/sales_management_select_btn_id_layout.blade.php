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
                    <button type="submit" class="btn" formaction="ticket_list">チケット一覧</button>
                    <button type="submit" class="btn" formaction="view_ticket_reserve">購入（POST)</button>
                    <button type="submit" class="btn" formaction="sales_management_init">売上管理</button>
                    <button type="submit" class="btn" formaction="sales_management_select_btn_id_init">売上管理ID</button>
                </div>
            </form>
    </div>

    <div class="right">
        <!-- 新規登録ボタン -->
        <div class="buttonsection">
            <form method="post" name="sales_management" action="sales_management_list_select_btn_id" enctype="multipart/form-data">
                @csrf
                <table>
                    <tr>
                        <td>売 上 日 付（開始）</td>
                        <td></td>
                        <td>売 上 日 付（終了）</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input class="databox" type="date" id="interval_start" name="interval_start" value={{ $interval_start }}>
                        </td>
                        <td>～</td>
                        <td>
                            <input class="databox" type="date" id="interval_end" name="interval_end" value={{ $interval_end }}>
                        </td>
                        <td>
                            <button method="POST" type="submit" class="searchButton">検索</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>


        <!--チケット登録画面-->
        @yield('content')



    </div>
</div>




@endsection
