@extends('layouts.base')

@section('head')

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{asset('css/pages/list.css')}}">

    <style>
        .seasonFilter{
            display: inline-block;
            width: 48%;
            text-align: center;
            padding: 10px;
            border: solid 1px gray;
            border-radius: 8px;
            margin-bottom: 1%;
            font-weight: bold;
            cursor: pointer;
        }
        .seasonFilter:hover{
            background-color: #0a7bbd;
            color: white;
        }
        .choosenSeasonFilter{
            background-color: #0a7bbd;
            color: white;
        }
        .costFilter{
            display: flex;
            justify-content: space-between;
        }
        .maxCostName{
            font-size: 13px;
            font-weight: bold;
        }
        .minCost{
            font-size: 18px;
        }
        .maxCost{
            font-size: 18px;
        }

        .numOfPackages{
            width: 100%;
            font-size: 21px;
            margin-bottom: 11px;
        }
    </style>

@endsection

@section('body')

    <div class="container" style="margin-top: 10px">
        <div class="col-md-12 mainHeader">
            <h2 class="mainHeaderH1">
                {{$title}}
            </h2>
        </div>
        <div class="col-md-12" style="margin-top: 30px">

            <div class="row">
                @if($kind != 'destination')
                    <div class="col-lg-3" style="margin-bottom: 40px">

                    <div class="row sortAndFilterButtonDiv">
                        <div class="sortAndFilterButton">
                            <span style="color: white; width: 100%">Sort & filter</span>
                            <div class="clearAllButtonPc" onclick="deleteAllFilters()">
                                Clear all
                            </div>
                            <div class="filtersInRowPc">
                                <div class="nmbFilterInPc">
                                    <span class="numberOfFilters" style="color: white"></span> filter applied
                                </div>
                                <div class="filtersShow"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row filtersInPcSection">
                        <div class="filterSection">
                            <i class="fa fa-sort sortIcon" aria-hidden="true"></i>
                            <select class="filterSelect" style="padding: 12px; padding-left: 25px" onchange="changeSortList(this.value)">
                                <option value="nearestDate">Nearest date</option>
                                <option value="minConst">Total Price: Lower First</option>
                                <option value="maxConst">Total Price: Highest First</option>
                                <option value="minDay">Duration: Shortest first</option>
                                <option value="maxDay">Duration: Longest first</option>
                            </select>
                        </div>
                    </div>

                    <div class="row filtersInPcSection">
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                Activity
                                <div class="arrow down" style="float: right; margin-right: 5px; border-color: gray"></div>
                                <div class="arrow up" style="float: right; margin-right: 5px; border-color: gray; margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                @foreach($activitiesList as $item)
                                    <label class="containerCheckBox activityFilter{{$item->id}}">{{$item->name}}
                                        <input type="checkbox" data_id="{{$item->id}}" onchange="chaneActivityId(this)" {{$item->id == $activity ? 'checked' : ''}}>
                                        <span class="checkmark"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                Season
                                <div class="arrow down" style="float: right; margin-right: 5px; border-color: gray"></div>
                                <div class="arrow up" style="float: right; margin-right: 5px; border-color: gray; margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <div class="seasonFilter seasonFilterspring" onclick="seasonFilter(this)" data_value="spring">Spring</div>
                                <div class="seasonFilter seasonFiltersummer" onclick="seasonFilter(this)" data_value="summer">Summer</div>
                                <div class="seasonFilter seasonFilterfall" onclick="seasonFilter(this)" data_value="fall">Fall</div>
                                <div class="seasonFilter seasonFilterwinter" onclick="seasonFilter(this)" data_value="winter">Winter</div>
                            </div>
                        </div>
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                Cost
                                <div class="arrow down" style="float: right; margin-right: 5px; border-color: gray"></div>
                                <div class="arrow up" style="float: right; margin-right: 5px; border-color: gray; margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <div class="costFilter">
                                    <div class="maxCostName">min:
                                        <span class="minCost">0</span>
                                    </div>
                                    <div class="maxCostName">max:
                                        <span class="maxCost">5000</span>
                                    </div>
                                </div>
                                <div class="slider-range"></div>
                            </div>
                        </div>
                    </div>

                </div>
                    <div id="scroleBase" class="col-lg-9" style="margin-bottom: 50px">
                        <div id="listSample" style="display: none; flex-wrap: wrap; justify-content: center;">
                            <div class="row listSectionDiv">
                                    <div class="col-md-4 col-sm-12 picSection">
                                        <img src="##imgUrl##" class="imgList" >
                                    </div>
                                    <div class="col-md-5 col-sm-12 contentSection">
                                        <div class="headerContentSection">
                                            <div style="color: #818d99; font-size: 12px" >
                                                ##day##
                                                Days
                                                in
                                                <a href="##destinationUrl##" style="color: #818d99; font-size: 12px" target="_blank">
                                                    ##destinationName##
                                                </a>
                                            </div>
                                            <div style="line-height: 37px">
                                                ##name##
                                            </div>

                                        </div>
                                        <div class="textContentSection">
                                            ##description##
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 infoSection infoSectionPackage">
                                        <div class="packageInfoDiv">
                                            <div class="packageInfoSec">
                                                <div class="packageInfoName">Activity: </div>
                                                <div class="packageInfoValue">##activity##</div>
                                            </div>
                                            <div class="packageInfoSec seDateInfo">
                                                <div>
                                                    <div class="packageInfoName seDateInfoName">Start: </div>
                                                    <div class="packageInfoValue seDateInfoValue">##sDate##</div>
                                                </div>
                                                <div>
                                                    <div class="packageInfoName seDateInfoName">End: </div>
                                                    <div class="packageInfoValue seDateInfoValue">##eDate##</div>
                                                </div>
                                            </div>
                                            <div class="packageInfoSec seDateInfo">
                                                <div>
                                                    <div class="packageInfoName seDateInfoName">Season: </div>
                                                    <div class="packageInfoValue seDateInfoValue">##season##</div>
                                                </div>
                                            </div>
                                            {{--                                        <div class="packageInfoSec">--}}
                                            {{--                                            <div class="packageInfoName">Days: </div>--}}
                                            {{--                                            <div class="packageInfoValue">##day##</div>--}}
                                            {{--                                        </div>--}}
                                            <div class="packageInfoSec costInfoDiv">
                                                <div class="constName">us</div>
                                                <div class="constValue">$##money##</div>
                                            </div>
                                        </div>
                                        <a href="##url##" class="showButton">
                                            View Package
                                        </a>
                                        <div class="pacakgeMoreInfo" onclick="showMoreInfo(this)">
                                            More Information
                                        </div>
                                    </div>

                                    <div class="packageSDateCircle">
                                        ##circleSDate##
                                    </div>
                                </div>
                        </div>
                    </div>
                @else
                    <div id="scroleBase" class="col-lg-12" style="margin-bottom: 50px">
                        <div id="listSample" style="display: flex; flex-wrap: wrap; justify-content: center;">
                            @foreach($destinations as $item)
                                    <div class="row listSectionDiv">
                                        <div class="col-md-4 col-sm-12 picSection">
                                            <img src="{{asset($item->pic)}}" class="imgList" >
                                        </div>
                                        <div class="col-md-5 col-sm-12 contentSection">
                                            <div class="headerContentSection">
                                                {{$item->name}}
                                            </div>
                                            <div class="textContentSection">
                                                {{$item->description}}
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 infoSection" style="flex-direction: column">
                                            <div class="numOfPackages">
                                                Packages: {{$item->package}}
                                            </div>

                                            <a href="{{route('show.destination', ['slug' => $item->slug])}}" class="showButton">
                                                View Destination
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        var maxCost = 5000;
        $( function() {
            $( ".slider-range" ).slider({
                range: true,
                min: 0,
                max: 5000,
                values: [ 0, maxCost ],
                slide: function( event, ui ) {
                    $('.minCost').text('$' + ui.values[0]);
                    $('.maxCost').text('$' + ui.values[1]);
                },
                stop: function(event, ui){
                    cost = [ui.values[0], ui.values[1]];
                    getNewElem();
                }
            });
        } );

        resizeImg('imgList');
        $(window).resize(function(){
            resizeImg('imgList');
        });

        @if($kind != 'destination')
            openLoading();

            function openFilterDropDown(_element){
                var nextElem = $(_element).next();
                if(nextElem.css('display') == 'block'){
                    nextElem.css('display', 'none');
                    $($(_element).children()[0]).css('display', 'block');
                    $($(_element).children()[1]).css('display', 'none');
                }
                else{
                    nextElem.css('display', 'block');
                    $($(_element).children()[1]).css('display', 'block');
                    $($(_element).children()[0]).css('display', 'none');
                }
            }

            function showMoreInfo(_element){
                $(_element).prev().prev().slideToggle();
            }

            var page = 1;
            var perPage = 4;
            var kind = '{{$kind}}';
            var destinationId = '{{$destination}}';
            var activityId = '{{$activity}}';
            var season = '{{$season}}';
            var sort = 'nearestDate';
            var isFinish = false;
            var isLoading = false;
            var listSample = 0;
            var cost = [0, maxCost];
            var tag = '{{$tag}}';

            if(activityId != 'all')
                activityId = [activityId];
            else
                activityId = [];

            if(season != 'all')
                season = [season];
            else
                season = [];


            function changeSortList(_value){
                sort = _value;
                getNewElem();
            }

            function seasonFilter(_element){
                var _season = $(_element).attr('data_value');

                if(season.indexOf(_season) < 0) {
                    season[season.length] = _season;
                    $(_element).addClass('choosenSeasonFilter');
                }
                else {
                    season[season.indexOf(_season)] = 0;
                    $(_element).removeClass('choosenSeasonFilter');
                }

                getNewElem();
            }

            function chaneActivityId(_element){
                id = $(_element).attr('data_id');
                if($(_element).prop('checked')){
                    if(activityId.indexOf(id) < 0)
                        activityId[activityId.length] = id;
                }
                else{
                    activityId[activityId.indexOf(id)] = 0;
                }

                getNewElem();
            }

            function getNewElem(){
                $('#listSample').html('');
                isFinish = false;
                page = 1;
                getListElem();
            }

            function createFilters(){
                var hasFilter = false;
                var text = '';
                var number = 0;

                for(i = 0; i < activityId.length; i++){
                    if(activityId[i] != 0){
                        var name = $('.activityFilter' + activityId[i]).text();
                        hasFilter = true;
                        text += '<div class="filterInPc">\n' +
                                '<i class="fas fa-times" style="color: white; font-size: 17px; margin-right: 10px;" onclick="deleteThisFilter(this)" data_type="activity" data_value="' + activityId[i] + '"></i>\n' +
                            name + ' Activity\n' +
                                '</div>';
                        number++;
                    }
                }

                for(i = 0; i < season.length; i++){
                    if(season[i] != 0){
                        hasFilter = true;
                        text += '<div class="filterInPc">\n' +
                                '<i class="fas fa-times" style="color: white; font-size: 17px; margin-right: 10px;" onclick="deleteThisFilter(this)" data_type="season" data_value="' + season[i] + '"></i>\n' +
                            season[i] + ' Season\n' +
                                '</div>';
                        number++;
                    }
                }

                if(cost[0] != 0 || cost[1] != maxCost){
                    hasFilter = true;
                    text += '<div class="filterInPc">\n' +
                        '<i class="fas fa-times" style="color: white; font-size: 17px; margin-right: 10px;" onclick="deleteThisFilter(this)" data_type="cost" data_value="cost"></i>\n' +
                        ' Cost is : $' + cost[0] + '- $' + cost[1] + ' \n' +
                        '</div>';
                    number++;
                }


                $('.filtersShow').html(text);
                $('.numberOfFilters').text(number);

                if(hasFilter) {
                    $('.filtersInRowPc').show();
                    $('.clearAllButtonPc').show();
                }
                else {
                    $('.filtersInRowPc').hide();
                    $('.clearAllButtonPc').hide();
                }
            }

            function deleteThisFilter(_element, _callback = ''){
                kind = $(_element).attr('data_type');
                value = $(_element).attr('data_value');
                if(kind == 'activity') {
                    activityId[activityId.indexOf(value)] = 0;
                    $($('.activityFilter' + value).children()[0]).prop('checked', false)
                }
                else if(kind == 'season'){
                    season[season.indexOf(value)] = 0;
                    $('.seasonFilter' + value).removeClass('choosenSeasonFilter');
                }
                else if(kind == 'cost'){
                    cost = [0, maxCost];
                    $( ".slider-range" ).slider( "values", [ 0, maxCost ] );
                    $('.minCost').text('$0');
                    $('.maxCost').text('$' + maxCost);
                }

                $(_element).parent().remove();
                if(typeof _callback == 'function')
                    _callback();
                else
                    getNewElem();
            }

            function deleteAllFilters(){
                var div = $('.filterInPc');
                if(div.length == 1)
                    deleteThisFilter($(div[0]).children()[0]);
                else
                    deleteThisFilter($(div[0]).children()[0], deleteAllFilters);
            }


            function getListElem(){
                if(listSample == 0) {
                    listSample = $('#listSample').html();
                    $('#listSample').html('');
                    $('#listSample').css('display', 'flex');
                }
                createFilters();

                if(!isLoading && !isFinish){
                    openLoading();
                    isLoading = true;
                    $.ajax({
                        type: 'post',
                        url: '{{route("getListElems")}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            destinationId: destinationId,
                            activityId: activityId,
                            season: season,
                            page: page,
                            perPage: perPage,
                            kind: kind,
                            sort: sort,
                            cost: cost,
                            tag: tag,
                        },
                        success: function(response){
                            isLoading = false;
                            try{
                                response = JSON.parse(response);
                                if(response['status'] == 'ok'){
                                    page++;
                                    createListElems(response['result']);
                                }
                                else{
                                    closeLoading();
                                }
                            }
                            catch (e) {
                                console.log(e);
                                closeLoading();
                            }
                        },
                        error: function(error){
                            console.log(error);
                            closeLoading();
                            isLoading = false;
                        }
                    })
                }
            }

            function createListElems(elems){
                if(elems.length != perPage)
                    isFinish = true;

                for(var i = 0; i < elems.length; i++){
                    if(elems[i]['bad'] == false) {
                        var text = listSample;
                        var fk = Object.keys(elems[i]);
                        for (var x of fk) {
                            var t = '##' + x + '##';
                            var re = new RegExp(t, "g");
                            text = text.replace(re, elems[i][x]);
                        }

                        $('#listSample').append(text);
                    }
                }

                resizeImg('imgList');
                closeLoading();
                isLoading = false;
            }

            getListElem();

            var loo = false;
            $(window).on('scroll', function(e){
                var nowHeight = $('#scroleBase').offset().top + $('#scroleBase').height();
                var nowView = $(window).scrollTop() + $(window).height();
                if(nowView > nowHeight+30 && !loo){
                    getListElem();
                }
            });
        @endif
    </script>
@endsection
