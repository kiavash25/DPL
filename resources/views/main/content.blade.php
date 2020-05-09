@extends('layouts.base')

@section('head')

    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">

    <script src="{{asset('js/swiper/swiper.js')}}"></script>
    <style>
        .mainSliderPic{
            width: 100%;
            height: 500px;
            background: #ebeef2;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .swiper-wrapper{
            display: flex;
            align-items: center;
        }
        .sliderButton{
            width: 50px;
            height: 50px;
            position: absolute;
            background-color: #ebeef2;
            border-radius: 50%;
            top: 45%;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .nextSlider{
            right: 15px;
        }
        .prevSlider{
            left: 15px;
        }
        .slider.arrow{
            border: solid #6b6b6b;
            border-width: 0 4px 4px 0;
        }
        .sliderImg{
            width: 100%;
        }

        .likeButton{
            width: 65px;
            height: 65px;
            position: absolute;
            top: 90%;
            margin: 0;
            right: 5%;
            background-color: #ebeef2;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 30px;
            border: 1px solid #c7d0d9;
            box-shadow: 0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.14), 0 1px 5px 0 rgba(0,0,0,.14);
            color: #9e9e9e;
            cursor: pointer;
            z-index: 1;

        }
        .likeBack{
            z-index: 1;
            transition: .3s;
        }
        .likeBorder{
            z-index: 2;
            position: absolute;
            transition: .3s;
        }
        .likeButton:hover .likeBack{
            color: #fb2626;
        }
        .likeButton:hover .likeBorder{
            color: #ff9e9e;
        }
        .mainSliderImgTop{
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }


        .albumeDiv{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0px;
            right: 0px;
            z-index: 9;
            background-color: #211919;
        }
        .closeAlbume{
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 70px;
            cursor: pointer;
            z-index: 2;
        }
        .albumePic{
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-position: center;
            background-repeat: no-repeat;
        }
        .gallery-top {
            height: 80%;
            width: 100%;
        }
        .gallery-thumbs {
            height: 20%;
            box-sizing: border-box;
            padding: 10px 0;
        }
        .gallery-thumbs .swiper-slide {
            height: 100px;
            width: 100px;
            opacity: 0.4;
        }
        .gallery-thumbs .swiper-slide-thumb-active {
            opacity: 1;
        }
    </style>
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
            <div id="thumbAlbum" class="swiper-wrapper" style="justify-content: center;"></div>
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

            $('#thumbAlbum').css('justify-content', 'center');
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
