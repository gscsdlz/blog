@extends('layout')
@section('title')
    所有分类
@endsection
@section('main')
    <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
        <h2 class="am-titlebar-title ">
            所有类型
        </h2>
    </div>

    <ul class="am-list am-list-static">
        @foreach($arr as $t)
            <li><span class="am-badge am-badge-success">{{ $t[1] - 1 }}</span><a href="{{ URL('type/'.$t[0].'/1') }}">{{ $t[0] }}</a></li>
        @endforeach
    </ul>
@endsection