<html>
    <head>
        <title>@yield('title')</title>  <!--@yieldで流し込む-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">   <!--js/app.jsをリンクする-->
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>

         <script src="{{ asset('js/app.js') }}"></script>   <!--js/app.jsを読み込む-->
    </body>
</html>
