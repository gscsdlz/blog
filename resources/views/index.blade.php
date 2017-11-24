<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="基于Redis、Laravel的NoSQL博客">
    <meta name="keywords" content="博客,Redis,Laravel,NoSQL">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>首页</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{{ URL::asset('/i/favicon.ico') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}">
    <script src="{{ URL::asset("/js/jquery.min.js") }}"></script>
    <script src="{{ URL::asset("/js/amazeui.min.js") }}"></script>
</head>

<body>
<header class="am-topbar">
    <h1 class="am-topbar-brand">
        <a href="#">{{ config('blog.blogName') }}</a>
    </h1>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            <li class="am-active"><a href="{{ URL('/') }}">首页</a></li>
            <li><a href="{{ URL('all') }}">全部文章</a></li>
            <li><a href="{{ URL('all_type') }}">文章分类</a></li>
            @foreach($navbar as $key => $value)
                <li><a href="{{ URL('type/'.$key.'/1') }}">{{ $key }}</a></li>
            @endforeach
        </ul>

        <form class="am-topbar-form am-topbar-right am-form-inline" role="search">
            <div class="am-form-group">
                <input type="text" class="am-form-field am-input-sm" placeholder="搜索">
            </div>
        </form>
    </div>
</header>
    <div class="am-g">
        <div class="am-u-md-8 am-u-md-offset-2">
            <div data-am-widget="slider" class="am-slider am-slider-c3" data-am-slider='{&quot;controlNav&quot;:false}' >
                <ul class="am-slides">
                    <li>
                        <img src="{{ URL('i/b1.jpg') }}">
                        <div class="am-slider-desc"><div class="am-slider-counter"><span class="am-active">1</span>/4</div>远方 有一个地方 那里种有我们的梦想</div>

                    </li>
                    <li>
                        <img src="http://s.amazeui.org/media/i/demos/bing-2.jpg">
                        <div class="am-slider-desc"><div class="am-slider-counter"><span class="am-active">2</span>/4</div>某天 也许会相遇 相遇在这个好地方</div>

                    </li>
                    <li>
                        <img src="http://s.amazeui.org/media/i/demos/bing-3.jpg">
                        <div class="am-slider-desc"><div class="am-slider-counter"><span class="am-active">3</span>/4</div>不要太担心 只因为我相信 终会走过这条遥远的道路</div>

                    </li>
                    <li>
                        <img src="http://s.amazeui.org/media/i/demos/bing-4.jpg">
                        <div class="am-slider-desc"><div class="am-slider-counter"><span class="am-active">4</span>/4</div>OH PARA PARADISE 是否那么重要 你是否那么地遥远</div>

                    </li>
                </ul>
            </div>
        </div>
        <div class="am-u-md-2">
            <canvas id="rightCanvas" width="100%" height="100%"></canvas>
        </div>
    </div>
    <hr/>
<div class="am-g">
    <div class="am-u-md-12">
        <div class="am-g">
            <div class="am-u-md-2">
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
            </div>
            <div class="am-u-md-2">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-bd">
                        <h2 class="am-text-center"><span>捐赠</span></h2>
                        <img src="{{ URL('images/web/qrcode.png') }}" width="100%"/>
                        <p>各位大佬如果觉得本博客对您有帮助，不如来包辣条？￣ω￣=</p>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<footer data-am-widget="footer" class="am-footer am-footer-default" data-am-footer="{  }">
    <div class="am-footer-miscs ">
        <p>基于PHP-Laravel框架和Redis的博客，不使用MySQL，前端使用了AmazeUI的模板。</p>
        <p>服务器时间:{{ date('Y-m-d H:i:s', time()) }} 执行耗时:{{ printf("%0.3f", microtime(true) - LARAVEL_START) }}</p>
    </div>
</footer>

</body>
</html>