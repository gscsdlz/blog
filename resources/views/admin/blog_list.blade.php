@extends('admin.layout')
@section('title')所有博客@endsection
@section('main')
    <div class="am-g">
        <div class="am-u-md-6 am-u-md-centered">
            <table class="am-table">
                <tr>
                    <th>编号</th>
                    <th>博客标题</th>
                    <th>添加时间</th>
                    <th>所属分类</th>
                    <th>操作</th>
                </tr>
                @foreach($lists as $id => $blog )
                    <tr>
                        <td><a href="{{ URL('blog/'.$id) }}" target="_blank">{{ $id }}</a></td>
                        <td>{{ $blog['title'] }}</td>
                        <td>{{ date('Y-m-d H:i:s', $blog['time']) }}</td>
                        <td>{{ $blog['type'] }}</td>
                        <td>
                            <span onclick="window.location.href='{{ URL('admin/blog/edit') }}/{{ $id }}'" class="am-icon am-icon-edit am-icon-sm" data-am-popover="{content: '编辑文章', trigger: 'hover focus'}"></span> |
                            <span class="am-icon am-icon-trash am-icon-sm" data-am-popover="{content: '删除文章', trigger: 'hover focus'}"></span> |
                            @if(isset($blog['visible']) && $blog['visible'] == 1)
                                <span class="am-icon am-icon-eye-slash am-icon-sm" data-am-popover="{content: '隐藏文章', trigger: 'hover focus'}"></span>
                            @else
                                <span class="am-icon am-icon-eye am-icon-sm" data-am-popover="{content: '取消隐藏文章', trigger: 'hover focus'}"></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                
            </table>
        </div>
    </div>
    <div class="am-modal am-modal-confirm" tabindex="-1" id="confirm">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">提醒</div>
            <div class="am-modal-bd" id="info">
                你，确定要删除这条记录吗？
            </div>
            <div class="am-modal-footer">
                <span class="am-modal-btn" data-am-modal-cancel>取消</span>
                <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
        </div>
    </div>
    <script>
        var _token = "{{ csrf_token() }}"
        $(document).ready(function () {
            $(".am-icon-trash").click(function () {
                var id = $(this).parent().parent().children().eq(0).html();
                $("#info").html("删除不可逆，建议使用隐藏功能！确认删除吗？");
                $("#confirm").modal({
                    onConfirm:function(){
                        $.post("{{ URL('admin/blog/del') }}", {bid:id, _token:_token}, function(data){
                            if(data.status == true) {
                                window.location.reload();
                            } else {
                                alert("删除失败，请重试");
                            }
                        })
                    }
                })
            })

            $(".am-icon-eye").click(function(){
                var id = $(this).parent().parent().children().eq(0);
            })
        })
    </script>
@endsection
