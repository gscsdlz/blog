<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="基于Redis、Laravel的NoSQL博客">
    <meta name="keywords" content="博客,Redis,Laravel,NoSQL">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{{ URL::asset('/i/favicon.png') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}">
</head>

<body>
<header class="am-topbar">
    <h1 class="am-topbar-brand">
        <a href="#">后台管理</a>
    </h1>
    <div class="am-collapse am-topbar-collapse am-topbar-right" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            <li><a href="#">退出登录</a></li>
        </ul>
    </div>
</header>

<footer data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
    <div class="am-footer-miscs ">
        <p>基于PHP-Laravel框架和Redis的博客，不使用MySQL，前端使用了AmazeUI的模板。</p>
        <p>服务器时间:{{ date('Y-m-d H:i:s', time()) }} 执行耗时:{{ printf("%0.3f", microtime(true) - LARAVEL_START) }}</div>
    </div>
</footer>
<script src="{{ URL::asset("/js/jquery.min.js") }}"></script>
<script src="{{ URL::asset("/js/amazeui.min.js") }}"></script>
</body>
</html>