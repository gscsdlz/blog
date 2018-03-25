@extends('admin.layout')
@section('title')@if(isset($bid))编辑文章@else新增文章@endif @endsection
@section('main')
    <div class="am-g">
        <div class="am-u-md-4 am-u-md-centered">
            <form class="am-form">
                <div class="am-form-group">
                    <label for="title">标题</label>
                    <input type="text" class=""  value="@if(isset($blog)){{ $blog->title }}@endif" id="title" placeholder="请输入博客文章名">
                </div>
                <div class="am-form-group">
                    <label for="title">文章类型</label>
                    <select data-am-selected id="type">
                        <option value="-1">请选择文章类型</option>
                        @foreach($types as $t)
                            @if(isset($blog) && $blog->type == $t[0])
                                <option selected value="{{ $t[0] }}">{{ $t[0] }}</option>
                            @else
                                <option value="{{ $t[0] }}">{{ $t[0] }}</option>
                            @endif
                        @endforeach
                    </select>
                    <button id="addType" style="float: right;" class="am-btn am-btn-primary" type="button">新增文章分类</button>
                </div>
            </form>
        </div>
    </div>


    <div class="editormd" id="editormd">
        <textarea style="display:none;">
# 欢迎使用Editor.md!!!
</textarea>
    </div>

    <hr/>
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
    <div class="am-modal am-modal-prompt" tabindex="-1" id="prompt">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">输入</div>
            <div class="am-modal-bd">
                请输入新的类别
                <input type="text" class="am-modal-prompt-input">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>提交</span>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            window.onbeforeunload = function() {
                localStorage.removeItem("markdown_str");
                return "";
            }
            var editor = editormd({
                id   : "editormd",
                path : "{{ URL('ext/meditor/lib/') }}/",
                width : "85%",
                height : 777,
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "{{ URL('admin/imgUpload') }}",
                saveHTMLToTextarea : true,
                emoji : true,
                toolbarIcons : function() {
                    return ["undo", "redo", "saveIcon", "|",
                        "bold", "del", "italic", "quote", "ucwords", "uppercase", "lowercase", "|",
                        "h1", "h2", "h3", "h4", "h5", "h6", "|",
                        "list-ul", "list-ol", "hr", "|",
                        "link", "reference-link", "image", "code", "preformatted-text", "code-block", "table", "datetime", "emoji", "html-entities", "pagebreak", "|",
                        "goto-line", "watch", "preview", "fullscreen", "clear", "search", "|",
                        "help", "info"]
                },
                toolbarIconsClass : {
                    saveIcon : "fa-save"  // 指定一个FontAawsome的图标类
                },
                toolbarHandlers : {
                    saveIcon : function(cm, icon, cursor, selection) {
                        var title = $("#title").val();
                        var type = $("#type").val();
                        var mdtext = editor.getMarkdown();
                        if(title.length != 0  && type != -1 && mdtext.length != 0) {
                            $.post("{{ URL('admin/blog/add') }}", {
                                title:title,
                                type:type,
                                mdtext:mdtext,
                                @if(isset($bid))blogID:{{ $bid }},@endif
                                _token:"{{ csrf_token() }}"
                            }, function(data){
                                if(data.status == true) {
                                    $("#info").html("保存成功！");
                                    $("#alert").modal();
                                    //保存并且成功清除本地缓存
                                    localStorage.removeItem('markdown_str');
                                    @if(!isset($bid))
                                    setTimeout(function(){
                                        window.location.href = "{{ URL('admin/blog/edit') }}/" + data.blogID;
                                    }, 3000)
                                    @endif
                                } else {
                                    $("#info").html("保存失败！请刷新页面后重试")
                                    $("#alert").modal();
                                }
                            })
                        } else {
                            var str = '';
                            if(title.length == 0)
                                str += '<br/>标题为空！';
                            if(type == -1)
                                str += '<br/>必须选择类型！';
                            $("#info").html(str);
                            $("#alert").modal();
                        }
                    },
                },
                lang : {
                    toolbar : {
                        saveIcon : "保存"
                    }
                },
                onload : function() {
                    var target = this;
                    @if(isset($blog))


                    $.get("{{ URL('file/get_markdown/'.$blog->mdtextPath) }}", function(data) {
                        var str = localStorage.getItem('markdown_str');
                        if(str == null)
                            target.setMarkdown(data)
                        else if(str.length != 0 )
                            target.setMarkdown(str);
                    })
                    @endif
                }
            });

            $("#addType").click(function(){
                $("#prompt").modal({
                    onConfirm:function(e){
                        if(e.data.length != 0) {
                            $("#type").append('<option value="'+e.data+'">'+e.data+'</option>');
                            $("#type").val(e.data);
                        } else {
                            alert("输入不能为空！");
                        }
                    }
                })
            })
            @if(isset($blog))
            window.setInterval(function(){
                localStorage.setItem('markdown_str', editor.getMarkdown());
            }, 5000);
            @endif

        })
    </script>
@endsection