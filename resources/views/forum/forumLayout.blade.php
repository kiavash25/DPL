@extends('layouts.base')

@section('head')
    <link rel="stylesheet" href="{{asset('css/pages/forum.css')}}">

    <?php
    $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>

    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlForum.css')}}">
    @endif

    @yield('forumHeader')
@endsection


@section('body')
    <div class="forumHeaderPic">
        {{$header}}
    </div>
    <div class="container" style="background-color: #f9f9f9;">
        <div class="row topForumRow">
            <div class="pathForum">
                <a href="{{route('forum.index')}}">{{__('Topical discussion')}}</a>
                @foreach($path as $item)
                    <span> / </span>
                    <a href="{{$item['url']}}">{{$item['title']}}</a>
                @endforeach
            </div>
            <a href="{{route('forum.newTopic')}}" class="newTopicButton">
                <i class="fas fa-plus"></i>
                {{__('New Topic')}}
            </a>
        </div>
        <div class="row" style="padding: 0px 10px">
            <div class="col-lg-9 mainSection">
                @yield('forumBody')
            </div>

            <div class="col-lg-3 sideSection">
                <div class="sideForumSections">
                    <div class="sideForumHeader">
                        {{__('Categories')}}
                    </div>
                    <div class="sideForumBody">
                        @foreach($sideCategory as $item)
                            <a href="{{route('forum.category.list', ['categoryId' => $item->id])}}" class="sideForumRow">
                                <div class="forumCategoryName">{{$item->name}}</div>
                                <div class="forumCategoryNum">{{$item->topics}}</div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="sideForumSections">
                    <div class="sideForumHeader">
                        {{__('Recent topics')}}
                    </div>
                    <div class="sideForumBody">
                        @foreach($recentlyTopic as $item)
                            <a href="{{route('forum.topic.show', ['topicId' => $item->id])}}" class="sideForumRow newTopics" style="flex-direction: column;">
                                <div class="forumSideTopicName">{{$item->title}}</div>
                                <div class="forumSideTopicBy">{{__('by')}} {{$item->username}} {{__('In')}} {{$item->category}}</div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('script')
    @yield('forumScript')
@endsection
