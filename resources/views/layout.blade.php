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
    <script src="{{ URL::asset("/js/jquery.min.js") }}"></script>
    <script src="{{ URL::asset("/js/amazeui.min.js") }}"></script>
    @if(isset($neededitorMD))
    <link rel="stylesheet" href="{{ URL::asset('/ext/meditor/css/editormd.preview.min.css') }}">
    <script src="{{ URL('ext/meditor/lib/marked.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/lib/prettify.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/lib/raphael.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/lib/underscore.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/lib/sequence-diagram.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/lib/flowchart.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/lib/jquery.flowchart.min.js') }}"></script>
    <script src="{{ URL('ext/meditor/editormd.min.js') }}"></script>
    @endif
</head>

<body>
<header class="am-topbar">
    <h1 class="am-topbar-brand">
        <a href="#">{{ config('blog.blogName') }}</a>
    </h1>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            <li @if(!isset($menu) || $menu == 'index')class="am-active"@endif><a href="{{ URL('/') }}">首页</a></li>
            <li @if(isset($menu) && $menu == 'blog@all')class="am-active"@endif><a href="{{ URL('all') }}">全部文章</a></li>
            <li @if(isset($menu) && $menu == 'typeAll')class="am-active"@endif><a href="{{ URL('all_type') }}">文章分类</a></li>
            @foreach($navbar as $key => $value)
                <li @if(isset($menu) && $menu == 'type@'.$key)class="am-active"<?php $find = true;?>@endif><a href="{{ URL('type/'.$key.'/1') }}">{{ $key }}</a></li>
            @endforeach
            @if(!isset($find) && isset($menu) && strpos($menu, 'type@') !== false)
                <li class="am-active"><a href="{{ URL(str_replace_first('@', '/', $menu).'/1') }}">{{ substr($menu, 5) }}</a></li>
            @endif
        </ul>

        <form class="am-topbar-form am-topbar-right am-form-inline" role="search">
            <div class="am-form-group">
                <input type="text" class="am-form-field am-input-sm" placeholder="搜索">
            </div>
        </form>
    </div>
</header>
<div class="am-g">
    <div class="am-u-md-6 am-u-md-offset-2">
        @yield('main')
    </div>

    <div class="am-u-md-2 am-u-end" id="aboutMe">
        @yield('rightArea')
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h2 class="am-text-center "><span>博主简介</span></h2>
            </div>
            <div class="am-panel-bd">
                <img class="am-radius" src="{{ URL("images/header.jpg") }}" width="100%" alt="about me" >
                <h2 class="am-text-center"><i><u>{{ config('blog.adminName') }}</u></i></h2>
                <p>
                    <?php $str = explode(':', config('blog.labels')) ?>
                    @foreach($str as $s)
                        <span class="am-badge am-badge-primary am-radius">{{ $s }}</span>
                    @endforeach
                </p>
                <p>{{ config('blog.aboutMe') }}</p>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-bd">
                <h2 class="am-text-center"><span>捐赠</span></h2>
                <img src="{{ URL('images/web/qrcode.png') }}" width="100%"/>
                <p>各位大佬如果觉得本博客对您有帮助，不如来包辣条？￣ω￣=</p>
            </div>
        </div>
    </div>
</div>
@yield('commit')
<footer data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
    <div class="am-footer-miscs ">
        <p>基于PHP-Laravel框架和Redis的博客，不使用MySQL，前端使用了AmazeUI的模板。</p>
        <p>服务器时间:{{ date('Y-m-d H:i:s', time()) }} 执行耗时:{{ printf("%0.3f", microtime(true) - LARAVEL_START) }}</p>
    </div>
</footer>

</body>
</html>