@extends('layout')
@section('title')文章列表@endsection
@section('main')
    <div data-am-widget="list_news" class="am-list-news am-list-news-default" >
        <div class="am-list-news-hd am-cf">
            <a href="#" class="">
                <h1>文章列表</h1>
            </a>
        </div>
        <div class="am-list-news-bd">
            <ul class="am-list">
                @foreach($arr as $key => $blog)
                    <li class="am-g am-list-item-dated">
                        <a href="{{ URL('blog/'.$key) }}" class="am-list-item-hd ">{{ $blog['title'] }}</a>
                        <span class="am-list-date">{{ $blog['type'] }} | {{ date('Y-m-d H:i', $blog['time']) }} | <span class="am-icon am-icon-eye"></span> {{ $blog['view'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
    <ul data-am-widget="pagination" class="am-pagination am-pagination-select">
        <li class="am-pagination-prev ">
            <a href="#" class="">上一页</a>
        </li>
        <li class="am-pagination-select">
            <select>
                <option value="#" class="">第1页</option>
                <option value="#" class="">第2页</option>
            </select>
        </li>
        <li class="am-pagination-next ">
            <a href="#" class="">下一页</a>
        </li>
    </ul>
@endsection