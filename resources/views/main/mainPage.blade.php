@extends('layouts.base')

@section('head')
    <link rel="stylesheet" href="{{asset('css/pages/mainPage.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Archivo+Black|Caveat|Kaushan+Script|Lobster&display=swap" rel="stylesheet">
@endsection

@section('body')
    <div class="topSlider">
        <img class="mainSliderPic" src="{{ asset('images/slider.jpg')}}" alt="">
        <div class="textSlider" style="font-family: 'Kaushan Script', cursive; flex-direction: column;">
            It's Time To <span style="font-family: 'Archivo Black', sans-serif; font-size: 100px; color: white"> Travel</span>
        </div>
    </div>

    <div class="mainSearchCenter">
        <div class="row searchCenter">
            <div class="col-lg-10" style="padding: 0px">
                <div class="row threeSearchDiv">
                    <label for="centerSearchInputWhere" class="col-md-5 whereToSearch">
                        <div class="navSearchIcon" style="position: absolute; left: 0px">
                            <img src="{{asset('images/mainImage/searchIcon.svg')}}" style="width: 100%;">
                        </div>
                        <label class="centerSearchLabel" onclick="searchLabelClick(this)">Where to?</label>
                        <input id="centerSearchInputWhere" class="centerSearchInput" type="text" onfocus="changeLabelInput(this, 1)" onfocusout="changeLabelInput(this, 0)" onkeyup="changeSearchDestination(this)">

                        <div class="destinationMainSearch">
                            <div class="destinationMainSearchResult">Rome</div>
                            <div class="destinationMainSearchResult">Iran</div>
                            <div class="destinationMainSearchResult">Tehran</div>
                            <div class="destinationMainSearchResult">Italy</div>
                        </div>
                    </label>

                    <label for="centerSearchInputSeason" class="col-md-3 whereToSearch" style="border-radius: 0px; cursor: pointer" onclick="changeLabelInputSeason(this, 1)">
                        <label class="centerSearchLabel" style="left: 10px">What season?</label>
                        <input id="centerSearchInputSeason" class="centerSearchInput" type="text" onfocusout="changeLabelInputSeason(this, 0)" style="width: 100%; cursor: pointer" readonly>

                        <div class="seasonSearch">
                            <div class="seasons" onclick="selectSeason('Spring', this)">Spring</div>
                            <div class="seasons" onclick="selectSeason('Summer', this)">Summer</div>
                            <div class="seasons" onclick="selectSeason('Fall', this)">Fall</div>
                            <div class="seasons" onclick="selectSeason('Winter', this)">Winter</div>
                            <div class="seasons" onclick="selectSeason('close', this)" style="width: 100%">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>

                    </label>

                    <label for="centerSearchInputActivity" class="col-md-3 whereToSearch" style="border-radius: 0px; cursor: pointer" onclick="changeLabelInputActivity(this, 1)">
                        <label class="centerSearchLabel" style="left: 10px">What Activity?</label>
                        <input id="centerSearchInputActivity" class="centerSearchInput" type="text" onfocusout="changeLabelInputActivity(this, 0)" style="width: 100%; cursor: pointer" readonly>

                        <div class="seasonSearch">
                            <div class="seasons activity" onclick="selectActivity('Ski', this)">Ski</div>
                            <div class="seasons activity" onclick="selectActivity('Walking', this)">Walking</div>
                            <div class="seasons activity" onclick="selectActivity('Mountaineering', this)">Mountaineering</div>
                            <div class="seasons activity" onclick="selectActivity('OffRoad', this)">OffRoad</div>
                            <div class="seasons activity" onclick="selectActivity('Ice climbing', this)">Ice climbing</div>
                            <div class="seasons activity" onclick="selectActivity('Fieldwork', this)">Fieldwork</div>
                            <div class="seasons activity" onclick="selectActivity('close', this)" style="width: 100%">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>

                    </label>

                </div>
            </div>
            <label class="col-lg-2" style="display: flex; margin: 0; padding: 0px">
                <button class="btn btn-warning searchButton"> Search</button>
            </label>
        </div>
    </div>


    <div class="container" style="margin-bottom: 50px;">
        <div id="map" class="map"></div>
    </div>
@endsection

@section('script')

    <script>
        var season = 0;
        var activity = 0;

        $(window).resize(function(){
            resizeImg('mainSliderPic');
        });

        function searchLabelClick(_element){
            $(_element).next().focus();
        }
        function changeLabelInput(_element, _kind){
            if(_kind == 1)
                $(_element).prev().addClass('centerSearchLabelFocus');
            else{
                var value = $(_element).val();
                if(value.trim().length == 0)
                    $(_element).prev().removeClass('centerSearchLabelFocus');
                $(_element).next().hide();
            }
        }

        function changeLabelInputSeason(_element, _kind){
            if(_kind == 1) {
                $($(_element).children()[0]).addClass('centerSearchLabelFocus');
                $($(_element).children()[2]).css('display', 'flex');
            }
            else{
                setTimeout(function (){
                    if(season == 0)
                        $(_element).prev().removeClass('centerSearchLabelFocus');

                    $(_element).next().hide();
                }, 100);
            }
        }

        function selectSeason(_season, _element){
            if(_season == 'close'){
                season = 0;
                $(_element).parent().prev().val('');
            }
            else {
                season = _season;
                $(_element).parent().prev().val(season);
            }
        }

        function changeLabelInputActivity(_element, _kind){
            if(_kind == 1) {
                $($(_element).children()[0]).addClass('centerSearchLabelFocus');
                $($(_element).children()[2]).css('display', 'flex');
            }
            else{
                setTimeout(function (){
                    if(activity == 0)
                        $(_element).prev().removeClass('centerSearchLabelFocus');

                    $(_element).next().hide();
                }, 100);
            }
        }

        function selectActivity(_activity, _element){
            if(_activity == 'close'){
                activity = 0;
                $(_element).parent().prev().val('');
            }
            else {
                activity = _activity;
                $(_element).parent().prev().val(activity);
            }
        }

        function changeSearchDestination(_element){
            var value = $(_element).val();
            if(value.trim().length > 0){
                setTimeout(function(){
                    $(_element).next().show();
                }, 1000);
            }
            else
                $(_element).next().hide();
        }


        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 54.711382812500005, lng: 33.340562481212146},
                zoom: 2
            });
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>

@endsection

