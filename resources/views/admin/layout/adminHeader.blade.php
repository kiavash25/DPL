<link rel="stylesheet" href="{{asset('css/admin/adminHeader.css')}}">

<div id="mySidenav" class="sidenav">
    <div class="sideHeader">
        <div class="sideHeaderText"> DPL </div>
    </div>
    <div class="sideBody">
        <div id="backSideNavMenuDiv" class="sideNavTabs sideNavTabsHeader"  onclick="backSideNavMenu()">
            <a href="#" style="font-weight: bold; justify-content: center; color: #30759d;">
                <div class="arrow leftArrow" style="position: absolute; left: 20px; border-color: #30759d;"></div>
                <div id="backSideNavMenuName"></div>
            </a>
        </div>

        <div class="sideNavTabs">
            <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                Destinations
                <div class="arrow rightArrow"></div>
            </a>
            <div class="subSideNavMenu">
                <div class="sideNavTabs">
                    <a class="subSideNavTab" href="{{route('admin.destination.list')}}">
                        All Destination
                    </a>
                </div>
                <div class="sideNavTabs">
                    <a class="subSideNavTab" href="{{route('admin.destination.new')}}">
                        Create New Destination
                    </a>
                </div>
            </div>
        </div>
        <div class="sideNavTabs">
            <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                Package
                <div class="arrow rightArrow"></div>
            </a>
            <div class="subSideNavMenu">
                <div class="sideNavTabs">
                    <a class="subSideNavTab" href="{{route('admin.package.list')}}">
                        All Package
                    </a>
                </div>
                <div class="sideNavTabs">
                    <a class="subSideNavTab" href="{{route('admin.package.new')}}">
                        Create New Package
                    </a>
                </div>
            </div>
        </div>
        <div class="sideNavTabs">
            <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                Activity
                <div class="arrow rightArrow"></div>
            </a>
            <div class="subSideNavMenu">
                <div class="sideNavTabs">
                    <a class="subSideNavTab" href="{{route('admin.activity.list')}}">
                        All Activity
                    </a>
                </div>
            </div>
        </div>
        <div class="sideNavTabs">
            <a href="#" class="subSideNavTab">
                Log out
            </a>
        </div>
    </div>
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

<script src="{{asset('js/admin/adminHeaderJs.js')}}"></script>
