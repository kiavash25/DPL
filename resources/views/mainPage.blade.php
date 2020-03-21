@extends('layouts.base')

@section('head')

    <style>
        .topSlider{
            height: 400px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        .mainSliderPic{
            width: 100%;
        }
        .textSlider{
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            top: 0px;
            left: 0px;
            background-color: #00000061;
            color: white;
            font-size: 60px;
        }
        .mainSearchCenter{
            width: 100%;
            position: relative;
            height: 20px;
        }
        .searchCenter{
            top: -25px;
            width: 50%;
            height: 80px;
            background: #ebeef2;
            position: absolute;
            z-index: 1;
            margin-left: 25%;
            box-shadow: 0 0 8px rgba(0,0,0,.2);
            border-radius: 8px;
            padding: 10px;
        }
        .whereToSearch{
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
            cursor: text;
            border-radius: 8px 0px 0px 8px;
            border: 1px solid #c7d0d9;
            margin: 0;
        }
        .centerSearchInput{
            font-size: 15px;
            border: none;
            padding-left: 20px;
            width: 80%;
            padding-top: 15px;

        }
        .centerSearchLabel{
            position: absolute;
            left: 45px;
            color: gray;
            transition: .3s;
        }
        .centerSearchLabelFocus{
            font-size: 12px;
            top: 7px;
            color: #409bd0;
        }
        .seasonSearch{
            position: absolute;
            background: white;
            width: 100%;
            top: 65px;
            padding: 5px;
            border: 1px solid #c7d0d9;
            display: none;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
        .seasons{
            width: 45%;
            text-align: center;
            border: 1px solid #c7d0d9;
            margin: 2%;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.1s;
        }
        .seasons:hover{
            background-color: #409bd0;
            color: white;
        }

        @media (max-width:  1300px){
            .searchCenter{
                width: 75%;
                margin-left: 12%;
            }
        }

        @media (max-width:  1023px){
            .topSlider{
                height: 300px;
            }
        }

    </style>
    <link href="https://fonts.googleapis.com/css?family=Archivo+Black|Caveat|Kaushan+Script|Lobster&display=swap" rel="stylesheet">
@endsection

@section('body')
    <div class="topSlider">
        <img class="mainSliderPic" src="{{ asset('images/slider.jpg')}}" alt="">
        <div class="textSlider" style="font-family: 'Kaushan Script', cursive; flex-direction: column;">
            It's Time To <span style="font-family: 'Archivo Black', sans-serif; font-size: 100px"> Travel</span>
        </div>
    </div>

    <div class="mainSearchCenter">
        <div class="row searchCenter">
            <label for="centerSearchInputWhere" class="col-lg-3 col-md-4 whereToSearch">
                <div class="navSearchIcon" style="position: absolute; left: 0px">
                    <img src="{{asset('images/mainImage/searchIcon.svg')}}" style="width: 100%;">
                </div>
                <label class="centerSearchLabel" onclick="searchLabelClick(this)">Where to?</label>
                <input id="centerSearchInputWhere" class="centerSearchInput" type="text" onfocus="changeLabelInput(this, 1)" onfocusout="changeLabelInput(this, 0)">
            </label>
            <label for="centerSearchInputSeason" class="col-lg-3 col-md-4 whereToSearch" style="margin-left: 10px; border-radius: 0px; cursor: pointer" onclick="changeLabelInputSeason(this, 1)">
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

            <label for="centerSearchInputActivity" class="col-lg-3 col-md-4 whereToSearch" style="margin-left: 10px; border-radius: 0px; cursor: pointer" onclick="changeLabelInputActivity(this, 1)">
                <label class="centerSearchLabel" style="left: 10px">What Activity?</label>
                <input id="centerSearchInputActivity" class="centerSearchInput" type="text" onfocusout="changeLabelInputActivity(this, 0)" style="width: 100%; cursor: pointer" readonly>

                <div class="seasonSearch">
                    <div class="seasons" onclick="selectActivity('Ski', this)">Ski</div>
                    <div class="seasons" onclick="selectActivity('Walking', this)">Walking</div>
                    <div class="seasons" onclick="selectActivity('Mountaineering', this)">Mountaineering</div>
                    <div class="seasons" onclick="selectActivity('OffRoad', this)">OffRoad</div>
                    <div class="seasons" onclick="selectActivity('Ice climbing', this)">Ice climbing</div>
                    <div class="seasons" onclick="selectActivity('Fieldwork', this)">Fieldwork</div>
                    <div class="seasons" onclick="selectActivity('close', this)" style="width: 100%">
                        <i class="fas fa-times"></i>
                    </div>
                </div>

            </label>
        </div>
    </div>


    <div class="container" style="margin-bottom: 200px;">
        <div id="map" style="width: 100%; height: 50vh;"></div>
    </div>
@endsection

@section('script')

    <script>
        var season = 0;
        var activity = 0;

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


        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 54.711382812500005, lng: 33.340562481212146},
                zoom: 2
            });
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>

@endsection

