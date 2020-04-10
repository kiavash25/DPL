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
                    @foreach($destCategory as $item)
                        <div class="sideNavTabs">
                            <a href="#" onclick="showSubSideNavMenu(this)">
                                {{$item->name}}
                                <div class="arrow right"></div>
                            </a>

                            <div class="subSideNavMenu subSubSideNavMenu">
                                <div class="sideNavTabs">
                                    <a class="subSideNavTab" href="{{route('show.list', ['kind' => 'destination', 'value1' => $item->name ])}}">
                                        See all
                                    </a>
                                </div>
                                @for($i = 0; $i < count($item->destination); $i++)
                                    <div class="sideNavTabs">
                                        <a class="subSideNavTab" href="{{route('show.destination', ['categoryId' => $item->destination[$i]->categoryId, 'slug' => $item->destination[$i]->slug])}}">
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
                    Activities
                    <div class="arrow right"></div>
                </a>
                <div class="subSideNavMenu">
                    @foreach($activitiesList as $item)
                        <div class="sideNavTabs">
                            <a class="subSideNavTab" href="{{url('list/activity/'. $item->name)}}">
                                {{$item->name}}
                            </a>
                        </div>
                    @endforeach
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
                <a href="{{route('aboutUs')}}">
                    About us
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="#">
                    Contact us
                </a>
            </div>
            <div class="sideNavTabs">
                <a href="{{route('journal.index')}}">
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
                <input type="text" class="searchNavInput" placeholder="Where do you want to go?" onfocus="$('.searchBackBlack').show(); inSearch = true;" onfocusout="$('.searchBackBlack').hide(); inSearch = false; clearResult(); $(this).val('')" onkeydown="gollobalSearch(this.value)">
                <div class="searchResult"></div>
            </div>
        @endif

        <ul class="navUl">
            <li class="navLi mobileHide" onmouseleave="$('.navSubListAllCountries').css('height', '0px')">
                <div class="navTabName">
                    Destinations
                </div>
                <div class="subList subLisL">
                    <div class="navListSub">
                        @foreach($destCategory as $item)
                            <div class="navSubListRow">
                                <a href="{{route('show.list', ['kind' => 'destination', 'value1' => $item->name ])}}" class="navSubListHeader">{{$item->name}}</a>
                                @for($i = 0; $i < count($item->destination) && $i < 6; $i++)
                                    <a href="{{route('show.destination', ['categoryId' => $item->destination[$i]->categoryId, 'slug' => $item->destination[$i]->slug])}}" class="navSubListBody">{{$item->destination[$i]->name}}</a>
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
                                        <a href="{{route('show.destination', ['categoryId' => $desti->categoryId, 'slug' => $desti->slug])}}" class="navCountries">
                                            {{$desti->name}}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </li>
            <li class="navLi posRel mobileHide">
                <div class="navTabName">
                    Activities
                </div>
                <div class="subList subLisM">
                    <div class="navSubListRow">
                        @foreach($activitiesList as $item)
                            <a href="{{url('list/activity/'. $item->name)}}" class="navSubListBody navSubListBodyM">{{$item->name}}</a>
                        @endforeach
                    </div>
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
                <a href="{{route('aboutUs')}}" class="navTabName">
                    About us
                </a>
            </li>
            <li class="navLi posRel mobileHide">
                <a href="{{url('/')}}" class="navTabName">
                    Contact us
                </a>
            </li>
            <li class="navLi posRel mobileHide">
                <a href="{{route('journal.index')}}" class="navTabName">
                    Journal
                </a>
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
                    <input type="text" class="mobileNavSearchInput" placeholder="Where do you want to go?" onkeyup="gollobalSearch(this.value)">
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
        <div class="container" >
            <div class="row" style="width: 100%; margin: 0;">
                <div>
                    <a href="{{url('/')}}" class="topMenuGuide">Home</a>
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
    function closeAllCountry(_element){
        $(_element).parent().css('height', '0')
    }

    function gollobalSearch(_value){

        if(_value.trim().length > 1){
            $.ajax({
                type: 'post',
                url: '{{route("search")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    value: _value
                },
                success: function(response){
                    try {
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            createSearchResult(response['result']);
                        }
                    }
                    catch (e) {
                        console.log(e)
                    }
                },
                error: function(err){

                }
            })
        }
    }

    function createSearchResult(_result){
        var fk = Object.keys(_result);
        var text = '';
        for (var x of fk) {
            if(_result[x].length > 0)
                text += '<div class="headerSearch">' + x + '</div>';
            for(var i = 0; i < _result[x].length; i++){
                console.log(_result[x][i]["url"]);
                text += '<a href="' + _result[x][i]["url"] + '"><div class="resultsOFSearch">' + _result[x][i]["name"] + '</div></a>';
            }
        }

        $('.searchResult').show();
        $('.searchResult').html(text);
    }

</script>

<script src="{{asset('js/headerJs.js')}}"></script>
