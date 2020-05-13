<link rel="stylesheet" href="{{asset('css/header.css')}}">

<style>
    @if( Request::is('/'))
        .logoNavDiv {
            margin-right: auto;
        }
    @endif

    .destTitles{
        width: 0%;
        overflow: hidden;
        display: flex;
        position: absolute;
        flex-direction: column;
        color: white;
        background: #1f75b9;
        left: 90%;
        top: 0px;
        align-items: center;
        justify-content: center;
        transition: .3s;
        border-radius: 10px;
        z-index: 1;
    }
    .navSubListBody:hover{
        border-radius: 10px;
    }
    .navSubListBody:hover .destTitles{
        width: 100%;
    }
    .destTitlesName{
        color: white;
        padding: 10px;
        text-align: center;
        width: 100%;
    }
    .destTitlesName:hover{
        color: white;
        background: #2b393a;
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
                    {{__('Destinations')}}
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    @foreach($destCategory as $item)
                        <div class="sideNavTabs">
                            <a href="#" onclick="showSubSideNavMenu(this)">
                                {{$item->name}}
                                <div class="arrow right"></div>
                            </a>

                            <div class="subSideNavMenu subSubSideNavMenu">
                                <div class="sideNavTabs">
                                    <a class="subSideNavTab"
                                       href="{{route('show.list', ['kind' => 'destination', 'value1' => $item->name ])}}">
                                        See all
                                    </a>
                                </div>
                                @for($i = 0; $i < count($item->destination); $i++)
                                    <div class="sideNavTabs">
                                        <a class="subSideNavTab"
                                           href="{{route('show.destination', ['slug' => $item->destination[$i]->slug])}}">
                                            {{$item->destination[$i]->name}}
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
                    {{__('Activities')}}
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    @foreach($activitiesList as $item)
                        <div class="sideNavTabs">
                            @if(isset($item->subAct) && count($item->subAct) > 0)
                                <a href="#" onclick="showSubSideNavMenu(this)">
                                    {{$item->name}}
                                    <div class="arrow right"></div>
                                </a>
                                <div class="subSideNavMenu subSubSideNavMenu">
                                    @for($i = 0; $i < count($item->subAct); $i++)
                                        <div class="sideNavTabs">
                                            <a class="subSideNavTab"
                                               href="{{route('show.activity', ['slug' => $item->subAct[$i]->slug])}}">
                                                {{$item->subAct[$i]->name}}
                                            </a>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                <a href="{{url('activity/'. $item->slug)}}" class="subSideNavTab">
                                    {{$item->name}}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    {{__('Fest & Events')}}
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Sport
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Bisotun
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Skimo
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Sky running
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Ice climbing
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Local events
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Regions
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Saffron
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Kurdish music
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Rose water
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Street music
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Cherry
                                </a>
                            </div>

                        </div>
                    </div>

                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Training
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Youth Camp
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Ski
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Grass Ski
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    {{__('Pre-trips')}}
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Accommodation
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Camps
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Huts
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Localhouses & eco-lodges
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Hostles
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Hotels
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Pre-trip checklist
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Travel extras
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Tutorals
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    General
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Good to know
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Destinations
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Activities
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    General
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="sideNavTabs">
                        <a href="#" onclick="showSubSideNavMenu(this)">
                            Book transfers
                            <div class="arrow right"></div>
                        </a>

                        <div class="subSideNavMenu subSubSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Flights
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Train
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Bus
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="#">
                                    Car rental
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="sideNavTabs">
                        <a href="#">
                            Guides and experts
                        </a>
                    </div>

                </div>
            </div>

            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    {{ __('Nature friend') }}
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Trekking Guide Course
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            Events
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="#">
                            News
                        </a>
                    </div>

                </div>

            </div>

            <div class="sideNavTabs">
                <a href="#" onclick="showSubSideNavMenu(this)">
                    {{ __('Community') }}
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a href="#" class="subSideNavTab">
                            Topical discussion
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a href="#" class="subSideNavTab">
                            GO-Live
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a href="#" class="subSideNavTab">
                            Your shots
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a href="#" class="subSideNavTab">
                            Our shots
                        </a>
                    </div>
                </div>
            </div>
{{--            <div class="sideNavTabs">--}}
{{--                <a href="{{route('aboutUs')}}">--}}
{{--                    {{ __('About us') }}--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="sideNavTabs">
                <a href="{{route('journal.index')}}">
                    {{ __('Journal') }}
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
            <a href="#" class="mobileHide" style="background-color: #ff2727; padding: 4px 7px; border-radius: 50%; margin-right: 15px">
                <img src="{{asset('images/mainImage/tv.png')}}" alt="DPL_TV" style="width: 20px">
            </a>
            <a href="{{url('/')}}">
                <img src="{{asset('images/mainImage/dplIcon.jpg')}}" alt="DPL" style="width: 100%">
            </a>

        </div>

        @if( !Request::is('/'))
            <div class="searchNavIconInFlag navSearchBar mobileHide" style="margin-right: auto; background: white; margin-left: 0;" onclick="$('.searchBackBlack').show(); inSearch = true; $('#pcSearchHeaderInput').css('display', 'flex'); $('.searchNavInput').focus();">
                <i class="fas fa-search" style="color: #459ed1"></i>


                <div id="pcSearchHeaderInput" class="navSearchBar mobileHide" style="display: none; position: absolute; z-index: 10; width: 400px; left: -20px">
                    <div class="navSearchIcon">
                        <img src="{{asset('images/mainImage/searchIcon.svg')}}" style="width: 100%;">
                    </div>
                    <input type="text" class="searchNavInput" placeholder="{{__('Where do you want to go?')}}" onfocusout="$('.searchBackBlack').hide(); inSearch = false; clearResult(); $(this).val(''); $('#pcSearchHeaderInput').css('display', 'none')" onkeydown="gollobalSearch(this.value)">
                    <div class="searchResult"></div>
                </div>
            </div>

        @endif

        <div class="navUl">
            <div class="navLi mobileHide" onmouseleave="$('.navSubListAllCountries').css('height', '0px')">
                <div class="navTabName">
                    {{__('Destinations')}}
                </div>
                <div class="subList subLisL">
                    <div class="navListSub">
                        @foreach($destCategory as $item)
                            <div class="navSubListRow">
                                <a href="{{route('show.category', ['slug' => $item->slug])}}"
                                   class="navSubListHeader">{{$item->name}}</a>
                                @for($i = 0; $i < count($item->destination) && $i < 6; $i++)
                                    <div href="{{$item->destination[$i]->url}}" class="navSubListBody">
                                        {{$item->destination[$i]->name}}
                                        <div class="destTitles" style="z-index: 9">
                                            <a href="{{$item->destination[$i]->url}}" class="destTitlesName">
                                                See {{$item->destination[$i]->name}}
                                            </a>
                                            @foreach($item->destination[$i]->titles as $titles)
                                                <a href="{{$item->destination[$i]->url . '#' . $titles}}" class="destTitlesName">
                                                    {{$titles}}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endfor

                                @if(count($item->destination) > 6)
                                    <div class="navSubListFooter" onclick="openAllCountryHeader(this)">See all</div>
                                @endif
                            </div>
                            <div class="navSubListAllCountries">
                                <div class="navSubListAllCountriesClose" onclick="closeAllCountry(this)">
                                    <div class="arrow down closeCountryArrow"></div>
                                </div>
                                <div class="navSubListAllCountriesList">
                                    @foreach($item->destination as $desti)
                                        <a href="{{route('show.destination', ['slug' => $desti->slug])}}"
                                           class="navCountries">
                                            {{$desti->name}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="navLi mobileHide" onmouseleave="$('.navSubListAllCountries').css('height', '0px')">
                <div class="navTabName">
                    {{__('Activities')}}
                </div>

                <div class="subList subLisL">
                    <div class="navListSub">
                        @foreach($activitiesList as $item)
                            <div class="navSubListRow">
                                <a href="{{url('activity/'. $item->slug)}}" class="navSubListHeader">
                                    {{$item->name}}
                                </a>
                                @for($i = 0; $i < count($item->subAct) && $i < 6; $i++)
                                    <a href="{{url('activity/'. $item->subAct[$i]->slug) }}" class="navSubListBody">
                                        {{$item->subAct[$i]->name}}
                                    </a>
                                @endfor

                                @if(count($item->subAct) > 6)
                                    <div class="navSubListFooter" onclick="openAllCountryHeader(this)">See all</div>
                                @endif
                            </div>
                            <div class="navSubListAllCountries">
                                <div class="navSubListAllCountriesClose" onclick="closeAllCountry(this)">
                                    <div class="arrow down closeCountryArrow"></div>
                                </div>
                                <div class="navSubListAllCountriesList">
                                    @foreach($item->subAct as $act)
                                        <a href="{{url('activity/'. $act->slug) }}"
                                           class="navCountries">
                                            {{$act->name}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="navLi mobileHide" onmouseleave="$('.navSubListAllCountries').css('height', '0px')">
                <div class="navTabName">
                    {{__('Fest & Events')}}
                </div>

                <div class="subList subLisL">
                    <div class="navListSub">
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Sport
                            </a>
                            <a href="#" class="navSubListBody">
                                Bisotun
                            </a>
                            <a href="#" class="navSubListBody">
                                Skimo
                            </a>
                            <a href="#" class="navSubListBody">
                                Sky running
                            </a>
                            <a href="#" class="navSubListBody">
                                Ice Climbing
                            </a>
                        </div>
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Local events
                            </a>
                            <a href="#" class="navSubListBody">
                                Regions
                            </a>
                            <a href="#" class="navSubListBody">
                                Saffron
                            </a>
                            <a href="#" class="navSubListBody">
                                Kurdis music
                            </a>
                            <a href="#" class="navSubListBody">
                                Rose water
                            </a>
                            <a href="#" class="navSubListBody">
                                Street music
                            </a>
                            <a href="#" class="navSubListBody">
                                Cherry
                            </a>
                        </div>
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Training
                            </a>
                            <a href="#" class="navSubListBody">
                                Youth Camp
                            </a>
                            <a href="#" class="navSubListBody">
                                Ski
                            </a>
                            <a href="#" class="navSubListBody">
                                Grass ski
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="navLi mobileHide" onmouseleave="$('.navSubListAllCountries').css('height', '0px')">
                <div class="navTabName">
                    {{ __('Pre-trips') }}
                </div>

                <div class="subList subLisL">
                    <div class="navListSub">
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Accommodation
                            </a>
                            <a href="#" class="navSubListBody">
                                Camps
                            </a>
                            <a href="#" class="navSubListBody">
                                Huts
                            </a>
                            <a href="#" class="navSubListBody">
                                Localhouses & eco-lodges
                            </a>
                            <a href="#" class="navSubListBody">
                                Hostles
                            </a>
                            <a href="#" class="navSubListBody">
                                Hotels
                            </a>
                        </div>
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Pre-trip checklist
                            </a>
                            <a href="#" class="navSubListBody">
                                Travel extras
                            </a>
                            <a href="#" class="navSubListBody">
                                Tutorals
                            </a>
                            <a href="#" class="navSubListBody">
                                General
                            </a>
                        </div>
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Good to know
                            </a>
                            <a href="#" class="navSubListBody">
                                Destinations
                            </a>
                            <a href="#" class="navSubListBody">
                                Activities
                            </a>
                            <a href="#" class="navSubListBody">
                                General
                            </a>
                        </div>
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Book transfers
                            </a>
                            <a href="#" class="navSubListBody">
                                Flights
                            </a>
                            <a href="#" class="navSubListBody">
                                Train
                            </a>
                            <a href="#" class="navSubListBody">
                                Bus
                            </a>
                            <a href="#" class="navSubListBody">
                                Car rental
                            </a>
                        </div>
                        <div class="navSubListRow">
                            <a href="#" class="navSubListHeader">
                                Guides and experts
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="navLi posRel mobileHide">
                <a href="#" class="navTabName">
                    {{ __('Nature friend') }}
                </a>

                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Trekking Guide Course</div>
                        <div class="navSubListBody navSubListBodyM">Events</div>
                        <div class="navSubListBody navSubListBodyM">News</div>
                    </div>
                </div>

            </div>

            <div class="navLi posRel mobileHide">
                <a href="#" class="navTabName">
                    {{ __('Community') }}
                </a>

                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">Topical discussion</div>
                        <div class="navSubListBody navSubListBodyM">GO-Live</div>
                        <div class="navSubListBody navSubListBodyM">Your shots</div>
                        <div class="navSubListBody navSubListBodyM">Our shots</div>
                    </div>
                </div>
            </div>

{{--            <div class="navLi posRel mobileHide">--}}
{{--                <a href="{{route('aboutUs')}}" class="navTabName">--}}
{{--                    {{ __('About us') }}--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="navLi posRel mobileHide">
                <a href="{{route('journal.index')}}" class="navTabName">
                    {{ __('Journal') }}
                </a>
            </div>

            <div class="navLi posRel mobileHide rtlRight">
                <div class="navTabName">
                    <div class="navPerson">
                        <i class="far fa-user" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <div class="navSubListBody navSubListBodyM">{{__('sign in')}}</div>
                        <div class="navSubListBody navSubListBodyM">{{__('login')}}</div>
                        <div class="navSubListBody navSubListBodyM">{{__('Community')}}</div>
                    </div>
                </div>
            </div>
            <div class="navLi posRel mobileHide">
                <div class="navTabName" style="padding: 0">
                    <div class="navFlag">
                        {{strtoupper(app()->getLocale())}}
                    </div>
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        <a href="{{ url('locale/en') }}" class="navSubListBody navSubListBodyM">English</a>
                        @foreach($languages as $lang)
                            <a href="{{ url('locale/'. $lang->symbol) }}" class="navSubListBody navSubListBodyM">{{$lang->name}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="navLi">
                <div class="searchNavIconInFlag pcHide" onclick="showNavSearchMobile()">
                    <i class="fas fa-search" style="color: #459ed1"></i>
                </div>
                {{--                <div class="telPcLi">--}}
                {{--                    <div class="navFlag">--}}
                {{--                        EN--}}
                {{--                    </div>--}}
                {{--                    <div class="navPhone">--}}
                {{--                        +989122474393--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="telMobileLi">--}}
                {{--                    <i class="fas fa-phone phoneIconHeader"></i>--}}
                {{--                </div>--}}
            </div>
        </div>


{{--        <div class="headerSearchAndMenuPc">--}}
{{--            --}}

{{--           --}}
{{--        </div>--}}


        <div id="searchNavMobile" class="searchNavMobile pcHide">
            <div style="width: 100%; display: flex; height: 100%;">
                <div class="mobileNavSearchDiv">
                    <input type="text" class="mobileNavSearchInput" placeholder="{{__('Where do you want to go?')}}"
                           onkeyup="gollobalSearch(this.value)">
                    <div class="searchResult"></div>
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
        <div class="container">
            <div class="row" style="width: 100%; margin: 0;">
                <div>
                    <a href="{{url('/')}}" class="topMenuGuide">{{__('Home')}}</a>
                    <span class="topMenuGuide"> > </span>
                    <a href="{{$guidance['value1Url']}}" class="topMenuGuide">{{$guidance['value1']}}</a>
                    @if(isset($guidance['value2']))
                        <a href="#" class="topMenuGuide"> > </a>
                        <a href="{{$guidance['value2Url']}}" class="topMenuGuide">{{$guidance['value2']}}</a>
                        @if(isset($guidance['value3']))
                            <a href="#" class="topMenuGuide"> > </a>
                            <a href="{{$guidance['value3Url']}}" class="topMenuGuide">{{$guidance['value3']}}</a>
                            @if(isset($guidance['value4']))
                                <a href="#" class="topMenuGuide"> > </a>
                                <a href="{{$guidance['value4Url']}}" class="topMenuGuide">{{$guidance['value4']}}</a>
                                @if(isset($guidance['value5']))
                                    <a href="#" class="topMenuGuide"> > </a>
                                    <a href="#" class="topMenuGuide">{{$guidance['value5']}}</a>
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

    function closeAllCountry(_element) {
        $(_element).parent().css('height', '0')
    }

    function gollobalSearch(_value) {

        if (_value.trim().length > 1) {
            $.ajax({
                type: 'post',
                url: '{{route("search")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    value: _value
                },
                success: function (response) {
                    try {
                        response = JSON.parse(response);
                        if (response['status'] == 'ok') {
                            createSearchResult(response['result']);
                        }
                    } catch (e) {
                        console.log(e)
                    }
                },
                error: function (err) {

                }
            })
        }
    }

    function createSearchResult(_result) {
        var fk = Object.keys(_result);
        var text = '';
        for (var x of fk) {
            if (_result[x].length > 0)
                text += '<div class="headerSearch">' + x + '</div>';
            for (var i = 0; i < _result[x].length; i++) {
                console.log(_result[x][i]["url"]);
                text += '<a href="' + _result[x][i]["url"] + '"><div class="resultsOFSearch">' + _result[x][i]["name"] + '</div></a>';
            }
        }

        $('.searchResult').show();
        $('.searchResult').html(text);
    }

</script>

<script src="{{asset('js/headerJs.js')}}"></script>
