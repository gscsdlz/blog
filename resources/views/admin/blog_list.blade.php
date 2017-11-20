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
                        <td><a href="#" target="_blank">{{ $id }}</a></td>
                        <td>{{ $blog['title'] }}</td>
                        <td>{{ date('Y-m-d H:i:s', $blog['time']) }}</td>
                        <td>{{ $blog['type'] }}</td>
                        <td>
                            <div class="am-g">
                                <span class="am-icon am-icon-edit am-icon-sm" data-am-popover="{content: '编辑文章', trigger: 'hover focus'}"></span> |
                                <span class="am-icon am-icon-trash am-icon-sm" data-am-popover="{content: '删除文章', trigger: 'hover focus'}"></span> |
                                @if(isset($blog['visible']) && $blog['visible'] == 1)
                                    <span class="am-icon am-icon-eye-slash am-icon-sm" data-am-popover="{content: '隐藏文章', trigger: 'hover focus'}"></span>
                                @else
                                    <span class="am-icon am-icon-eye am-icon-sm" data-am-popover="{content: '取消隐藏文章', trigger: 'hover focus'}"></span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
                
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".am-icon-trash").click(function () {
                //var id =
            })
        })
    </script>
@endsection
