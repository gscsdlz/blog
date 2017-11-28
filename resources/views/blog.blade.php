@extends('layout')
@section('title')
{{ $blog->title }}
@endsection
@section('rightArea')
    <div class="am-panel am-panel-default">
        <div class="am-panel-bd">
            <ul class="am-list am-list-static">
                <li><span class="am-badge">{{ date('Y-m-d H:i', $blog->time) }}</span>创建时间</li>
                <li><span class="am-badge am-badge-primary">{{ $blog->type }}</span>所属类别</li>
                <li><span class="am-badge am-badge-danger">{{ $blog->view }}</span>浏览次数</li>
                <li><span class="am-badge">{{ date('Y-m-d H:i', $blog->updateTime) }}</span>上次修改时间</li>
                <li><span class="am-badge">{{ $blog->updateCount }}</span>修改次数</li>
            </ul>
        </div>
    </div>
@endsection
@section('main')
    <div data-am-sticky style="float: right">
        <span data-am-popover="{content: '回到顶部', trigger: 'hover focus'}" data-am-smooth-scroll class="am-icon am-icon-arrow-up am-icon-sm" ></span>&nbsp;&nbsp;
        <span data-am-popover="{content: '添加留言', trigger: 'hover focus'}"  onclick="var $w = $(window); $w.smoothScroll({position: $(document).height() - $w.height()});" class="am-icon am-icon-comment am-icon-sm"></span>
    </div>
    <div id="view">
        <textarea style="display:none;" name="markdown-doc">###Hello world!</textarea>
    </div>
<script>
    var EditorView;
    @if(isset($blog) && $blog->visible == 1)
    $.get("{{ URL('file/get_markdown/'.$blog->mdtextPath) }}", function(markdown) {

        EditormdView = editormd.markdownToHTML("view", {
            markdown        : markdown ,//+ "\r\n" + $("#append-test").text(),
            htmlDecode      : "style,script,iframe",  // you can filter tags decode
            tocm            : true,    // Using [TOCM]
            emoji           : true,
            taskList        : true,
            tex             : true,  // 默认不解析
            flowChart       : true,  // 默认不解析
            sequenceDiagram : true,  // 默认不解析
        });
    });
    @else
        EditormdView = editormd.markdownToHTML("view", {
        markdown        : "# 该博客已经隐藏无法查看" ,//+ "\r\n" + $("#append-test").text(),
    });
    @endif
</script>
@endsection
@section('commit')
    <hr/>
    <div class="am-g">
        <div class="am-u-md-6 am-u-md-centered">
            <ul class="am-comments-list am-comments-list-flip">
                @foreach($comments as $c)
                <li class="am-comment @if(isset($c['isAdmin']) && $c['isAdmin'] == 1) am-comment-flip am-comment-highlight @endif" >
                    <div class="am-comment-main">
                        <header class="am-comment-hd">
                            <div class="am-comment-meta">
                                <a href="#" class="am-comment-author">
                                    @if(isset($c['isAdmin']) && $c['isAdmin'] == 1)
                                        {{ $c['email'] }} 回复于 <time>{{ $c['time'] }}</time>
                                    @else
                                        {{ substr($c['email'], 0, 2) }}********{{ substr($c['email'], strlen($c['email']) - 3) }}
                                        评论于 <time>{{  $c['time'] }}</time>
                                    @endif</a>

                            </div>
                        </header>

                        <div class="am-comment-bd">
                            {{ $c ['text'] }}
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="am-g">
        <div class="am-u-md-4 am-u-md-centered">
            <form class="am-form">
                <div class="am-form-group">
                    <label class="am-form-label">电子邮箱</label>
                    <input type="email" id="email" class="am-form-field" value="" placeholder="电子邮件仅用于通知您我的回复 显示时 不完全显示"/>
                </div>
                <div class="am-form-group">
                    <label class="am-form-label">留言内容</label>
                    <textarea class="am-form-field" id="text" rows="4" cols="4"></textarea>
                </div>
                <button class="am-btn am-btn-primary" type="button" id="submit">发表留言</button>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#submit").click(function(){
                var email = $("#email").val();
                var text = $("#text").val();

                if(text.length == 0) {
                   alert("留言内容不可为空");
                } else {
                    $.post("{{ URL("comments") }}", {email:email, text:text, _token:"{{ csrf_token() }}"}, function (data) {
                        if(data.status == true)
                            alert("提交成功")
                        else
                            alert("提交失败")
                    })
                }

            })
        })
    </script>
@endsection
