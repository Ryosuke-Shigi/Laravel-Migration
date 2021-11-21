
<!DOCTYPE html>
<html lang="jp">
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="@yield('style')">
    <title>@yield('title')</title>
    </head>
    <body>
        <div class="wrapper">
            @yield('contents')
        </div>
    </body>
    @yield('js')
</html>
