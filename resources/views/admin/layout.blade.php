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
    <link rel="icon" type="image/png" href="{{ URL::asset('/i/favicon.ico') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('ext/meditor/css/editormd.min.css') }}">
    <script src="{{ URL::asset("/js/jquery.min.js") }}"></script>
    <script src="{{ URL::asset("/js/amazeui.min.js") }}"></script>
    <script src="{{ URL::asset("/ext/meditor/editormd.min.js") }}"></script>
</head>

<body>
<header class="am-topbar">
    <h1 class="am-topbar-brand">
        <a href="#">后台管理</a>
    </h1>

    <div class="am-collapse am-topbar-collapse am-topbar-left">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            <li @if(isset($menu) && $menu == 'index')class="am-active" @endif><a href="{{ URL('admin/') }}">首页</a></li>
            <li @if(isset($menu) && $menu == 'blog@edit')class="am-active" @endif><a href="{{ URL('admin/blog/edit') }}">新增博客</a></li>
            <li @if(isset($menu) && $menu == 'blog@list')class="am-active" @endif><a href="{{ URL('admin/blog/list') }}">修改/删除博客</a></li>
            <li @if(isset($menu) && $menu == 'blog@type')class="am-active" @endif><a href="{{ URL('admin/type/edit') }}">分类管理</a></li>
            <li @if(isset($menu) && $menu == 'comments')class="am-active" @endif><a href="{{ URL('admin/comments/list') }}">评论管理</a></li>
            <li><a href="/">退出登录</a></li>
        </ul>
    </div>
</header>
@yield('main')
<footer data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
    <div class="am-footer-miscs ">
        <p>基于PHP-Laravel框架和Redis的博客，不使用MySQL，前端使用了AmazeUI的模板。</p>
        <p>服务器时间:{{ date('Y-m-d H:i:s', time()) }} 执行耗时:{{ printf("%0.3f", microtime(true) - LARAVEL_START) }}
    </div>
</footer>
</body>
</html>