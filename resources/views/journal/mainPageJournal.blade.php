@extends('journal.layout.layoutJournal')

@section('head')
    <link rel="stylesheet" href="{{asset('css/journal/mainPageJournal.css')}}">
    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">
    <script src="{{asset('js/swiper/swiper.js')}}"></script>

@endsection


@section('body')
    <div style="background: #f0f0f0;">
        <div class="row">
            <div class="col-lg-9" style="padding: 0px">
                <div class="mainContent">

                    <div class="row topContentRecently" >
                        <div class="col-md-12">
                            <div class="sections">
                                <div class="header">
                                    {{__('RECENT JOURNALS')}}
                                </div>
                                <div class="body" style="padding-top: 20px;">
                                    @if(isset($mainSliderJournal[0]))
                                        <div class="mainRecently">
                                            <img src="{{$mainSliderJournal[0]->pic}}" class="resizeImage" onload="fitThisImg(this)">
                                            <a href="{{$mainSliderJournal[0]->url}}" class="hoverPic">
                                                <div class="name">{{$mainSliderJournal[0]->name}}</div>
                                                <div class="writer">{{__('Written by')}} {{$mainSliderJournal[0]->username}}</div>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="row sideRecentlyDiv">
                                        @foreach($mainSliderJournal as $index => $item)
                                            @if($index > 0)
                                                <div class="col-md-6">
                                                    <div class="recentlySide">
                                                        <a href="{{$item->ur}}" class="imgSection">
                                                            <img src="{{$item->pic}}" class="resizeImage" onload="fitThisImg(this)">
                                                        </a>
                                                        <div class="infos">
                                                            <a href="{{$item->ur}}" class="name">
                                                                {{$item->name}}
                                                            </a>
                                                            <div class="writer">
                                                        <span>
                                                            {{__('by')}} {{$item->username}}
                                                        </span>
                                                                <span>
                                                            {{$item->date}}
                                                        </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="categoriesList">
                        <div class="row">
                            @foreach($showCategories as $index => $categ)
                                @if(($index % 4 == 0 || $index % 4 == 1) && $index != count($showCategories)-1)
                                    <?php
                                        $col = 'col-md-6';
                                        $type = 'type2Side full';
                                        $hasBig = false;
                                    ?>
                                @elseif($index % 4 == 3)
                                    <?php
                                        $col = 'col-md-12';
                                        $type = '';
                                        $hasBig = false;
                                    ?>
                                @else
                                    <?php
                                        $col = 'col-md-12';
                                        $type = 'type2Side';
                                        $hasBig = true;
                                    ?>
                                @endif
                                    <div class="margb30 {{$col}}">
                                        <div class="sections">
                                            <a href="{{route('journal.list', ['kind' => 'category', 'value' => $categ->name])}}" class="header">
                                                {{$categ->name}}
                                            </a>
                                            <div class="body">
                                                @if($hasBig)
                                                    <div class="recentlySide main">
                                                        <a href="{{$categ->journals[0]->ur}}" class="imgSection">
                                                            <img src="{{$categ->journals[0]->pic}}" class="resizeImage" onload="fitThisImg(this)">
                                                        </a>
                                                        <div class="infos">
                                                            <a href="{{$categ->journals[0]->url}}" class="name">
                                                                {{$categ->journals[0]->name}}
                                                            </a>
                                                            <div class="writer">
                                                                <span>
                                                                    {{__('by')}} {{$categ->journals[0]->username}}
                                                                </span>
                                                                <span>
                                                                    {{$categ->journals[0]->date}}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row {{$type}} sideRecentlyDiv">
                                                    @foreach($categ->journals as $index => $item)
                                                        @if(($index > 0 || ($index == 0 && !$hasBig)) && (($index < 4 && !$hasBig) || ($hasBig && $index < 5)))
                                                            <div class="col-md-6 recentlySide">
                                                                <a href="{{$item->url}}" class="imgSection">
                                                                    <img src="{{$item->pic}}" class="resizeImage" onload="fitThisImg(this)">
                                                                </a>
                                                                <div class="infos">
                                                                    <a href="{{$item->url}}" class="name">
                                                                        {{$item->name}}
                                                                    </a>
                                                                    <div class="writer">
                                                                        <span>
                                                                            {{__('by')}} {{$item->username}}
                                                                        </span>
                                                                        <span>
                                                                            {{$item->date}}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

            @include('journal.layout.sideContentJournal')
        </div>
    </div>
@endsection



@section('script')

    <script>

    </script>
@endsection

