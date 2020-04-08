@extends('journal.layout.layoutJournal')

@section('head')

    <link rel="stylesheet" href="{{asset('css/ckeditor.css')}}">
    <link rel="stylesheet" href="{{asset('css/journal/contentJournal.css')}}">

@endsection


@section('body')
    <article style="margin-top: 30px">
        <div class="row">
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

