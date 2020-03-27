<link rel="stylesheet" href="{{asset('css/header.css')}}">

<style>
    @if( Request::is('/'))
        .logoNavDiv{
            margin-right: auto;
        }
    @endif

    .navSubListAllCountries{
        position: absolute;
        width: 100%;
        height: 0;
        background: white;
        bottom: 0px;
        transition: .2s;
        overflow: hidden;
        display: flex;
        align-items: center;
        flex-direction: column;

    }
    .navSubListAllCountriesClose{
        width: 100%;
        height: 50px;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .closeCountryArrow{
        border: solid #909090;
        border-width: 0 5px 5px 0;
        padding: 10px;
    }
    .navSubListAllCountriesList{
        display: flex;
        justify-content: left;
        align-items: center;
        flex-wrap: wrap;
        margin-top: 20px;
        max-width: 1500px;
    }
    .navCountries{
        width: 125px;
        height: 50px;
        display: flex;
        justify-content: left;
        align-items: center;
        text-align: left;
        color: #2c3e50 !important;
        margin: 5px 10px;
        padding: 5px 5px;
    }
    .navCountries:hover{
        background-color: #1f75b9;
        color: white !important;
    }
</style>

<div id="backBlackSideNav" class="backBlack" style="display: none">
    <div id="mySidenav" class="sidenav">
        <div class="sideHeader">

        </div>
        <div class="sideBody">
            <div id="backSideNavMenuDiv" class="sideNavTabs" style="margin-bottom: 20px; display: none">
                <a href="#" onclick="backSideNavMenu()" style="justify-content: center">
                    <div class="arrow left" style="position: absolute; left: 15px;"></div>
                    <div id="backSideNavMenuName"></div>
                </a>
            </div>

            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    Destinations
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    @foreach($continents as $item)
                        <div class="sideNavTabs">
                            <a href="#" onclick="showSubSideNavMenu(this)">
                                {{$item->name}}
                                <div class="arrow right"></div>
                            </a>

                            <div class="subSideNavMenu subSubSideNavMenu">
                                <div class="sideNavTabs">
                                    <a class="subSideNavTab" href="#">
                                        See all
                                    </a>
                                </div>
                                @for($i = 0; $i < count($item->countries); $i++)
                                    <div class="sideNavTabs">
                                        <a class="subSideNavTab" href="#">
                                            {{$item->countries[$i]->name}}
                                        </a>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    Activities
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            See all Travel
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Adventure Travel
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Hiking & Trekking Travel
                            <div class="arrow right"></div>
                        </a>
                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    See all1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Adventure1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Hiking & Trekking1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Wildlife1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Safari1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    See all1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Adventure1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Hiking & Trekking1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Wildlife1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Safari1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    See all1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Adventure1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Hiking & Trekking1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Wildlife1
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Safari1
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Wildlife Travel
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Safari Travel
                        </a>
                    </div>
                </div>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Fest & Events
                    <div class="arrow right"></div>
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Pre-Trips
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Community
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    About us
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Contact us
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Journal
                </a>
            </div>
        </div>
    </div>
</div>

<nav>
    <div class="container navContainer">
        <div class="pcHide threeLineDiv" onclick="openNav()">
            <div class="navThreeLine"></div>
            <div class="navThreeLine"></div>
            <div class="navThreeLine"></div>
        </div>
        <div class="logoNavDiv">
            <a href="{{url('/')}}">
                <img src="{{asset('images/mainImage/dplIcon.jpg')}}" alt="DPL" style="width: 100%">
            </a>
        </div>

        @if( !Request::is('/'))
            <div class="navSearchBar mobileHide">
                <div class="navSearchIcon">
                    <img src="{{asset('images/mainImage/searchIcon.svg')}}" style="width: 100%;">
                </div>
                <input type="text" class="searchNavInput" placeholder="Where do you want to go?" onfocus="$('.searchBackBlack').show(); inSearch = true;" onfocusout="$('.searchBackBlack').hide(); inSearch = false; clearResult(); $(this).val('')" onkeydown="search(this.value)">
                <div class="searchResult">
                    <div class="headerSearch">
                        TOUR OPERATORS
                    </div>
                    <div class="resultsOFSearch"> Iranian Tour</div>
                    <div class="resultsOFSearch"> Iran Welcomes You</div>
                    <div class="resultsOFSearch"> Top Iran Tour</div>

                    <div class="headerSearch">PLACES</div>
                    <div class="resultsOFSearch"> Middle East</div>
                    <div class="resultsOFSearch"> Isfahan</div>
                    <div class="resultsOFSearch"> Shiraz</div>

                    <div class="headerSearch">TOURS</div>
                    <div class="resultsOFSearch"> From Turky To India</div>
                    <div class="resultsOFSearch"> Grand Asia Caravan</div>
                    <div class="resultsOFSearch">  Grand Asia Caravan Extended Journy</div>
                    <div class="resultsOFSearch">  See All Result For "iran"</div>
                </div>
            </div>
        @endif

        <ul class="navUl">
            <li class="navLi mobileHide" onmouseleave="$('.navSubListAllCountries').css('height', '0px')">
                <div class="navTabName">
                    Destinations
                </div>
                <div class="subList subLisL">
                    <div class="navListSub">
                        @foreach($continents as $item)
                            <div class="navSubListRow">
                                <div class="navSubListHeader">{{$item->name}}</div>
                                @for($i = 0; $i < count($item->countries) && $i < 6; $i++)
                                    <div class="navSubListBody">{{$item->countries[$i]->name}}</div>
                                @endfor
                                @if(count($item->countries) > 6)
                                    <div class="navSubListFooter" onclick="openAllCountryHeader(this)">See all</div>
                                @endif
                            </div>
                            <div class="navSubListAllCountries">
                                <div class="navSubListAllCountriesClose" onclick="closeAllCountry(this)">
                                    <div class="arrow down closeCountryArrow"></div>
                                </div>
                                <div class="navSubListAllCountriesList">
                                    @foreach($item->countries as $countri)
                                        <a class="navCountries">
                                            {{$countri->name}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>
            <li class="navLi mobileHide">
                <div class="navTabName">
                    Activities
                </div>
                <div class="subList subLisL">

                </div>
            </li>
            <li class="navLi posRel mobileHide">
                <div class="navTabName">
                    Fest&Event
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                    </div>
                </div>
            </li>
            <li class="navLi posRel mobileHide">
                <div class="navTabName">
                    Pre-Trips
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                    </div>
                </div>
            </li>
            <li class="navLi posRel mobileHide">
                <div class="navTabName">
                    About us
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Community</div>
                        <div class="navSubListBody navSubListBodyM">Contact us</div>
                    </div>
                </div>
            </li>
            <li class="navLi posRel mobileHide">
                <div class="navTabName">
                    Journal
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                    </div>
                </div>
            </li>

{{--            <li class="navLi posRel mobileHide">--}}
{{--                <div class="navTabName">--}}
{{--                    <div class="navPerson">--}}
{{--                        <i class="far fa-user" aria-hidden="true"></i>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="subList subLisM">--}}
{{--                    <div class="navSubListRow">--}}
{{--                        <div class="navSubListBody navSubListBodyM">Namibia</div>--}}
{{--                        <div class="navSubListBody navSubListBodyM">Namibia</div>--}}
{{--                        <div class="navSubListBody navSubListBodyM">Namibia</div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="navLi">--}}
                <div class="searchNavIconInFlag pcHide" onclick="showNavSearchMobile()">
                    <i class="fas fa-search" style="color: #459ed1"></i>
                </div>
{{--                <div class="telPcLi">--}}
{{--                    <div class="navFlag"></div>--}}
{{--                    <div class="navPhone">--}}
{{--                        +989122474393--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="telMobileLi">--}}
{{--                    <i class="fas fa-phone phoneIconHeader"></i>--}}
{{--                </div>--}}
{{--            </li>--}}
        </ul>

        <div id="searchNavMobile" class="searchNavMobile pcHide">
            <div style="width: 100%; display: flex; height: 100%;">
                <div class="mobileNavSearchDiv">
                    <input type="text" class="mobileNavSearchInput" placeholder="Where do you want to go?" onkeyup="search(this.value)">
                    <div class="searchResult">

                        <div class="headerSearch">
                            TOUR OPERATORS
                        </div>
                        <div class="resultsOFSearch"> Iranian Tour</div>
                        <div class="resultsOFSearch"> Iran Welcomes You</div>
                        <div class="resultsOFSearch"> Top Iran Tour</div>

                        <div class="headerSearch">PLACES</div>
                        <div class="resultsOFSearch"> Middle East</div>
                        <div class="resultsOFSearch"> Isfahan</div>
                        <div class="resultsOFSearch"> Shiraz</div>

                        <div class="headerSearch">TOURS</div>
                        <div class="resultsOFSearch"> From Turky To India</div>
                        <div class="resultsOFSearch"> Grand Asia Caravan</div>
                        <div class="resultsOFSearch">  Grand Asia Caravan Extended Journy</div>
                        <div class="resultsOFSearch">  See All Result For "iran"</div>

                    </div>
                </div>
                <div class="closeIcon" onclick="closeNavSearchMobile()">
                    <i class="fas fa-times" style="color: #1f75b9"></i>
                </div>
            </div>
        </div>

        <div class="backBlack searchBackBlack"></div>

    </div>
</nav>

@if(isset($guidance))
    <div class="guideNav">
        <div class="container" >
            <div class="row" style="width: 100%; margin: 0;">
                <div>
                    <a href="{{url('/')}}" class="topMenuGuide">Home</a>
                    <span class="topMenuGuide"> > </span>
                    <a href="#" class="topMenuGuide">{{$guidance['continents']}}</a>
                    @if(isset($guidance['country']))
                        <a href="#" class="topMenuGuide"> > </a>
                        <a href="#" class="topMenuGuide">{{$guidance['country']}}</a>
                        @if(isset($guidance['city']))
                            <a href="#" class="topMenuGuide"> > </a>
                            <a href="#" class="topMenuGuide">{{$guidance['city']}}</a>
                            @if(isset($guidance['destination']))
                                <a href="#" class="topMenuGuide"> > </a>
                                <a href="{{route('show.destination', ['country' => $guidance['country'], 'slug' => $guidance['destinationSlug']])}}" class="topMenuGuide">{{$guidance['destination']}}</a>
                                @if(isset($guidance['package']))
                                    <a href="#" class="topMenuGuide"> > </a>
                                    <a href="#" class="topMenuGuide">{{$guidance['package']}}</a>
                                @endif
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    function openAllCountryHeader(_element) {
        $(_element).parent().next().css('height', '100%');
    }
    function closeAllCountry(_element){
        $(_element).parent().css('height', '0')
    }

</script>

<script src="{{asset('js/headerJs.js')}}"></script>
