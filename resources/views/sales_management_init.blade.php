@extends('layouts.sales_management_layout')

<!--コンテンツの表　タイトル-->
<?php $tabletitles=array("ＮＯ","商品番号","チケット名","金額","　　　　","　　　　"); ?>

@section('title')
チケット登録
@endsection

@section('layoutcss')
{{asset("css/sales_management.css")}}
@endsection

@section('content')
        <!-- 表 -->
        <div class="listfield">
            検索をクリック
        </div>
        <div class="listpage"></div>
@endsection
