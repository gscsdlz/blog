<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="基于Redis、Laravel的NoSQL博客">
    <meta name="keywords" content="博客,Redis,Laravel,NoSQL">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{{ URL::asset('/i/favicon.png') }}">
    <link rel="stylesheet" href="{{ URL::asset('/css/amazeui.min.css') }}">
</head>

<body>
<div class="am-g" style="height:1000px;background: url({{ URL('images/web/login.jpg') }}) 0px 0px no-repeat">
    <div class="am-u-md-4 am-u-md-centered" style="margin-top: 300px">
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h1 class="am-text-center">博客后台管理登录</h1>
            </div>
            <div class="am-panel-bd">
                <form class="am-form-horizontal">
                    <div class="am-form-group" id="emailForm">
                        <label class="am-form-label am-u-sm-2">电子邮箱</label>
                        <div class="am-u-sm-10">
                            <input type="text" id="email" class="am-form-field am-radius" value="" placeholder="请输入后台管理员邮箱">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-form-label am-u-sm-2">邮箱提示</label>
                        <div class="am-u-sm-10">
                            <input type="text" class="am-form-field am-radius" value="{{ str_pad(substr($adminEmail, 0, 1), rand(4, 10), '*', STR_PAD_RIGHT) }}" readonly>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-form-label am-u-sm-2" >验证码</label>
                        <div class="am-u-sm-7">
                            <input type="text" id="vcode" class="am-form-field am-radius" value="">
                        </div>
                        <div class="am-u-sm-1 am-u-end">
                            <button class="am-btn am-btn-primary" type="button" id="send">发送</button>
                        </div>
                    </div>
                    <button class="am-btn am-btn-danger am-btn-block" type="button" id="signIn">登录</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="am-modal am-modal-alert" tabindex="-1" id="alert">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提醒</div>
        <div class="am-modal-bd">
            <p class="info am-danger" id="info"></p>
        </div>
        <div class="am-modal-footer">
            <span class="am-modal-btn">确定</span>
        </div>
    </div>
</div>
<script src="{{ URL::asset("/js/jquery.min.js") }}"></script>
<script src="{{ URL::asset("/js/amazeui.min.js") }}"></script>
<script>
    $(document).ready(function () {

        $("#send").click(function () {
            var email = $("#email").val();
            if(email.length != 0) {
                $.post("{{ URL('admin/checkEmail') }}", {email:email, _token:"{{ csrf_token() }}"}, function(data){
                    if(data.status == false) {
                        if(data.info == 'TError') {
                            $("#info").html("请勿重复尝试！")
                        } else if(data.info == 'EError'){
                            $("#info").html("邮箱填写错误")
                            $(this).html("发送");
                            $("#email").val("");
                            $("#email").attr('placeholder',"邮箱填写错误，请重试!");
                        } else if(data.info == 'SError')
                            $("#info").html("邮件服务器出现异常，发送失败！");
                    } else {
                        $(this).html("再次发送");
                        $("#info").html("邮件发送成功，请注意查收");
                    }
                })
                $("#info").html("请稍等...")
                $("#alert").modal();
            }
        })

        $("#signIn").click(function(){
            var vcode = $("#vcode").val();
            if(vcode.length != 0) {
                $.post("{{ URL('admin/login') }}", {vcode: vcode, _token: "{{ csrf_token() }}"}, function (data) {
                    if (data.status == true)
                        window.location.href = "{{ URL('admin/') }}";
                    else {
                        if (data.info != 'TError') {
                            $("#info").html("验证码错误");
                            $("#alert").modal();
                        } else {
                            $("#info").html("验证码已经过期，请重新发送验证码!");
                            $("#alert").modal();
                            window.setTimeout('window.location.reload()', 3000);
                        }
                    }
                })
            }
        })
    })
</script>
</body>
</html>