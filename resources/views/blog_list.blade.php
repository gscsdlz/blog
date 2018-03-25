@extends('layout')
@section('title')文章列表@endsection
@section('main')
    <div data-am-widget="list_news" class="am-list-news am-list-news-default" >
        <div class="am-list-news-hd am-cf">
            <a href="#" class="">
                <h1>文章列表 <small>共计{{ count($arr) }}篇</small></h1>
            </a>
        </div>
        <div class="am-list-news-bd">
            <ul class="am-list">
                @if(count($arr) > 0)
                @foreach($arr as $key => $blog)
                    <li class="am-g am-list-item-dated">
                        <a href="{{ URL('blog/'.$key) }}" class="am-list-item-hd ">{{ $blog['title'] }}</a>
                        <span class="am-list-date">{{ $blog['type'] }} | {{ date('Y-m-d H:i', $blog['time']) }} | <span class="am-icon am-icon-eye"></span> {{ $blog['view'] }}</span>
                    </li>
                @endforeach
                @endif
            </ul>
        </div>

    </div>
    <!--<ul data-am-widget="pagination" class="am-pagination am-pagination-select">
        <li class="am-pagination-prev ">
            @if(isset($type))
                <a href="{{ URL('type/'.$type.'/'.( $page- 1) )}}" class="">上一页</a>
            @else
                <a href="{{ URL('all/'.( $page- 1) )}}" class="">上一页</a>
            @endif
        </li>

        <li class="am-pagination-next ">
            @if(isset($type))
                <a href="{{ URL('type/'.$type.'/'.( $page+ 1) )}}" class="">下一页</a>
            @else
                <a href="{{ URL('all/'.( $page+ 1) )}}" class="">下一页</a>
            @endif        </li>
    </ul>-->
@endsection