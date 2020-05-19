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
    @else
        <style>
            .textSlider{
                /*font-family: 'Kaushan Script', cursive;*/
                font-family: 'Archivo Black', sans-serif;
            }
            .navTabName{
                font-size: 15px ;
            }
        </style>
    @endif

@endsection

@section('body')
    <div class="topSlider">
        @if(count($mainPageSlider) > 1)
            <div class="swiper-container picSliderSwiper">
                <div class="swiper-wrapper">
                    @foreach($mainPageSlider as $item)
                        <div class="swiper-slide contentHtmlCenter picSliderSwiperSlide" style="overflow: hidden">
                            <img class="resizeImage" src="{{$item->pic}}" alt="DPL" style="height: 450px">
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="swiper-slide contentHtmlCenter picSliderSwiperSlide" style="overflow: hidden">
                <img class="resizeImage" src="{{$mainPageSlider[0]->pic}}" alt="DPL" style="height: 450px">
            </div>
        @endif



        <div class="textSlider" style=" flex-direction: column; text-align: center; z-index: 1; cursor: context-menu;">
            {{__('Culventure: metamorphosis of travel Experience')}}
            @if(count($mainPageSlider) > 1)
                <div id="nextMainSlider" class="sliderButton nextSlider">
                    <div class="slider arrow right"></div>
                </div>
                <div id="prevMainSlider" class="sliderButton prevSlider">
                    <div class="slider arrow left"></div>
                </div>
            @endif
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
        <div class="mainContentSection">
            <div class="aboutHeader" style="text-align: center">
                {{__('Most Popular')}}
            </div>
            <div class="mainRecentlyPackageTopDiv recentlyPackageNonSwiper ">
                @foreach($recentlyPackage as $item)
                        <div class="recentlyPackageDiv">
                            <a href="{{$item->url}}">
                                <div class="recentlyPackageImg">
                                    <img src="{{$item->pic}}" class="resizeImage" style="width: 100%;">
                                </div>
                            </a>
                            <div class="recentlyPackageText">
                                <a href="{{$item->url}}" class="recentlyPackageName">
                                    {{$item->name}}
                                </a>
                                <div class="recentlyPackageCost">
                                    15 Day <span style="font-weight: bold">{{$item->money}}$</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
            </div>
            <div class="recentlyPackageSwiper">
                <div id="recentSwiper" class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($recentlyPackage as $item)
                            <div class="swiper-slide recentlyPackageDiv">
                                <a href="{{$item->url}}">
                                    <div class="recentlyPackageImg">
                                        <img src="{{$item->pic}}" class="resizeImage" style="width: 100%;">
                                    </div>
                                </a>
                                <div class="recentlyPackageText">
                                    <a href="{{$item->url}}" class="recentlyPackageName">
                                        {{$item->name}}
                                    </a>
                                    <div class="recentlyPackageCost">
                                        15 Day <span style="font-weight: bold">{{$item->money}}$</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="packageSideButtonDiv">
                    <div id="nextPackageSide" class="sliderButton nextSlider" style="top: 33%; right: 10px; box-shadow: 0 0 10px 3px black">
                        <div class="slider arrow right"></div>
                    </div>
                    <div id="prevPackageSide" class="sliderButton prevSlider" style="top: 33%; left: 10px; box-shadow: 0 0 10px 3px black">
                        <div class="slider arrow left"></div>
                    </div>
                </div>

            </div>
        </div>

        @if(isset($mainSliderJournal) && count($mainSliderJournal) > 0)
            <div class="mainContentSection">
                <div class="aboutHeader" style="text-align: center">
                    {{__('Recent News')}}
                </div>

                <div class="mainRecentlyPackageTopDiv">
                    @foreach($mainSliderJournal as $item)
                        <a href="{{$item->url}}" target="_blank" class="JournalDiv">
                            <img src="{{$item->pic}}" class="resizeImage" style="width: 100%">
                            <div class="JournalContentDiv">
                                <div class="JournalName">{{$item->name}}</div>
                                <div class="JournalCategory">{{$item->category}}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mapSection">
            <div id="map" class="map" style="min-height: 400px"></div>
            <div class="mapOptionDiv">
                <div class="insideOptiponMap" onclick="toggleMapOption(this)">
                    <div class="threeLineDiv">
                        <div class="navThreeLine1"></div>
                        <div class="navThreeLine2"></div>
                        <div class="navThreeLine3"></div>
                    </div>
                </div>
                <div class="mapOptionContentDiv">
                    @foreach($destCategory as $item)
                        <div class="mapOptionContent mapOptionContentActive" onclick="toggleMapMarker({{$item->id}}, this)">{{$item->name}}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let packageSwiper = [];
        let rowsNum = 0;

        new Swiper('#recentSwiper', {
            loop: true,
            slidesPerView: 'auto',
            navigation: {
                nextEl: '#nextPackageSide',
                prevEl: '#prevPackageSide',
            },
        });

        var swiper = new Swiper('.picSliderSwiper', {
            loop: true,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '#nextMainSlider',
                prevEl: '#prevMainSlider',
            },
        });

    </script>

    <script>
        let season = 0;
        let activity = 0;
        let isOpenSearch = 0;

        $(window).resize(function () {
            resizeImg('resizeImage');
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


    <script>
        let mapDestinations = {!! $mapDestination !!};
        let mapMarker = [];
        console.log(mapDestinations);
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 32.427908, lng: 53.688046},
                zoom: 5
            });

            mapDestinations.forEach((category) => {
                mapMarker[category.id] = [];
               category.destinations.forEach((dest) => {
                   let _icon;
                   if(category.icon !=  null)
                       _icon = {
                           url: category.icon, // url
                           scaledSize: new google.maps.Size(30, 30), // scaled size
                           origin: new google.maps.Point(0,0), // origin
                           anchor: new google.maps.Point(15, 30) // anchor
                       };
                   else
                       _icon = null;

                  let m = new google.maps.Marker({
                      position: new google.maps.LatLng(dest.lat, dest.lng),
                      title: dest.name,
                      icon: _icon,
                      map: map,
                  });

                  m.addListener('click', function () {
                      window.open(dest.url);
                  });
                   mapMarker[category.id].push(m);
               });
            });
        }

        function toggleMapOption(x) {
            x.classList.toggle("change");
            $('.mapOptionDiv').toggleClass('openOptionDiv', function(){
                alert('end');
            });
            $('.mapOptionContentDiv').toggleClass('mapOptionContentDivOpen');
        }

        function toggleMapMarker(_id, _element){
            $(_element).toggleClass('mapOptionContentActive');
            mapMarker[_id].forEach((item) => {
                if(item.getVisible())
                    item.setVisible(false);
                else
                    item.setVisible(true);
            })
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>

@endsection

