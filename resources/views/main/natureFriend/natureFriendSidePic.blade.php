<link rel="stylesheet" href="{{asset('css/common/sliderPacks.css')}}">


<style>
    .sidePicContent{
        color: #2c3e50;
    }
    .sidePicHeader{

    }
    .sidePicCountry{

    }
    .sidePicTagContent{
        margin-bottom: 10px;
    }
    .tagContent{
        font-size: 14px;
        display: inline-block;
        line-height: 21px;
        background-color: #ebeef2;
        border-radius: 7px;
        padding: 4px 8px;
        margin-right: 8px;
        margin-bottom: 6px;
        color: #415466;
        transition: .2s;
        cursor: pointer;
    }
    .tagContent:hover{
        color: #286283;
        text-decoration: none;
        background-color: #b3d6e9;
    }
    .podcastDiv{
        margin-top: 10px;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
    .packages{
        width: 195px !important;
    }
    .packageSideButtonDiv{

    }
    #nextPackageSide{
        top: 40% !important;
        right: -5px !important;
    }
    #prevPackageSide{
        top: 40% !important;
        left: -5px !important;
    }
</style>

<div class="sidePicContent">
    <h1 class="sidePicHeader">
        {{$content->name}}
    </h1>
{{--    tag--}}
{{--    <div class="sidePicTagContent">--}}
{{--        @foreach($content->tags as $item)--}}
{{--            <a href="{{route('show.list', ['kind' => 'tags', 'value1' => $item])}}" class="tagContent">--}}
{{--                {{$item}}--}}
{{--            </a>--}}
{{--        @endforeach--}}
{{--    </div>--}}

    @if(isset($content->podcast) && $content->podcast)
        <div class="aboutHeader podcastDiv">
            {{__('Podcast')}}
            <audio id="audioTag" preload="none" controls style="width: 100%; ">
                <source id="audioSource" src="{{$content->podcast}}">
            </audio>
        </div>
    @endif
</div>
