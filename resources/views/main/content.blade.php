@extends('layouts.base')

@section('head')
    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">
    <link rel="stylesheet" href="{{asset('css/pages/content.css')}}">

    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>

    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlContent.css')}}">
    @endif

    <script src="{{asset('js/swiper/swiper.js')}}"></script>

@endsection

@section('body')
    <div class="container">
        <div class="row" style="margin-bottom: 30px">
            <?php
                if($kind == 'destination' || $kind == 'category' || $kind == 'activity'){
                    $picNum = 8;
                    $sideNum = 4;
                }
                else{
                    $picNum = 7;
                    $sideNum = 5;
                }
            ?>

            <div class="col-lg-{{$picNum}}">
                <div class="mainSliderPic">
                    <div class="swiper-container mainSS" style="height: 100%;">
                        <div class="swiper-wrapper">
                            @foreach($content->slidePic as $item)
                                <div class="swiper-slide mainSliderImgTop" onclick="openMainPicAlbum({{$item->id}})">
                                    <img src="{{$item->slide}}" class="sliderImg">
                                </div>
                            @endforeach
                        </div>

                        <div id="nextSlider" class="sliderButton nextSlider">
                            <div class="slider arrow right"></div>
                        </div>
                        <div id="prevSlider" class="sliderButton prevSlider">
                            <div class="slider arrow left"></div>
                        </div>
                    </div>
                </div>

{{--                <div class="likeButton">--}}
{{--                    <i class="fas fa-heart likeBack"></i>--}}
{{--                    <i class="far fa-heart likeBorder" ></i>--}}
{{--                </div>--}}
            </div>
            <div class="col-lg-{{$sideNum}}">
                @if($kind == 'destination')
                    @include('main.destination.destinationSidePic')
                @elseif($kind == 'category')
                    @include('main.destination.category.categorySidePic')
                @elseif($kind == 'activity')
                    @include('main.activity.activitySidePic')
                @else
                    @include('main.package.packageSidePic')
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if($kind == 'destination')
                    @include('main.destination.destinationMainContent')
                @elseif($kind == 'category')
                    @include('main.destination.category.categoryMainContent')
                @elseif($kind == 'activity')
                    @include('main.activity.activityMainContent')
                @else
                    @include('main.package.packageMainContent')
                @endif
            </div>
        </div>

    </div>

    <div id="albumeDiv" class="albumeDiv">
        <div class="closeAlbume" onclick="closeAlbum()">
            <i class="fas fa-times" style="color: white"></i>
        </div>
        <div class="swiper-container gallery-top">
            <div id="mainAlbum" class="swiper-wrapper"></div>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-white"></div>
            <div class="swiper-button-prev swiper-button-white"></div>
        </div>
        <div class="swiper-container gallery-thumbs">
            <div id="thumbAlbum" class="swiper-wrapper"></div>
        </div>
    </div>
@endsection


@section('script')

    <script !src="">
        let galleryThumbs = 0;
        let galleryTop = 0;

        function createAlbum(_main, _thumbnail){
            if(galleryThumbs != 0)
                galleryThumbs.destroy(false, true);
            if(galleryTop != 0)
                galleryTop.destroy(false, true);

            $('#albumeDiv').show();

            $('#mainAlbum').html('');
            $('#thumbAlbum').html('');

            for(let i = 0; i < _main.length; i++){
                $('#mainAlbum').append(_main[i]);
                $('#thumbAlbum').append(_thumbnail[i]);
            }


            galleryThumbs = new Swiper('.gallery-thumbs', {
                slidesPerView: 'auto',
                // loop: true,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });
            galleryTop = new Swiper('.gallery-top', {
                spaceBetween: 10,
                loop:true,
                loopedSlides: 5, //looped slides should be the same
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                thumbs: {
                    swiper: galleryThumbs,
                },
            });

        }

        function closeAlbum(){
            $('#albumeDiv').hide();
        }

        let sliderPics = {!! $content !!};
        sliderPics = sliderPics.slidePic;

        function openMainPicAlbum(_id){
            let main = [];
            let main0 = [];
            let thumb = [];
            let thumb0 = [];
            let allow = false;

            for(let i = 0; i < sliderPics.length; i++){
                if(allow){
                    main.push('<div class="swiper-slide albumePic" ><img src="' + sliderPics[i]["pic"] + '" style="max-height: 100%; max-width: 100%;"></div>');
                    thumb.push('<div class="swiper-slide albumePic" style="background-image:url(' + sliderPics[i]["thumbnail"] + '); cursor:pointer;"></div>');
                }
                else {
                    if (sliderPics[i]['id'] == _id){
                        main.push('<div class="swiper-slide albumePic" ><img src="' + sliderPics[i]["pic"] + '"  style="max-height: 100%; max-width: 100%;"></div>');
                        thumb.push('<div class="swiper-slide albumePic" style="background-image:url(' + sliderPics[i]["thumbnail"] + '); cursor:pointer;"></div>');
                        allow = true;
                    }
                    else{
                        main0.push('<div class="swiper-slide albumePic" ><img src="' + sliderPics[i]["pic"] + '"  style="max-height: 100%; max-width: 100%;"></div>');
                        thumb0.push('<div class="swiper-slide albumePic" style="background-image:url(' + sliderPics[i]["thumbnail"] + '); cursor:pointer;"></div>');
                    }
                }
            }

            main = main.concat(main0);
            thumb = thumb.concat(thumb0);

            createAlbum(main, thumb);
        }
    </script>

    <script>
        var swiper = new Swiper('.mainSS', {
            loop: true,
            navigation: {
                nextEl: '#nextSlider',
                prevEl: '#prevSlider',
            },
        });

        $(document).ready(function(){
            resizeImg('sliderImg');
            $(window).resize(function(){
                resizeImg('sliderImg');
            });
        });


    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>
@endsection
