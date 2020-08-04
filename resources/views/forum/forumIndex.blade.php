@extends('forum.forumLayout')

@section('forumHeader')

@endsection

@section('forumBody')
    <div class="mainForumSection">
        <div class="mainForumHeaderList">
            {{__('Categories')}}
        </div>
        <div class="mainForumBodyList">
            @foreach($categories as $item)
                <div class="mainForumListRow">
                    <div class="mainInfoForumList">
                        <div class="headerRow">
                            <a href="{{route('forum.category.list', ['categoryId' => $item->id])}}" class="forumNameList">
                                {{$item->name}}
                            </a>
                            <div class="showOn550">
                                <div class="forumInfosList">
                                    <span class="number">{{$item->topics}}</span>
                                    <span class="name">{{__('Topic')}}</span>
                                </div>
                                <div class="forumInfosList">
                                    <span class="number">{{$item->replies}}</span>
                                    <span class="name">
                                    {{__('Repliet')}}
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="forumSummeryList">
                            {{$item->summery}}
                        </div>
                        <div class="hideOnPc">

                            <div class="forumInfosList">
                                <span class="number">{{$item->like}}</span>
                                <span class="name">
                                    <i class="far fa-thumbs-up" aria-hidden="true" style="color: green;"></i>
                                </span>
                            </div>
                            <div class="forumInfosList">
                                <span class="number">{{$item->dislike}}</span>
                                    <span class="name">
                                    <i class="far fa-thumbs-down" aria-hidden="true" style="color: red;"></i>
                                </span>
                            </div>
{{--                            <div class="forumInfosList">--}}
{{--                                <span class="name">--}}
{{--                                    2020-03-23--}}
{{--                                </span>--}}
{{--                            </div>--}}

                        </div>
                    </div>

                    <div class="sideInfoForumList sideHide">
                        <div>
                            <div class="forumInfosList">
                                <span class="number">{{$item->topics}}</span>
                                <span class="name">{{__('Topic')}}</span>
                            </div>
                            <div class="forumInfosList">
                                <span class="number">{{$item->replies}}</span>
                                <span class="name">
                                {{__('Repliet')}}
                            </span>
                            </div>
                            <div class="forumInfosList">
                                <span class="number">{{$item->person}}</span>
                                <span class="name">{{__('Person')}}</span>
                            </div>
                        </div>

                        <div class="hideOnPhone">
                            <div class="forumInfosList">
                                <span class="number">{{$item->like}}</span>
                                <span class="name">
                                    <i class="far fa-thumbs-up" aria-hidden="true" style="color: green;"></i>
                                </span>
                            </div>
                            <div class="forumInfosList">
                                <span class="number">{{$item->dislike}}</span>
                                <span class="name">
                                    <i class="far fa-thumbs-down" aria-hidden="true" style="color: red;"></i>
                                </span>
                            </div>
{{--                            <div class="forumInfosList">--}}
{{--                                <span class="name">--}}
{{--                                    2020-03-23--}}
{{--                                </span>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
