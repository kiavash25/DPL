@extends('journal.layout.layoutJournal')

@section('head')
    <title>{{isset($journal->setTitle) ? $journal->setTitle : 'DPL'}}</title>
    <meta content="article" property="og:type"/>

    <meta name="keywords" content="{{$journal->keyword}}">
    <meta property="og:title" content="{{$journal->seoTitle}}" />
    <meta property="og:description" content=" {{$journal->meta}}" />
    <meta name="twitter:title" content=" {{$journal->seoTitle}} " />
    <meta name="twitter:description" content=" {{$journal->meta}}" />
    <meta name="description" content=" {{$journal->meta}}"/>
    <meta property="article:section" content="article"/>
    @if(isset($journal->pic))
        <meta property="og:image" content="{{$journal->pic}}"/>
        <meta property="og:image:secure_url" content="{{$journal->pic}}"/>
        <meta property="og:image:width" content="550"/>
        <meta property="og:image:height" content="367"/>
        <meta name="twitter:image" content="{{$journal->pic}}"/>
    @endif

    @if(isset($journal->tag))
        @foreach($journal->tag as $item)
            <meta property="article:tag" content="{{$item}}"/>
        @endforeach
    @endif

    <link rel="stylesheet" href="{{asset('css/ckeditor.css')}}">
    <link rel="stylesheet" href="{{asset('css/journal/contentJournal.css')}}">

    <style>
        .mainContentDiv{
            background: white;
            width: calc(100% - 30px);
            margin: 20px auto;
            max-width: 1000px;
            padding: 1px 0px;
            margin-bottom: 100px;
        }
        .mainContentName{
            text-align: center;
        }
        .mainSideContent{
            text-align: center;
            font-size: 14px;
            margin: 0px;
            margin-bottom: 15px;
            color: #9b9b9b;
        }
        .mainPic{
            position: relative;
        }
        .userInfoSection{
            margin-top: -50px;
            z-index: 9;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .circle-100{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
        }
        .userPicBorder{
            border: solid 5px white;
            box-shadow: 0 0 13px 3px #737373;
        }
        .userInfoSection .userName{
            margin-top: 10px;
            font-size: 20px;
            color: #9b9b9b;
        }
        .mainContentText{
            padding: 40px;
        }
        .mainContentText > p{
            font-size: 16px;
        }
        .mainTags{
            display: flex;
            font-size: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .mainTags .tag{
            color: #1f75b9;
            margin: 5px 10px;
            cursor: pointer;
        }

        #sideContentDiv{
            padding: 0px
        }

        #sideContentDiv > div{
            margin-top: 20px;
            padding-top: 20px;
            background: white;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
        }
        #sideContentDiv > div .row{
            margin: 0px;
        }
        @media (max-width: 991px) {
            .mainBase .row{
                margin: 0px;
            }
            .col-lg-9{
                padding: 0px;
            }
        }
        @media (max-width: 761px) {

            .mainContentText{
                padding: 10px;
            }
            .mainPic{
                height: 250px;
            }
        }
    </style>
@endsection


@section('body')
    <article style=" background: #f0f0f0;">
        <div class="row" style="width: 100%; padding: 0;">
            <div class="col-lg-9">
                <div id="mainContentDiv" class="mainContentDiv">
                <div class="mainContentName">
                    <h1 style="text-align: center"> {{$journal->name}} </h1>
                    <a href="{{route('journal.list', ['kind' => 'category', 'value' => $journal->category])}}" style="color: #2c3e50">
                        {{$journal->category}}
                    </a>
                </div>
                <div class="mainSideContent">{{$journal->date}}</div>
                <div class="mainPic">
                    <img src="{{$journal->pic}}" alt="{{$journal->name}}" class="resizeImage" style="width: 100%;" onload="fitThisImg(this)">
                </div>
                <div class="userInfoSection">
                    <div class="circle-100 userPicBorder">
                        <img src="{{$journal->userPic}}" alt="userPic" class="resizeImage" style="width: 100%">
                    </div>
                    <div class="userName">
                        {{__('Written by')}}
                        <span style="color: #1f75b9">{{$journal->username}}</span>
                    </div>
                </div>
                <div class="mainContentText">
                    {!! $journal->text   !!}

                    <div class="mainTags">
                        @if(isset($journal->tag))
                            @foreach($journal->tag as $tag)
                                <a href="{{route('journal.list', ['kind' => 'search', 'value' => $tag])}}" class="tag">
                                    #{{$tag}}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>
            </div>

            @include('journal.layout.sideContentJournal')
        </div>
    </article>


@endsection



@section('script')

@endsection

