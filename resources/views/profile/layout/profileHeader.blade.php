<link rel="stylesheet" href="{{asset('css/admin/adminHeader.css')}}">

<div id="mySidenav" class="sidenav">
    <div class="sideHeader">
        <a href="{{url('/')}}" class="sideHeaderText">
            <img src="{{$profile['pic']}}" alt="DPL" style="width: 100%">
        </a>
        <h3 style="color: white; margin: 0px;">
            {{auth()->user()->name}}
        </h3>
    </div>
    <div class="sideBody">
        <div id="backSideNavMenuDiv" class="sideNavTabs sideNavTabsHeader"  onclick="backSideNavMenu()">
            <a href="#" style="font-weight: bold; justify-content: center; color: #30759d;">
                <div class="arrow leftArrow" style="position: absolute; left: 20px; border-color: #30759d;"></div>
                <div id="backSideNavMenuName"></div>
            </a>
        </div>

        <div class="sideNavTabs">
            <a class="subSideNavTab" href="{{ route('profile.setting') }}">
                {{__('Setting')}}
            </a>
        </div>

        <div class="sideNavTabs">
            <a class="subSideNavTab" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{__('Log out')}}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>


    <?php
    $selectLang = \App\models\Language::all();
    ?>
    <select name="selectLanguage" id="selectLanguage" class="form-control" style="position: absolute; bottom: 0px;" onchange="location.href='{{url('admin/locale/')}}/' + this.value">
        <option value="en" {{app()->getLocale() == 'en' ? 'selected' : ''}}>English</option>
        @foreach( $selectLang as $item)
            <option value="{{$item->symbol}}" {{app()->getLocale() == $item->symbol ? 'selected' : ''}}>{{$item->name}}</option>
        @endforeach
    </select>
</div>

<nav id="mainNav" class="goRight">
    <div class="navContainer">
        <div class="threeLineDiv" onclick="toggleNav()">
            <div class="navThreeLine"></div>
            <div class="navThreeLine"></div>
            <div class="navThreeLine"></div>
        </div>
    </div>
</nav>

<script src="{{asset('js/pages/adminHeaderJs.js')}}"></script>
