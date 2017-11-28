@extends('admin.layout')
@section('title')
    评论管理
@endsection
@section('main')
    <div class="am-g">
        <div class="am-u-md-2 am-u-md-offset-2">
            <ul class="am-list am-list-static am-list-border am-list-striped" id="indexLists">
                @foreach($keys as $k)
                    <li onclick="refresh_list({{ $k[0] }})"><span class="am-badge am-btn-primary" >{{ $k[0] }}</span>{{ $k[1] }}</li>
                @endforeach
            </ul>
        </div>
        <div class="am-u-md-6 am-u-end">
            <table class="am-table" id="commentsList">
                <tr>
                    <th>内部编号</th>
                    <th>内容</th>
                    <th>邮箱</th>
                    <th>留言时间</th>
                    <th>操作</th>
                </tr>
            </table>
        </div>
    </div>
    <div class="am-modal am-modal-prompt" tabindex="-1" id="prompt">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">输入回复内容</div>
            <div class="am-modal-bd">
                <textarea rows="4" class="am-modal-prompt-input"></textarea>
                <label><input type="checkbox" value="1" id="sendEmail" checked> 同时发送邮件提醒</label>
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>提交</span>
            </div>
        </div>
    </div>
    <script>
        var blogID;
        var token = "{{ csrf_token() }}";
        $(document).ready(function () {
            refresh_list($("#indexLists").children().eq(0).children().eq(0).html());
        })

        function refresh_list(bid) {
            blogID = bid;
            $.post("{{ URL("admin/comments/get") }}",{bid:bid, _token:token}, function(data){
                if(data.status == true) {
                    $("#commentsList tr:gt(0)").remove();
                    for(var i = 0; i < data.lists.length; i++)
                        $("#commentsList").append('<tr><td>'+(i+1)+'</td><td>'+data.lists[i].text+'</td><td>'+data.lists[i].email+'</td><td>'+data.lists[i].time+'</td>' +
                            '<td><span class="am-icon am-icon-trash" onclick="del((this))"></span> | <span class="am-icon am-icon-reply" onclick="reply($(this))"></span></td>' +
                            '</tr>');
                }
            })
        }

        function del(target) {
            var id = $(target).parent().parent().children().eq(0).html();
            $.post("{{ URL('admin/comments/del') }}", {bid:blogID, id:(id-1), _token:token}, function(data){
                if(data.status == true) {
                    window.location.reload();
                } else {
                    alert("删除失败！");
                }
            })
        }

        function reply(target) {
            var id = $(target).parent().parent().children().eq(0).html();
            var email = $("#sendEmail").prop('checked') == true ? 1 : 0;

            $("#prompt").modal({
                onConfirm:function(e){
                    $.post("{{ URL('admin/comments/insert') }}", {bid:blogID, id:(id-1), text:e.data, email:email, _token:token}, function(data){
                        if(data.status == false)
                            alert("邮件发送失败！");
                       window.location.reload();
                    })
                }
            })
        }
    </script>
@endsection