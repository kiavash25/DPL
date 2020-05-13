@extends('journal.layout.layoutJournal')

@section('head')

    <link rel="stylesheet" href="{{asset('css/ckeditor.css')}}">
    <link rel="stylesheet" href="{{asset('css/journal/contentJournal.css')}}">
    <title>{{isset($journal->setTitle) ? $journal->setTitle : 'DPL'}}</title>
    <meta content="article" property="og:type"/>

    <meta name="keywords" content="{{$journal->keyword}}">
    <meta property="og:title" content=" {{$journal->seoTitle}} " />
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


@endsection


@section('body')
    <article style="margin-top: 30px">
        <div class="row" style="direction: ltr;">
            <div id="mainContentDiv" class="col-lg-9" style="visibility: hidden; margin-bottom: 100px">
                <div class="row" style="padding: 0px; margin: 0px">
                    <div class="mainPic">
                        <img src="{{$journal->pic}}" class="resizeImage" style="width: 100%;">
                    </div>
                </div>
                <div class="row" style="padding: 0px; margin: 0px;">
                    <div class="col-md-2 mainSideContentDiv">
                        <a href="{{route('journal.list', ['kind' => 'category', 'value' => $journal->category])}}" class="mainSideContent">{{$journal->category}}</a>
                        <div class="mainSideContent">{{$journal->date}}</div>
                        <div class="mainSideContent">{{$journal->username}}</div>
                    </div>
                    <div class="col-md-10 mainContent">
                        <h1 class="mainContentName">
                            {{$journal->name}}
                        </h1>
                        <div class="mainContentText">
                            {!! $journal->text   !!}
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

