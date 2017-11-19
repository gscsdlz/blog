@extends('admin.layout')
@section('main')
    <div class="am-g">
        <div class="am-u-md-4 am-u-md-offset-2">
            <form class="am-form am-form-horizontal">
                <div class="am-form-group">
                    <div class="am-u-md-4">
                        <input type="text" id="newName" value="" class="am-form-field" placeholder="输入新的类型名"/>
                    </div>
                    <button class="am-btn am-btn-primary" type="button" id="insert">新增</button>
                </div>

            </form>

            <table class="am-table">
                <tr>
                    <th>编号</th>
                    <th>类型名称</th>
                    <th>类型文章数</th>
                    <th>操作</th>
                </tr>
                <?php $i = 1;?>
                @foreach($types as $t)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $t[0] }}</td>
                        <td>{{ $t[1] - 1 }}</td>
                        <td>
                            <span style="cursor: pointer" data-am-popover="{content: '删除该类型', trigger: 'hover focus'}" class="am-icon-fw am-icon am-icon-trash am-icon-sm"></span> |
                            <span style="cursor: pointer" data-am-popover="{content: '修改类型名称', trigger: 'hover focus'}" class="am-icon-fw am-icon am-icon-edit am-icon-sm"></span>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="am-u-md-4 am-u-end am-text-center">
            <h3 class="am-text-center">首页导航条热门分类<small>不建议设置过多 点击按钮调整</small></h3>
            <ul class="am-list am-list-static am-list-border am-text-left" id="navbarList">
                @foreach($navbars as $key => $value)
                    <li>
                        <span class="am-badge am-badge-primary">{{ $value }}</span>
                        <span onclick="moveUp($(this))" style="cursor: pointer" class="am-icon-btn am-icon am-icon-arrow-up"></span>
                        <span onclick="moveDown($(this))" style="cursor: pointer" class="am-icon am-icon-btn am-icon-arrow-down"></span>
                        <span onclick="remove($(this))" style="cursor: pointer" class="am-icon am-icon-btn am-danger am-icon-close"></span>
                        <span>{{ $key }}</span>
                    </li>
                @endforeach
            </ul>
            <button class="am-btn am-btn-primary" type="button" onclick="window.location.reload()">复原</button>
            <button class="am-btn am-btn-primary" type="button" id="add">新增</button>
            <button class="am-btn-success am-btn" type="button" id="save">保存</button>
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
    <div class="am-modal am-modal-prompt" tabindex="-1" id="prompt">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">输入</div>
            <div class="am-modal-bd">
                请输入新的类别名
                <input type="text" class="am-modal-prompt-input">
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>提交</span>
            </div>
        </div>
    </div>
    <div class="am-modal am-modal-prompt" tabindex="-1" id="navbar">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">选择类别名</div>
            <div class="am-modal-bd">
                <select data-am-selected id="typeSelect" class="am-modal-prompt-input">
                    <option value="-1">请选择</option>
                </select>
            </div>
            <hr/>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>提交</span>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".am-icon-trash").click(function () {
                if(parseInt($(this).parent().prev().html()) != 0) {
                    $("#info").html("该类型文章并不为空，不能删除。如果已经调整过，请刷新页面");
                    $("#alert").modal();
                } else {
                    var name = $(this).parent().prev().prev().html();
                    $.post("{{ URL('admin/type/del') }}", {name:name, _token:"{{ csrf_token() }}"}, function(data){
                        if(data.status == true) {
                            $("#info").html("操作完成，即将自动刷新!");
                            $("#alert").modal();
                            window.setTimeout('window.location.reload()', 2000);
                        } else {
                            $("#info").html("操作失败，即将自动刷新!");
                            $("#alert").modal();
                            window.setTimeout('window.location.reload()', 2000);
                        }
                    })
                }
            })

            $(".am-icon-edit").click(function(){
                var lastName = $(this).parent().prev().prev().html();
                $(".am-modal-prompt-input").val('');
                $(".am-modal-prompt-input").attr('placeholder', lastName);
                $("#prompt").modal({
                    onConfirm:function(e){
                        $.post("{{ URL('admin/type/change') }}",
                            {lastName:lastName, newName:e.data, _token:"{{ csrf_token() }}"}, function(data){
                            if(data.status == true) {
                                window.location.reload();
                            } else {
                                alert("更新失败，请重试！");
                            }
                            })
                    }
                })
            })

            $("#add").click(function(){
                //左右去重
                var leftArr = new Array();
                var rightArr = new Array();
                $("#typeSelect option:gt(0)").remove();
                $(".am-icon-trash").each(function(){
                   leftArr.push($(this).parent().prev().prev().html());
                })
                $(".am-icon-close").each(function(){
                    rightArr.push($(this).next().html());
                })

                for(var i = 0; i < leftArr.length; i++) {
                    var findSig = false;
                    for (var j = 0; j < rightArr.length; j++) {
                        if (leftArr[i] == rightArr[j]) {
                            findSig = true;
                            break;
                        }
                    }
                    if(!findSig) {
                        $("#typeSelect").append('<option value="'+leftArr[i]+'">'+leftArr[i]+'</option>')
                    }
                }
                $("#navbar").modal({
                    onConfirm:function(e){
                        if(e.data != -1)
                            $("#navbarList").append('<li>' +
                                '                        <span class="am-badge am-badge-primary">'+($("#navbarList").children().length + 1)+'</span>\n' +
                                '                        <span onclick="moveUp($(this))" style="cursor: pointer" class="am-icon-btn am-icon am-icon-arrow-up"></span>\n' +
                                '                        <span onclick="moveDown($(this))" style="cursor: pointer" class="am-icon am-icon-btn am-icon-arrow-down"></span>\n' +
                                '                        <span onclick="remove($(this))" style="cursor: pointer" class="am-icon am-icon-btn am-danger am-icon-close"></span>\n' +
                                '                        <span>'+e.data+'</span>' +
                                '                    </li>')
                    }
                });
            })
            $("#insert").click(function () {
                var name = $("#newName").val();
                if(name.length != 0) {
                    $.post("{{ URL('admin/type/add') }}", {name:name, _token:"{{ csrf_token() }}"}, function(data){
                        $("#info").html("操作完成，即将自动刷新!");
                        $("#alert").modal();
                        window.setTimeout('window.location.reload()', 2000);
                    })
                }
            })

            $("#save").click(function(){
                var lists = new Array();
                $("#navbarList").children().each(function () {
                    var id = $(this).children().eq(0).html();
                    var name = $(this).children().eq(4).html();
                    lists.push(new Array(name, id));
                })
                $.post("{{ URL('admin/type/navbar_edit') }}", {data:lists, _token:"{{ csrf_token() }}"}, function(data){
                    $("#info").html("操作完成，即将自动刷新!");
                    $("#alert").modal();
                    window.setTimeout('window.location.reload()', 2000);
                })

            })

        })

        function moveUp(target) {
            var id = $(target).prev().html();
            var name = $(target).parent().children().eq(4).html();
            if(id != 1) {
                var namePrev = $(target).parent().prev().children().eq(4).html();
                $(target).parent().prev().children().eq(4).html(name);
                $(target).parent().children().eq(4).html(namePrev);
            }
        }

        function moveDown(target) {
            var id = $(target).prev().html();
            var name = $(target).parent().children().eq(4).html();
            if(id < $(target).parent().parent().children().length) {
                var namePrev = $(target).parent().next().children().eq(4).html();
                $(target).parent().next().children().eq(4).html(name);
                $(target).parent().children().eq(4).html(namePrev);
            }
        }

        function remove(target) {

            $(target).parent().remove();
            var id = 1;
            //重写编号
            $("#navbarList").children().each(function(){
                $(this).children().eq(0).html(id++);
            })
        }
    </script>
@endsection
