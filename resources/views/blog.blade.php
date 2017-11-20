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
    $.get("{{ URL('file/get_markdown/'.$blog->mdtextPath) }}", function(markdown) {

        EditormdView = editormd.markdownToHTML("view", {
            markdown        : markdown ,//+ "\r\n" + $("#append-test").text(),
            //htmlDecode      : true,       // 开启 HTML 标签解析，为了安全性，默认不开启
            htmlDecode      : "style,script,iframe",  // you can filter tags decode
            //toc             : false,
            tocm            : true,    // Using [TOCM]
            //tocContainer    : "#custom-toc-container", // 自定义 ToC 容器层
            //gfm             : false,
            //tocDropdown     : true,
            // markdownSourceCode : true, // 是否保留 Markdown 源码，即是否删除保存源码的 Textarea 标签
            emoji           : true,
            taskList        : true,
            tex             : true,  // 默认不解析
            flowChart       : true,  // 默认不解析
            sequenceDiagram : true,  // 默认不解析
        });

        //console.log("返回一个 jQuery 实例 =>", testEditormdView);

        // 获取Markdown源码
        //console.log(testEditormdView.getMarkdown());

        //alert(testEditormdView.getMarkdown());
    });
</script>
@endsection
@section('commit')
    <hr/>
    <div class="am-g">
        <div class="am-u-md-4 am-u-md-centered">
            <form class="am-form">
                <div class="am-form-group">
                    <label class="am-form-label">电子邮箱</label>
                    <input type="email" class="am-form-field" value="" placeholder="电子邮件仅用于通知您我的回复"/>
                </div>
                <div class="am-form-group">
                    <label class="am-form-label">留言内容</label>
                    <textarea class="am-form-field" rows="4" cols="4"></textarea>
                </div>
                <button class="am-btn am-btn-primary" type="button">发表留言</button>
            </form>
        </div>
    </div>
@endsection
