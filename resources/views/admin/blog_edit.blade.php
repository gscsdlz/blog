@extends('admin.layout')
@section('title')新增文章@endsection
@section('main')
    <div class="am-g">
        <div class="am-u-md-4 am-u-md-centered">
            <form class="am-form">
                <div class="am-form-group">
                    <label for="title">标题</label>
                    <input type="text" class="" id="title" placeholder="请输入博客文章名">
                </div>
                <div class="am-form-group">
                    <label for="title">文章类型</label>
                    <select data-am-selected id="type">
                        <option value="-1">请选择文章类型</option>
                        @foreach($types as $t)
                            <option value="{{ $t[0] }}">{{ $t[0] }}</option>
                        @endforeach
                    </select>
                    <button id="addType" style="float: right;" class="am-btn am-btn-primary" type="button">新增文章分类</button>
                </div>
            </form>
        </div>
    </div>


    <div class="editormd" id="editormd">
        <textarea style="display:none;">### Hello Editor.md !</textarea>
    </div>
    <div class="am-g">
        <div class="am-u-md-4 am-u-md-centered">
            <button id="submit" class="am-btn am-btn-block am-btn-success" type="button">保存文章</button>
        </div>
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
            var editor = editormd({
                id   : "editormd",
                path : "{{ URL('ext/meditor/lib/') }}/",
                width : "85%",
                height : 777,
                imageUpload : true,
                imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                imageUploadURL : "{{ URL('admin/imgUpload') }}",
                saveHTMLToTextarea : true,
                emoji : true
            });

            $("#submit").click(function () {
                var title = $("#title").val();
                var type = $("#type").val();
                var text = editor.getHTML();
                var mdtext = editor.getMarkdown();
                if(title.length != 0 && text.length != 0 && type != -1 && mdtext.length != 0) {
                    $.post("{{ URL('admin/blog/add') }}", {
                        title:title,
                        type:type,
                        text:text,
                        mdtext:mdtext,
                        _token:"{{ csrf_token() }}"
                    }, function(data){
                        if(data.status == true) {
                            $("#info").html("保存成功！");
                            $("#alert").modal();
                            setTimeout(function(){
                                window.location.href = '/'
                            }, 3000)
                        }
                    })
                } else {
                    var str = '';
                    if(text.length == 0)
                        str += '<br/>内容为空！';
                    if(title.length == 0)
                        str += '<br/>标题为空！';
                    if(type == -1)
                        str += '<br/>必须选择类型！';
                    $("#info").html(str);
                    $("#alert").modal();
                }
            })

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
        })
    </script>
@endsection