<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css')}}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <a href="/">
                <img class="logo" src="{{ asset('images/logo.svg') }}" alt="ロゴ">
            </a>
            <form class="header__search" action="/" method="get">
                <input class="header__search-input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？">
                <input type="hidden" name="tab" value="{{ request('tab') }}">
            </form>
            <nav class="header__nav">
                @auth
                    <form action="/logout" method="POST" class="nav-button-form">
                        @csrf
                        <button type="submit" class="logout-button">
                            ログアウト
                        </button>
                    </form>
                @endauth
                @guest
                    <a href="/login" class="login-button">
                        ログイン
                    </a>
                @endguest
                <a href="/mypage" class="mypage-button">
                    マイページ
                </a>
                <a href="/sell" class="sell-button">
                    出品
                </a>
            </nav>
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>