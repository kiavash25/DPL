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
    </style>
@endsection

@section('body')
    <div class="container">
        <div class="row" style="margin-bottom: 30px">
            <div class="col-lg-7">
                <div class="mainSliderPic">
                    <div class="swiper-container mainSS" style="height: 100%;">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide" style="overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                <img src="{{$content->pic}}" class="sliderImg">
                            </div>
                            @foreach($content->sidePic as $item)
                                <div class="swiper-slide" style="overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                    <img src="{{$item->pic}}" class="sliderImg">
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
            <div class="col-lg-5">
                @if($kind == 'destination')
                    @include('main.destination.destinationSidePic')
                @else
                    @include('main.package.packageSidePic')
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if($kind == 'destination')
                    @include('main.destination.destinationMainContent')
                @else
                    @include('main.package.packageMainContent')
                @endif
            </div>
        </div>


    </div>
@endsection


@section('script')

    <script>
        var swiper = new Swiper('.mainSS', {
            loop: true,
            navigation: {
                nextEl: '#nextSlider',
                prevEl: '#prevSlider',
            },
        });

        resizeImg('sliderImg');
        $(window).resize(function(){
            resizeImg('sliderImg');
        });

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: {{$content->lat}}, lng: {{$content->lng}}},
                zoom: 18
            });
            marker = new google.maps.Marker({
                position: {
                    lat: parseFloat( {{$content->lat}} ),
                    lng: parseFloat( {{$content->lng}} )
                },
                map: map,
            })
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>
@endsection
