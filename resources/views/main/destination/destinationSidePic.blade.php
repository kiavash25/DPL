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
    <h4 class="sidePicCountry">
        {{$content->category->name}}
    </h4>
{{--    tag--}}
{{--    <div class="sidePicTagContent">--}}
{{--        @foreach($content->tags as $item)--}}
{{--            <a href="{{route('show.list', ['kind' => 'tags', 'value1' => $item])}}" class="tagContent">--}}
{{--                {{$item}}--}}
{{--            </a>--}}
{{--        @endforeach--}}
{{--    </div>--}}

    @if(isset($content->packages) && count($content->packages) > 0)
        <div id="sidePackageSwiper" class="swiper-container packageSwiperSide mobileHide" >
            <div class="swiper-wrapper" style="padding: 10px 0px">
                @foreach($content->packages as $item)
                    <div class="swiper-slide contentHtmlCenter">
                        <div class=" packages">
                            <div class="packageImgDiv">
                                <img src="{{$item->pic}}" class="packageImg">
                            </div>
                            <div class="packageContentDiv">
                                <div class="packageName">
                                    {{$item->name}}
                                </div>
                                <div class="packageDescription">
                                    {{$item->description}}
                                </div>
                                <div class="packageButtonDiv">
                                    <a href="{{$item->url}}" class="packageButton">
                                        {{__('See Package')}}
                                    </a>
                                </div>

                                <div class="packageActivity">
                                    {{$item->mainActivity->name}}
                                </div>
                            </div>

                            <div class="packageDate">
                                <div style="color: white">{{$item->sD}}</div>
                                <div style="color: white">{{$item->sM}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="packageSideButtonDiv mobileHide">
            <div id="nextPackageSide" class="sliderButton nextSlider" style="top: 33%; right: 10px; box-shadow: 0 0 10px 3px black">
                <div class="slider arrow right"></div>
            </div>
            <div id="prevPackageSide" class="sliderButton prevSlider" style="top: 33%; left: 10px; box-shadow: 0 0 10px 3px black">
                <div class="slider arrow left"></div>
            </div>
        </div>
    @endif


    @if(isset($content->podcast) && $content->podcast)
        <div class="aboutHeader podcastDiv">
            {{__('Podcast')}}
            <audio id="audioTag" preload="none" controls style="width: 100%; ">
                <source id="audioSource" src="{{$content->podcast}}">
            </audio>
        </div>
    @endif

    <script !src="">
        sidePackageSwiper = new Swiper('#sidePackageSwiper', {
            loop: true,
            slidesPerView: 2,
            navigation: {
                nextEl: '#nextPackageSide',
                prevEl: '#prevPackageSide',
            },
            breakpoints:{
                1500:{
                    slidesPerView: 1,
                },
            }
        });
    </script>
</div>
