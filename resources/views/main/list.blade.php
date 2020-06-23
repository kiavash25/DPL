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
            direction: ltr;
            display: flex;
            justify-content: space-between;
        }
        .maxCostName{
            font-size: 13px;
            font-weight: bold;
            display: flex;
            flex-direction: column;
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
        .sideArrow{
            float: right;
            margin-right: 5px;
            border-color: gray
        }
        .cancelFilter{
            color: white;
            font-size: 17px;
            margin-right: 10px;
        }
        .listHeader{
            line-height: 37px;
            vertical-align: middle;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 25px;
        }
    </style>
    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>
    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlList.css')}}">
    @endif
@endsection

@section('body')

    <div class="container" style="margin-top: 10px">
        <div class="col-md-12 mainHeader">
            <h2 class="mainHeaderH1">
                {{__($title)}}
            </h2>
        </div>
        <div class="col-md-12" style="margin-top: 30px">

            <div class="row">
                @if($kind != 'destination')
                    <div class="col-lg-3" style="margin-bottom: 40px">

                    <div class="row sortAndFilterButtonDiv">
                        <div class="sortAndFilterButton">
                            <span style="color: white; width: 100%">{{__('Sort & filter')}}</span>
                            <div class="clearAllButtonPc" onclick="deleteAllFilters()">
                                {{__('Clear all')}}
                            </div>
                            <div class="filtersInRowPc">
                                <div class="nmbFilterInPc">
                                    <span class="numberOfFilters" style="color: white"></span> {{__('filter applied')}}
                                </div>
                                <div class="filtersShow"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row filtersInPcSection">
                        <div class="filterSection">
                            <i class="fa fa-sort sortIcon" aria-hidden="true"></i>
                            <select class="filterSelect" onchange="changeSortList(this.value)">
                                <option value="nearestDate">{{__('Nearest date')}}</option>
                                <option value="minConst">{{__('Total Price: Lower First')}}</option>
                                <option value="maxConst">{{__('Total Price: Highest First')}}</option>
                                <option value="minDay">{{__('Duration: Shortest first')}}</option>
                                <option value="maxDay">{{{__('Duration: Longest first')}}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="row filtersInPcSection">
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                {{__('Activity')}}
                                <div class="arrow down sideArrow"></div>
                                <div class="arrow up sideArrow" style="margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                @foreach($activitiesList as $mainActivity)
                                    <hr>
                                    <h6 style="font-weight: bold">{{$mainActivity->name}}</h6>
                                    @foreach($mainActivity->subAct as $item)
                                        <label class="containerCheckBox activityFilter{{$item->id}}">{{$item->name}}
                                            <input type="checkbox" data_id="{{$item->id}}" onchange="chaneActivityId(this)" {{in_array($item->id, $activity) ? 'checked' : ''}}>
                                            <span class="checkmark"></span>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                {{__('Season')}}
                                <div class="arrow down sideArrow"></div>
                                <div class="arrow up sideArrow" style="margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <div class="seasonFilter seasonFilterspring {{isset($season) && $season != null && $season == 'spring' ? 'choosenSeasonFilter' : ''}}" onclick="seasonFilter(this)" data_value="spring">{{__('Spring')}}</div>
                                <div class="seasonFilter seasonFiltersummer {{isset($season) && $season != null && $season == 'summer' ? 'choosenSeasonFilter' : ''}}" onclick="seasonFilter(this)" data_value="summer">{{__('Summer')}}</div>
                                <div class="seasonFilter seasonFilterfall {{isset($season) && $season != null && $season == 'fall' ? 'choosenSeasonFilter' : ''}}" onclick="seasonFilter(this)" data_value="fall">{{__('Fall')}}</div>
                                <div class="seasonFilter seasonFilterwinter {{isset($season) && $season != null && $season == 'winter' ? 'choosenSeasonFilter' : ''}}" onclick="seasonFilter(this)" data_value="winter">{{__('Winter')}}</div>
                            </div>
                        </div>
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                {{__('Cost')}}
                                <div class="arrow down sideArrow"></div>
                                <div class="arrow up sideArrow" style="margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <div class="costFilter">
                                    <div class="maxCostName">{{__('min')}}:
                                        <span class="minCost">0</span>
                                    </div>
                                    <div class="maxCostName">{{__('max')}}:
                                        <span class="maxCost">{{$maxCost}}</span>
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
                                        <img src="##imgUrl##" class="imgList" onload="resizeThisImg(this)">
                                    </div>
                                    <div class="col-md-5 col-sm-12 contentSection">
                                        <div class="headerContentSection">
                                            <div style="color: #818d99; font-size: 12px; display: flex" >
                                                <span style=" display: ##day_show##">
                                                    ##day##
                                                    {{__('Days')}}
                                                    {{__('in')}}
                                                </span>
                                                <span>
                                                    <a href="##destinationUrl##" style="color: #818d99; font-size: 12px" target="_blank">
                                                    ##destinationName##
                                                </a>
                                                </span>
                                            </div>
                                            <div class="listHeader">
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
                                                <div class="packageInfoName">{{__('Activity')}}: </div>
                                                <div class="packageInfoValue">##activity##</div>
                                            </div>
                                            <div class="packageInfoSec seDateInfo">
                                                <div style=" display: ##sDate_show##">
                                                    <div class="packageInfoName seDateInfoName">{{__('Start')}}: </div>
                                                    <div class="packageInfoValue seDateInfoValue">##sDate##</div>
                                                </div>
                                                <div style=" display: ##eDate_show##">
                                                    <div class="packageInfoName seDateInfoName">{{__('End')}}: </div>
                                                    <div class="packageInfoValue seDateInfoValue">##eDate##</div>
                                                </div>
                                            </div>
                                            <div class="packageInfoSec seDateInfo">
                                                <div>
                                                    <div class="packageInfoName seDateInfoName">{{__('Season')}}: </div>
                                                    <div class="packageInfoValue seDateInfoValue">##season##</div>
                                                </div>
                                            </div>
                                            {{--                                        <div class="packageInfoSec">--}}
                                            {{--                                            <div class="packageInfoName">Days: </div>--}}
                                            {{--                                            <div class="packageInfoValue">##day##</div>--}}
                                            {{--                                        </div>--}}
                                            <div class="packageInfoSec costInfoDiv">
                                                <div class="constName">
                                                    {{isset($showLang->currencyName) && $showLang->currencyName != null ? $showLang->currencyName : 'Euro'}}
                                                </div>
                                                <div class="constValue">
                                                    {{isset($showLang) ? $showLang->currencySymbol : '€'}}
                                                    ##money##
                                                </div>
                                            </div>
                                        </div>
                                        <a href="##url##" class="showButton">
                                            {{__('View Package')}}
                                        </a>
                                        <div class="pacakgeMoreInfo" onclick="showMoreInfo(this)">
                                            {{__('More Information')}}
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
                                                {{__('Packages')}}: {{$item->package}}
                                            </div>

                                            <a href="{{route('show.destination', ['slug' => $item->slug])}}" class="showButton">
                                                {{__('View Destination')}}
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
        let currencySymbol = '{{isset($showLang) ? $showLang->currencySymbol : '€'}}';
        let currencyName = '{{isset($showLang->currencyName) && $showLang->currencyName != null ? $showLang->currencyName : 'Euro'}}';

        var maxCost = {{$maxCost}};

        $( function() {
            $( ".slider-range" ).slider({
                range: true,
                min: 0,
                step: 100,
                max: {{$maxCost}},
                values: [ 0, maxCost ],
                slide: function( event, ui ) {
                    $('.minCost').text(currencyName + threeDotMoney(ui.values[0]));
                    $('.maxCost').text(currencyName + threeDotMoney(ui.values[1]));
                    @if(app()->getLocale() == 'fa')
                        $('.minCost').persiaNumber('fa');
                        $('.maxCost').persiaNumber('fa');
                    @endif
                },
                stop: function(event, ui){
                    cost = [ui.values[0], ui.values[1]];
                    getNewElem();
                }
            });
        } );

        function threeDotMoney(_money){
            _money += '';
            let money = '';
            let j = 0;
            for (let i = _money.length-1; i >= 0 ; i--){
                if(j % 3 == 0 && j != 0)
                    money = ',' + money;
                money = _money[i] + money;
                j++;
            }
            return money;
        }

        resizeImg('imgList');
        $(window).resize(function(){
            resizeImg('imgList');
        });

        @if($kind != 'destination')
            openLoadingSample();

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
            var activityId = JSON.parse('{{json_encode($activity)}}');
            var season = '{{$season}}';
            var sort = 'nearestDate';
            var isFinish = false;
            var isLoading = false;
            var listSample = 0;
            var cost = [0, maxCost];
            var tag = '{{$tag}}';
            let seasonLang = {
                'spring': "{{__('Spring')}}",
                'summer': "{{__('Summer')}}",
                'fall': "{{__('Fall')}}",
                'winter': "{{__('Winter')}}",
            }

            if(activityId == 'all')
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
                id = parseInt($(_element).attr('data_id'));
                if($(_element).prop('checked')){
                    if(activityId.indexOf(id) < 0)
                        activityId[activityId.length] = id;
                }
                else
                    activityId[activityId.indexOf(id)] = 0;

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
                                '<i class="fas fa-times cancelFilter" onclick="deleteThisFilter(this)" data_type="activity" data_value="' + activityId[i] + '"></i>\n' +
                                '{{__('Activity')}} : ' +  name +
                                '</div>';
                        number++;
                    }
                }

                for(i = 0; i < season.length; i++){
                    if(season[i] != 0){
                        hasFilter = true;
                        text += '<div class="filterInPc">\n' +
                                '<i class="fas fa-times cancelFilter" onclick="deleteThisFilter(this)" data_type="season" data_value="' + season[i] + '"></i>\n' +
                            '{{__('Season')}} : ' +  seasonLang[season[i]] +
                                '</div>';
                        number++;
                    }
                }

                if(cost[0] != 0 || cost[1] != maxCost){
                    hasFilter = true;
                    text += '<div class="filterInPc">\n' +
                        '<i class="fas fa-times cancelFilter" onclick="deleteThisFilter(this)" data_type="cost" data_value="cost"></i>\n' +
                        ' {{__('Cost in')}} : ' + currencyName + ' ' + cost[0] + '- ' + currencyName + ' ' + cost[1] + ' \n' +
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
                    activityId[activityId.indexOf(parseInt(value))] = 0;
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
                    openLoadingSample();
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

                            if(elems[i][x] == null){
                                var t = '##' + x + '_show##';
                                var re = new RegExp(t, "g");
                                text = text.replace(re, 'none');
                            }
                            else{
                                var t = '##' + x + '_show##';
                                var re = new RegExp(t, "g");
                                text = text.replace(re, 'block');
                            }
                        }

                        $('#listSample').append(text);
                    }
                }

                resizeImg('imgList');
                closeLoading();
                isLoading = false;

                @if(app()->getLocale() == 'fa')
                    $('.constValue').persiaNumber('fa');
                    $('.filterInPc').persiaNumber('fa');
                @endif
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
