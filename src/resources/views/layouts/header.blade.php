<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}">
    @stack('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <a href="/">
                <img class="logo" src="{{ asset('images/logo.svg') }}" alt="ロゴ">
            </a>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>