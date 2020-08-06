<link rel="stylesheet" href="{{asset('css/admin/adminHeader.css')}}">

<div id="mySidenav" class="sidenav">
    <div class="sideHeader">
        <a href="{{url('/')}}" class="sideHeaderText">
            <img src="{{asset('images/mainImage/mainIcon3.png')}}" alt="DPL" style="width: 100%">
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

        @can('canSee', 'destination')
            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Destinations')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.destination.category.index')}}">
                            {{__('Destination Category')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.destination.list')}}">
                            {{__('Destination List')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.destination.new')}}">
                            {{__('Create New Destination')}}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('canSee', 'activity')
            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Activity')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.activity.list')}}">
                            {{__('Activity List')}}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('canSee', 'package')
            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Package')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">

                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.package.moreInfoTitle')}}">
                            {{__('Package More Info Titles')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.package.list')}}">
                            {{__('Package List')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.package.new')}}">
                            {{__('Create New Package')}}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('canSee', 'journal')
            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Journal')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.journal.list')}}">
                            {{__('Journal List')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.journal.category.index')}}">
                            {{__('Journal Category List')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.journal.new')}}">
                            {{__("Create New Journal")}}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('canSee', 'setting')
            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Setting')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.setting.mainPageSlider')}}">
                            {{__('Main Page Slider')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.setting.mainPage')}}">
                            {{__('Main Page')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.setting.awards')}}">
                            {{__('Awards')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.setting.lang')}}">
                            {{__('Language')}}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can('canSee', 'userAccess')
            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Content')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                            {{__('Shots')}}
                            <div class="arrow rightArrow"></div>
                        </a>
                        <div class="subSideNavMenu">
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="{{route('admin.shots.category')}}">
                                    {{__('Shots category')}}
                                </a>
                            </div>
                            <div class="sideNavTabs">
                                <a class="subSideNavTab" href="{{route('admin.shots.ourShot')}}">
                                    {{__('Our shot')}}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Topical discussion')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.forum.category')}}">
                            {{__('Category')}}
                        </a>
                    </div>
                </div>

            </div>

            <div class="sideNavTabs">
                <a href="#" class="subSideNavTab" onclick="showSubSideNavMenu(this)">
                    {{__('Users')}}
                    <div class="arrow rightArrow"></div>
                </a>
                <div class="subSideNavMenu">
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('admin.userAccess.list')}}">
                            {{__('Admin List')}}
                        </a>
                    </div>
                    <div class="sideNavTabs">
                        <a class="subSideNavTab" href="{{route('register')}}">
                            {{__('Add new admin')}}
                        </a>
                    </div>
                </div>
            </div>
        @endcan

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

