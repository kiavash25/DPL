@extends('layouts.base')

@section('head')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .container{
            max-width: 1164px !important;
        }
        .mainHeader{
            margin-bottom: 10px;
            border-bottom: 1px solid #c7d0d9;
        }
        .mainHeaderH1{
            font-weight: bold;
        }
        .listSectionDiv{
            box-shadow: 0 0 12px rgba(0,0,0,.15);
            width: 100%;
            border-radius: 5px;
            margin: 8px 0px;
            display: flex;
            position: relative;
            padding: 0;
        }
        .picSection{
            overflow: hidden;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px 0px 0px 5px;
            height: 200px;
        }
        .imgList{
            height: 100%;
        }
        .contentSection{
            padding: 15px;
        }
        .infoSection{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .headerContentSection{
            font-size: 30px;
            overflow: hidden;
        }
        .textContentSection{
            overflow: hidden;
            height: 120px;
            font-size: 10px;
            text-align: justify;
        }
        .showButton{
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            background: #f39a2d;
            margin: 0px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white !important;
            transition: .5s;
            border-radius: 5px;
        }
        .showButton:hover{
            background: #b26d22;
        }
        .infoSectionPackage{
            align-items: normal;
            flex-direction: column;
        }
        .packageInfoDiv{
            position: relative;
            margin-bottom: 20px;
        }
        .packageInfoSec{
            margin: 5px 0px;
        }
        .packageInfoName{
            color: #818d99;
            font-size: 12px;
        }
        .packageInfoValue{
            font-size: 14px;
        }
        .seDateInfo{
            display: flex;
            justify-content: space-between;
        }
        .seDateInfoName{

        }
        .seDateInfoValue{
            font-size: 12px;
        }
        .costInfoDiv{
            display: flex;
            justify-content: flex-end;
            margin: 7px 0px;
            position: absolute;
            right: 0;
            bottom: -25px;

        }
        .constName{
            display: flex;
            align-items: flex-end;
            color: #818d99;
            font-size: 14px;
        }
        .constValue{
            font-size: 20px;
            color: #41c4ab;
        }

        .packageSDateCircle{
            position: absolute;
            top: -10px;
            right: -20px;
            background-color: #1f75b9;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 45px;
            height: 45px;
            text-align: center;
            border-radius: 50%;
            padding: 30px;
            font-weight: bold;
        }
        .pacakgeMoreInfo{
            display: none;
            width: 100%;
            text-align: center;
            justify-content: center;
            align-items: center;
            color: #1f75b9;
            font-size: 12px;
            margin-top: 5px;
            border-top: solid 1px gray;
            padding-top: 10px;
        }
        @media (max-width: 767px) {
            .listSectionDiv{
                width: 48%;
                margin: 1%;
                padding-bottom: 15px;
            }
            .picSection{
                border-radius: 5px 5px 0px 0px;
            }
            .packageSDateCircle{
                right: 5px;
                top: 5px;
            }
        }
        @media (max-width: 624px){
            .listSectionDiv{
                width: 100%;
                max-width: 300px;
            }
            .packageInfoDiv{
                display: none;
            }
            .pacakgeMoreInfo{
                display: flex;
            }
        }
    </style>

    <style>
        .sortAndFilterButtonDiv{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
            /*padding: 10px;*/
        }
        .sortAndFilterButton{
            width: 100%;
            display: flex;
            justify-content: left;
            align-items: center;
            background-color: #0a7bbd;
            color: white;
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 5px;
            position: relative;
            flex-direction: column;
        }
        .clearAllButtonPc{
            position: absolute;
            right: 10px;
            font-size: 11px;
            top: 10px;
            background: white;
            padding: 4px;
            border-radius: 7px;
            color: #1f75b9;
            cursor: pointer;
        }
        .filtersInRowPc{
            width: 100%;
            padding-left: 10px;
        }
        .filterInPc{
            color: white;
            font-size: 11px;
            font-weight: 100;
        }
        .nmbFilterInPc{
            color: white;
            font-size: 15px;
            font-weight: 100;
            padding: 0px 0px;
            margin: 8px 0px;
        }
        .filtersInPcSection{
            border: 1px solid #c7d0d9;
            margin-top: 15px;
            border-radius: 5px;
        }
        .filterSection{
            width: 100%;
            position: relative;
        }
        .sortIcon{
            position: absolute;
            top: 30%;
            left: 3%;
            color: gray;
            font-size: 20px;
        }
        .filterSelect{
            width: 100%;
            border: none;
            border-radius: 5px;
            padding: 8px;
        }
        .dropdownFilter{
            padding: 12px;
            padding-left: 25px;
            border-bottom: 1px solid #c7d0d9;
        }
        .filterName{
            font-size: 17px;
            font-weight: bold;
        }
        .filterBody{
            overflow: hidden;
            display: none;
            width: 100%;
            margin-top: 10px;
        }
        .containerCheckBox{
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            margin-left: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .containerCheckBox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
            border-radius: 5px;
        }
        .containerCheckBox:hover input ~ .checkmark {
            background-color: #ccc;
        }
        .containerCheckBox input:checked ~ .checkmark {
            background-color: #0a7bbd;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .containerCheckBox input:checked ~ .checkmark:after {
            display: block;
        }
        .containerCheckBox .checkmark:after {
            left: 10px;
            top: 6px;
            width: 7px;
            height: 12px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .ui-widget.ui-widget-content{
            background: lightgray;
            margin: 10px 15px;
        }
        .ui-widget-header{
            background: #0a7bbd;
            color: #333333;
            font-weight: bold;
        }
        .ui-slider-handle{
            border-radius: 50%;
            border: solid 2px #0a7bbd !important;
        }
        .ui-slider-horizontal .ui-slider-handle{
            top: -0.45em;
        }
        .ui-slider .ui-slider-handle{
            width: 25px;
            height: 25px;
        }



    </style>
@endsection

@section('body')
    <div class="container" style="margin-top: 10px">
        <div class="col-md-12 mainHeader">
            <h2 class="mainHeaderH1">
                Iran Destinations
            </h2>
        </div>
        <div class="col-md-12" style="margin-top: 30px">

            <div class="row">
                <div class="col-lg-3">

                    <div class="row sortAndFilterButtonDiv">
                        <div class="sortAndFilterButton">
                            <span style="color: white; width: 100%">Sort & filter</span>
                            <div class="clearAllButtonPc">
                                Clear all
                            </div>
                            <div class="filtersInRowPc">
                                <div class="nmbFilterInPc">
                                    3 filter applied
                                </div>
                                <div class="filterInPc">
                                    <i class="fas fa-times" style="color: white; font-size: 17px; margin-right: 10px;"></i>
                                    Departs in Mar 2020
                                </div>
                                <div class="filterInPc">
                                    <i class="fas fa-times" style="color: white; font-size: 17px; margin-right: 10px;"></i>
                                    Departs in Mar 2020 hj vf
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row filtersInPcSection">
                        <div class="filterSection">
                            <i class="fa fa-sort sortIcon" aria-hidden="true"></i>
                            <select class="filterSelect" style="padding: 12px; padding-left: 25px">
                                <option value="1">kiavsah</option>
                                <option value="6">kiavsah2</option>
                                <option value="5">kiavsah3</option>
                                <option value="4">kiavsah4</option>
                                <option value="3">kiavsah5</option>
                                <option value="2">kiavsah6</option>
                            </select>
                        </div>
                    </div>

                    <div class="row filtersInPcSection">
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                Cost
                                <div class="arrow down" style="float: right; margin-right: 5px; border-color: gray"></div>
                                <div class="arrow up" style="float: right; margin-right: 5px; border-color: gray; margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <label class="containerCheckBox">Four
                                    <input type="checkbox">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                Season
                                <div class="arrow down" style="float: right; margin-right: 5px; border-color: gray"></div>
                                <div class="arrow up" style="float: right; margin-right: 5px; border-color: gray; margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <select class="form-control filterSelect">
                                    <option value="1">kiavsah</option>
                                    <option value="6">kiavsah2</option>
                                    <option value="5">kiavsah3</option>
                                    <option value="4">kiavsah4</option>
                                    <option value="3">kiavsah5</option>
                                    <option value="2">kiavsah6</option>
                                </select>
                            </div>
                        </div>
                        <div class="filterSection dropdownFilter">
                            <div class="filterName" onclick="openFilterDropDown(this)">
                                Start Date
                                <div class="arrow down" style="float: right; margin-right: 5px; border-color: gray"></div>
                                <div class="arrow up" style="float: right; margin-right: 5px; border-color: gray; margin-top: 10px; display: none"></div>
                            </div>
                            <div class="filterBody">
                                <div>
                                    <div >100</div>
                                    <div >300</div>
                                </div>
                                <div class="slider-range"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-9" style="display: flex; flex-wrap: wrap; justify-content: center">

                    @if($kind == 'destination')
                        @for($i = 0; $i < 4; $i++)
                            <div class="row listSectionDiv">
                                <div class="col-md-4 col-sm-12 picSection">
                                    <img src="{{asset('uploaded/destination/5/1584731501colosseum1.jpg')}}" class="imgList" >
                                </div>
                                <div class="col-md-5 col-sm-12 contentSection">
                                    <div class="headerContentSection">
                                        Colosseum
                                    </div>
                                    <div class="textContentSection">
                                        Damavand is a significant mountain in Persian mythology.
                                        It is the symbol of Iranian resistance against despotism
                                        and foreign rule in Persian poetry and literature.
                                        In Zoroastrian texts and mythology, the three-headed d
                                        ragon Aži Dahāka was chained within Mount Damāvand,
                                        there to remain until the end of the world. In a later
                                        version of the same legend, the tyrant Zahhāk was also
                                        chained in a cave somewhere in Mount Damāvand after being
                                        defeated by Kāveh and Fereydūn. Persian poet Ferdowsi
                                        depicts this event in his masterpiece, the Shahnameh.2
                                        version of the same legend, the tyrant Zahhāk was also
                                        chained in a cave somewhere in Mount Damāvand after being
                                        defeated by Kāveh and Fereydūn. Persian poet Ferdowsi
                                        depicts this event in his masterpiece, the Shahnameh.2
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 infoSection">
                                    <a class="showButton">
                                        View Destination
                                    </a>
                                </div>
                            </div>
                        @endfor
                    @else
                        @for($i = 0; $i < 4; $i++)
                            <div class="row listSectionDiv">
                                <div class="col-md-4 col-sm-12 picSection">
                                    <img src="{{asset('uploaded/destination/5/1584731501colosseum1.jpg')}}" class="imgList" >
                                </div>
                                <div class="col-md-5 col-sm-12 contentSection">
                                    <div class="headerContentSection">
                                        Colosseum2
                                    </div>
                                    <div class="textContentSection">
                                        Damavand is a significant mountain in Persian mythology.
                                        It is the symbol of Iranian resistance against despotism
                                        and foreign rule in Persian poetry and literature.
                                        In Zoroastrian texts and mythology, the three-headed d
                                        ragon Aži Dahāka was chained within Mount Damāvand,
                                        there to remain until the end of the world. In a later
                                        version of the same legend, the tyrant Zahhāk was also
                                        chained in a cave somewhere in Mount Damāvand after being
                                        defeated by Kāveh and Fereydūn. Persian poet Ferdowsi
                                        depicts this event in his masterpiece, the Shahnameh.2
                                        version of the same legend, the tyrant Zahhāk was also
                                        chained in a cave somewhere in Mount Damāvand after being
                                        defeated by Kāveh and Fereydūn. Persian poet Ferdowsi
                                        depicts this event in his masterpiece, the Shahnameh.2
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 infoSection infoSectionPackage">
                                    <div class="packageInfoDiv">
                                        <div class="packageInfoSec">
                                            <div class="packageInfoName">Season: </div>
                                            <div class="packageInfoValue">winter</div>
                                        </div>
                                        <div class="packageInfoSec seDateInfo">
                                            <div>
                                                <div class="packageInfoName seDateInfoName">Start: </div>
                                                <div class="packageInfoValue seDateInfoValue">2022/12/29</div>
                                            </div>
                                            <div>
                                                <div class="packageInfoName seDateInfoName">End: </div>
                                                <div class="packageInfoValue seDateInfoValue">2023/01/29</div>
                                            </div>
                                        </div>
                                        <div class="packageInfoSec">
                                            <div class="packageInfoName">Days: </div>
                                            <div class="packageInfoValue">25</div>
                                        </div>
                                        <div class="packageInfoSec costInfoDiv">
                                            <div class="constName">us</div>
                                            <div class="constValue">$2,015</div>
                                        </div>
                                    </div>
                                    <a class="showButton">
                                        View Package
                                    </a>
                                    <div class="pacakgeMoreInfo" onclick="showMoreInfo(this)">
                                        More Information
                                    </div>
                                </div>

                                <div class="packageSDateCircle">
                                    23 APR
                                </div>
                            </div>
                        @endfor
                    @endif


                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $( function() {
            $( ".slider-range" ).slider({
                range: true,
                min: 0,
                max: 500,
                values: [ 75, 300 ],
                slide: function( event, ui ) {
                    $($(this).prev().children()[0]).text(ui.values[ 0 ]);
                    $($(this).prev().children()[1]).text(ui.values[ 1 ]);
                }
            });
        } );

        resizeImg('imgList');
        $(window).resize(function(){
            resizeImg('imgList');
        });

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
    </script>
@endsection
