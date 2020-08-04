@extends('forum.forumLayout')

@section('forumHeader')
    <style>
        .mainInfoForumList{
            flex-direction: row;
            justify-content: flex-start;
        }
    </style>
@endsection

@section('forumBody')
    <div class="mainForumSection">
        <div class="mainForumHeaderList">
            {{$header}}
        </div>

        <div class="mainForumBodyList">
            @foreach($topics as $item)
                <div class="mainForumListRow">
                    <div class="mainInfoForumList">
                        <div class="forumUserImgSection hideOnPhone">
                            <div class="forumUserImgDiv">
                                <img src="{{$item->userPic}}" class="resizeImage" style="width: 100%">
                            </div>
                        </div>
                        <div>
                            <div class="headerRow">
                                <a href="{{route('forum.topic.show', ['topicId' => $item->id])}}" class="forumNameList">
                                    {{$item->title}}
                                </a>
                                <div class="showOn550">
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
                            </div>
                            <div class="forumSummeryList">
                                {{$item->summery}}
                            </div>
                            <div class="forumSummeryList infos">
                                <div>
                                    {{__('by')}}
                                    <span class="userWriter">{{$item->username}}</span>
                                </div>
                                <div>
                                    @foreach($item->tag as $tag)
                                        <label class="forumTag">#{{$tag}}</label>
                                    @endforeach
                                </div>
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
                                <div class="forumInfosList">
                                    <span class="name">
                                        {{$item->time}}
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="sideInfoForumList sideHide">
                        <div>
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
{{--                            <div class="forumInfosList">--}}
{{--                                <span class="number">{{$item->seen}}</span>--}}
{{--                                <span class="name">{{__('Views')}}</span>--}}
{{--                            </div>--}}
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
                            <div class="forumInfosList">
                                <span class="name">
                                    {{$item->time}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

{{--        <div class="mainForumPagination">--}}
{{--            <ul class="pagination">--}}
{{--                <li class="page-item disabled"><a class="page-link" href="#">{{__('Previous')}}</a></li>--}}
{{--                <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                <li class="page-item"><a class="page-link" href="#">{{__('Next')}}</a></li>--}}
{{--            </ul>--}}
{{--        </div>--}}

    </div>
@endsection
