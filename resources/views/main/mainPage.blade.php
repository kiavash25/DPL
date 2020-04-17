@extends('layouts.base')

@section('head')
    <link rel="stylesheet" href="{{asset('css/pages/mainPage.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Archivo+Black|Caveat|Kaushan+Script|Lobster&display=swap"
          rel="stylesheet">

    <style>
        .fullLabel {
            width: 100%;
            height: 100%;
            left: 0px;
            top: 0px;
            padding: 15px 10px;;
        }
    </style>

    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">
    <script src="{{asset('js/swiper/swiper.js')}}"></script>

    <link rel="stylesheet" href="{{asset('css/common/sliderPacks.css')}}">


    @if(app()->getLocale() == 'fa')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlMainPage.css')}}">
    @endif

@endsection

@section('body')
    <div class="topSlider">
        <img class="mainSliderPic resizeImage" src="{{ asset('images/slider.jpg')}}" alt="DPL">
        <div class="textSlider" style="font-family: 'Kaushan Script', cursive; flex-direction: column;">
            It's Time To <span
                style="font-family: 'Archivo Black', sans-serif; font-size: 100px; color: white"> Travel</span>
        </div>
    </div>

    <div class="mainSearchCenter">
        <div class="row searchCenter">
            <div class="col-lg-10" style="padding: 0px">
                <form id="searchForm" class="row threeSearchDiv" action="{{route('beforeList')}}" method="post">
                    {{csrf_field()}}
                    <label for="centerSearchInputWhere" class="col-md-5 whereToSearch">
                        <div class="navSearchIcon" style="position: absolute; left: 0px">
                            <img src="{{asset('images/mainImage/searchIcon.svg')}}" style="width: 100%;">
                        </div>
                        <label class="centerSearchLabel fullLabel" onclick="searchLabelClick(this)"
                               style="right: 0; left: auto; width: 85%">{{__('Where to?')}}</label>
                        <input id="centerSearchInputWhere" class="centerSearchInput" name="destination" type="text"
                               onfocus="changeLabelInput(this)" onfocusout="closeAllMainSearchSuggestion()"
                               onkeyup="changeSearchDestination(this)">

                        <div id="destinationMainSearch" class="destinationMainSearch"></div>
                    </label>

                    <label for="centerSearchInputSeason" class="col-md-3 whereToSearch"
                           style="border-radius: 0px; cursor: pointer">
                        <label class="centerSearchLabel fullLabel"
                               onclick="changeLabelInputSeason(this)">{{__('What season?')}}</label>
                        <input id="centerSearchInputSeason" class="centerSearchInput" name="season" type="text"
                               onfocusout="closeAllMainSearchSuggestion()" style="width: 100%; cursor: pointer"
                               readonly>

                        <div class="seasonSearch">
                            <div class="seasons" onclick="selectSeason(this, 'Spring')">{{__('Spring')}}</div>
                            <div class="seasons" onclick="selectSeason(this, 'Summer')">{{__('Summer')}}</div>
                            <div class="seasons" onclick="selectSeason(this, 'Fall')">{{__('Fall')}}</div>
                            <div class="seasons" onclick="selectSeason(this, 'Winter')">{{__('Winter')}}</div>
                            <div class="seasons" onclick="selectSeason(this, 'none')" style="width: 100%">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>

                    </label>

                    <label for="centerSearchInputActivity" class="col-md-3 whereToSearch"
                           style="border-radius: 0px; cursor: pointer">
                        <label class="centerSearchLabel fullLabel"
                               onclick="changeLabelInputActivity(this)">{{__('What Activity?')}}</label>
                        <input id="centerSearchInputActivity" name="activity" class="centerSearchInput" type="text"
                               onfocusout="closeAllMainSearchSuggestion()" style="width: 100%; cursor: pointer"
                               readonly>

                        <div class="seasonSearch">
                            @foreach($activitiesList as $item)
                                <div class="seasons activity" onclick="selectActivity(this)">{{$item->name}}</div>
                            @endforeach
                            <div class="seasons activity" onclick="selectActivity(this)" style="width: 100%">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>

                    </label>
                </form>
            </div>
            <label class="col-lg-2" style="display: flex; margin: 0; padding: 0px">
                <button class="btn btn-warning searchButton" onclick="mainSearch()">{{__('Search')}}</button>
            </label>
        </div>
    </div>




    <div class="container" style="margin-bottom: 50px;">

        <div style="width: 100%; margin-top: 45px">
            <a href="#">
                <div class="aboutHeader">
                    {{__('main.Recently Package')}}
                </div>
            </a>
            <div class="mainContentPackages">
                <div class="swiper-container packageSwiper">

                    <div class="swiper-wrapper" style="padding: 10px 0px">
                        @foreach($recentlyPackage as $item)
                            <div class="swiper-slide swiperSlidePackage contentHtmlCenter">
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
                                                {{__('main.See Destination')}}
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

                    <div id="nextPackage" class="sliderButton nextSlider"
                         style="top: 33%; right: 10px; box-shadow: 0 0 10px 3px black">
                        <div class="slider arrow right"></div>
                    </div>
                    <div id="prevPackage" class="sliderButton prevSlider"
                         style="top: 33%; left: 10px; box-shadow: 0 0 10px 3px black">
                        <div class="slider arrow left"></div>
                    </div>
                </div>
            </div>
        </div>

    @foreach($destinationCategoryMain as $categ)
            @if(count($categ->destination) > 0)
                <div style="width: 100%; margin-top: 45px">
                    <a href="#">
                        <div class="aboutHeader">
                            {{$categ->name}} {{__('Destination')}}
                        </div>
                    </a>
                    <div class="mainContentPackages">
                        <div class="swiper-container packageSwiper">

                            <div class="swiper-wrapper" style="padding: 10px 0px">
                                @foreach($categ->destination as $item)
                                    <div class="swiper-slide swiperSlidePackage contentHtmlCenter">
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
                                                        {{__('main.See Destination')}}
                                                    </a>
                                                </div>
                                                {{--                                        <div class="packageActivity">--}}
                                                {{--                                            {{$item->mainActivity->name}}--}}
                                                {{--                                        </div>--}}
                                            </div>

                                            {{--                                            <div class="packageDate">--}}
                                            {{--                                                <div style="color: white">{{$item->sD}}</div>--}}
                                            {{--                                                <div style="color: white">{{$item->sM}}</div>--}}
                                            {{--                                            </div>--}}

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div id="nextPackage" class="sliderButton nextSlider"
                                 style="top: 33%; right: 10px; box-shadow: 0 0 10px 3px black">
                                <div class="slider arrow right"></div>
                            </div>
                            <div id="prevPackage" class="sliderButton prevSlider"
                                 style="top: 33%; left: 10px; box-shadow: 0 0 10px 3px black">
                                <div class="slider arrow left"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        {{--        <div id="map" class="map"></div>--}}
    </div>
@endsection

@section('script')
    <script>
        let packageSwiper = [];
        let rowsNum = 0;

        function resizePackageSwiper() {
            rowsNum = 0;
            let windowW = $(window).width();
            let rows = $('.swiper-wrapper');
            for (item of rows) {
                packageCount = $(item).children().length;

                if (!packageSwiper[rowsNum])
                    packageSwiper[rowsNum] = 0;


                if ((windowW > 1400 && packageCount <= 5) || (windowW > 1100 && packageCount <= 4) ||
                    (windowW > 840 && packageCount <= 3) || (windowW > 585 && packageCount <= 2) ||
                    (windowW <= 585)) {
                    $(item).css('overflow-y', 'hidden');
                    $(item).next().hide();
                    $(item).next().next().hide();

                    let childs = $(item).children();
                    for (child of childs) {
                        $(child).css('width', 'auto');
                        $(child).css('height', 'auto');
                        $(child).parent().css('overflow-x', 'auto');
                        $(child).parent().css('height', '275px');
                        $(child).find('.packages').css('margin', '10px 20px');
                    }

                    if (packageSwiper[rowsNum] != 0)
                        packageSwiper[rowsNum].destroy(true, true);
                    packageSwiper[rowsNum] = 0;
                } else {
                    packageSwiper[rowsNum] = new Swiper($(item).parent(), {
                        loop: false,
                        navigation: {
                            nextEl: $(item).next(),
                            prevEl: $(item).next().next()
                        },
                        breakpoints: {
                            585: {
                                slidesPerView: 1,
                            },
                            840: {
                                slidesPerView: 2,
                            },
                            1100: {
                                slidesPerView: 3,
                            },
                            1400: {
                                slidesPerView: 4,
                            },
                            14000: {
                                slidesPerView: 5,
                            }
                        }
                    });
                }

                rowsNum++;
            }
        }

        resizePackageSwiper();
    </script>


    <script>
        let season = 0;
        let activity = 0;
        let isOpenSearch = 0;

        $(window).resize(function () {
            resizeImg('mainSliderPic');
        });

        function searchLabelClick(_element) {
            $(_element).next().focus();
        }

        function changeLabelInput(_element) {

            closeAllMainSearchSuggestion(function () {
                $(_element).prev().addClass('centerSearchLabelFocus');
            });
        }

        function changeLabelInputSeason(_element) {

            closeAllMainSearchSuggestion(function () {
                $(_element).addClass('centerSearchLabelFocus');
                $(_element).next().next().css('display', 'flex');
                $(_element).next().focus();
            });

        }

        function selectSeason(_element, _season) {
            if (_season == 'none') {
                season = 0;
                $(_element).parent().prev().val('');
            } else {
                season = _season;
                text = $(_element).text();
                $(_element).parent().prev().val(text);
            }
            closeAllMainSearchSuggestion();
        }

        function changeLabelInputActivity(_element, _kind) {
            closeAllMainSearchSuggestion(function () {
                $(_element).addClass('centerSearchLabelFocus');
                $(_element).next().next().css('display', 'flex');
                $(_element).next().focus();
            });
        }

        function selectActivity(_element) {
            let activityInput = $(_element).text();

            if (activityInput.trim().length == 0) {
                activity = 0;
                $(_element).parent().prev().val('');
            } else {
                activity = activityInput;
                $(_element).parent().prev().val(activityInput);
            }
            closeAllMainSearchSuggestion();
        }

        function changeSearchDestination(_element) {
            let value = $(_element).val();
            if (value.trim().length > 0) {
                $('#destinationMainSearch').html('');
                $.ajax({
                    type: 'post',
                    url: '{{route("findDestination")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        name: value,
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        if (response['status'] == 'ok') {
                            let text = '';
                            for (let i = 0; i < response['result'].length; i++)
                                text += '<div class="destinationMainSearchResult" data-value="' + response["result"][i]["id"] + '" onclick="chooseWhereSearch(this)">' + response["result"][i]["name"] + '</div>';

                            $('#destinationMainSearch').html(text);
                            $(_element).next().show();
                        } else {
                            console.log(response);
                        }
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            } else
                $(_element).next().hide();
        }

        function chooseWhereSearch(_element) {
            let text = $(_element).text();
            $('#centerSearchInputWhere').val(text);

            closeAllMainSearchSuggestion();
        }

        function closeAllMainSearchSuggestion(_callBack = '') {

            setTimeout(function () {
                if ($('#centerSearchInputActivity').val() == '')
                    $('#centerSearchInputActivity').prev().removeClass('centerSearchLabelFocus');
                $('#centerSearchInputActivity').next().css('display', 'none');

                if ($('#centerSearchInputSeason').val() == '')
                    $('#centerSearchInputSeason').prev().removeClass('centerSearchLabelFocus');
                $('#centerSearchInputSeason').next().css('display', 'none');

                if (isOpenSearch != 'whereTo') {
                    if ($('#centerSearchInputWhere').val() == '')
                        $('#centerSearchInputWhere').prev().removeClass('centerSearchLabelFocus');
                    $('#centerSearchInputWhere').next().css('display', 'none');
                }

                if (typeof _callBack == 'function')
                    _callBack();
            }, 200);
        }

        function mainSearch() {
            let destination = $('#centerSearchInputWhere').val();
            let season = $('#centerSearchInputSeason').val();
            let activity = $('#centerSearchInputActivity').val();

            if (destination.trim().length == 0)
                destination = 'all';

            if (season.trim().length == 0)
                season = 'all';

            if (activity.trim().length == 0)
                activity = 'all';

            if (activity != 'all' || season != 'all' || destination != 'all') {
                $('#searchForm').submit();
            }
        }
    </script>


    {{--    map Section--}}

    {{--    <script>--}}
    {{--let mapMarker = {!! $mapDestination !!};--}}
    {{--let catIds = {!! $catId !!};--}}
    {{--function initMap() {--}}
    {{--    map = new google.maps.Map(document.getElementById('map'), {--}}
    {{--        center: {lat: 32.427908, lng: 53.688046},--}}
    {{--        zoom: 5--}}
    {{--    });--}}

    {{--    for(let i = 0; i < catIds.length; i++){--}}
    {{--        if(mapMarker[catIds[i]]) {--}}
    {{--            for (let j = 0; j < mapMarker[catIds[i]].length; j++) {--}}
    {{--                mapMarker[catIds[i]][j]['marker'] = new google.maps.Marker({--}}
    {{--                    position: new google.maps.LatLng(mapMarker[catIds[i]][j]['lat'], mapMarker[catIds[i]][j]['lng']),--}}
    {{--                    title: mapMarker[catIds[i]][j]['name'],--}}
    {{--                    icon: {--}}
    {{--                        url: mapMarker[catIds[i]][j]['mapIcon'], // url--}}
    {{--                        scaledSize: new google.maps.Size(30, 30), // scaled size--}}
    {{--                        origin: new google.maps.Point(0,0), // origin--}}
    {{--                        anchor: new google.maps.Point(15, 30) // anchor--}}
    {{--                    },--}}
    {{--                    map: map,--}}
    {{--                });--}}

    {{--                mapMarker[catIds[i]][j]['marker'].addListener('click', function () {--}}
    {{--                    window.open('{{url('destination')}}/' + mapMarker[catIds[i]][j]['categoryId'] + '/' + mapMarker[catIds[i]][j]['slug']);--}}
    {{--                })--}}
    {{--            }--}}
    {{--        }--}}
    {{--    }--}}
    {{--}--}}
    {{--    </script>--}}
    {{--    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>--}}

@endsection

