<link rel="stylesheet" href="{{asset('css/header.css')}}">

<style>
    @if( Request::is('/'))
        .logoNavDiv{
            margin-right: auto;
        }
    @endif

</style>

<div id="backBlackSideNav" class="backBlack" style="display: none">
    <div id="mySidenav" class="sidenav">
        <div class="sideHeader">

        </div>
        <div class="sideBody">
            <div id="backSideNavMenuDiv" class="sideNavTabs" style="margin-bottom: 20px; display: none;">
                <a href="#" onclick="backSideNavMenu()" style="justify-content: center">
                    <div class="arrow left" style="position: absolute; left: 15px;"></div>
                    <div id="backSideNavMenuName"></div>
                </a>

                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            See all
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Adventure
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Hiking & Trekking
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Wildlife
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Safari
                        </a>
                    </div>
                </div>
            </div>

            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    Destinations
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            See all
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Adventure
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Hiking & Trekking
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Wildlife
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Safari
                        </a>
                    </div>
                </div>
            </div>
            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    Travel Styles
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
                    Deals
                    <div class="arrow right"></div>
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Why TourRadar
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Win a Tour
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Tour the World - Follow Now!
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
            <a href="#">
                <img src="{{asset('images/mainImage/logo.png')}}" alt="DPL" style="width: 100%">
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
            <li class="navLi mobileHide">
                <div class="navTabName">
                    Destinations
                </div>
                <div class="subList subLisL">
                    <div class="navListSub">
                        <div class="navSubListRow">
                            <div class="navSubListHeader">Africa</div>
                            <div class="navSubListBody">South Africa</div>
                            <div class="navSubListBody">Morocco</div>
                            <div class="navSubListBody">Namibia</div>
                            <div class="navSubListBody">Kenya</div>
                            <div class="navSubListBody">Madagascar</div>
                            <div class="navSubListBody">Tanzania</div>
                            <div class="navSubListFooter">See all</div>
                        </div>
                        <div class="navSubListRow">
                            <div class="navSubListHeader">Asia</div>
                            <div class="navSubListBody">Thailand</div>
                            <div class="navSubListBody">Japan</div>
                            <div class="navSubListBody">Vietnam</div>
                            <div class="navSubListBody">Indonesia</div>
                            <div class="navSubListBody">Nepal</div>
                            <div class="navSubListBody">Cambodia</div>
                            <div class="navSubListFooter">See all</div>
                        </div>
                        <div class="navSubListRow">
                            <div class="navSubListHeader">Australia & NZ</div>
                            <div class="navSubListBody">New Zealand</div>
                            <div class="navSubListBody">Australia</div>
                            <div class="navSubListBody">Fiji</div>
                            <div class="navSubListBody">Papua New Guinea</div>
                            <div class="navSubListFooter">See all</div>
                        </div>
                        <div class="navSubListRow">
                            <div class="navSubListHeader">Europe</div>
                            <div class="navSubListBody">Iceland</div>
                            <div class="navSubListBody">Spain</div>
                            <div class="navSubListBody">Scotland</div>
                            <div class="navSubListBody">Ireland</div>
                            <div class="navSubListBody">Croatia</div>
                            <div class="navSubListBody">Greece</div>
                            <div class="navSubListFooter">See all</div>
                        </div>
                        <div class="navSubListRow">
                            <div class="navSubListHeader">North America</div>
                            <div class="navSubListBody">USA</div>
                            <div class="navSubListBody">Canada</div>
                            <div class="navSubListBody">Greenland</div>
                        </div>
                        <div class="navSubListRow">
                            <div class="navSubListHeader">Latin America</div>
                            <div class="navSubListBody">Peru</div>
                            <div class="navSubListBody">Cuba</div>
                            <div class="navSubListBody">Mexico</div>
                            <div class="navSubListBody">Guatemala</div>
                            <div class="navSubListBody">Argentina</div>
                            <div class="navSubListBody">Costa Rica</div>
                            <div class="navSubListFooter">See all</div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="navLi mobileHide">
                <div class="navTabName">
                    Travel Styles
                </div>
                <div class="subList subLisL">

                </div>
            </li>
            <li class="navLi posRel mobileHide">
                <div class="navTabName">
                    Deals
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
                    Help
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
                    <div class="navPerson">
                        <i class="far fa-user" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                        <div class="navSubListBody navSubListBodyM">Namibia</div>
                    </div>
                </div>
            </li>
            <li class="navLi" style="padding: 0px 10px">
                <div class="searchNavIconInFlag pcHide" onclick="showNavSearchMobile()">
                    <i class="fas fa-search"></i>
                </div>
                <div class="telPcLi">
                    <div class="navFlag"></div>
                    <div class="navPhone">
                        +989122474393
                    </div>
                </div>
                <div class="telMobileLi">
                    <i class="fas fa-phone phoneIconHeader"></i>
                </div>
            </li>
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
                    <i class="fas fa-times"></i>
                </div>
            </div>
        </div>

        <div class="backBlack searchBackBlack"></div>

    </div>
</nav>

<script src="{{asset('js/headerJs.js')}}"></script>
