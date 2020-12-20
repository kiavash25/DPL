<style>
    .mainNav{
        position: fixed;
        height: 100%;
        width: 20%;
        background: white;
        transition: .5s;
        left: 0px;
    }
    .sideNavHeaderIcon{
        margin-top: 0px;
        display: block;
    }
    .sideNavMenuDiv{
        margin-top: 45px;
        width: 85%;
        display: flex;
        flex-direction: column;
        text-align: right;
        margin-right: auto;
    }
    .sideNavLink{
        margin: 9px 0px;
        color: #a2a2a2;
        font-size: 18px;
        transition: .1s;
    }
    .sideNavLinkChoose{
        color: #1f75b9;
        font-weight: bold;
        text-decoration: underline;
    }
    .sideNavLink:hover{
        color: #1f75b9;
        font-weight: bold;
    }
    .openSideNav{
        left: 0px;
    }
    .langDiv{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        flex-direction: column;
        position: absolute;
        bottom: 30px;
    }
    .selectLang{
        width: 150px;
        font-size: 20px;
        border: none;
        border: solid 1px #1f75b9;
        border-radius: 10px;
        color: #1f75b9;
    }

    @media (max-width: 1200px) {
        .mainNav{
            width: 30%;
        }
    }

    @media (max-width: 767px){
        .mainNav{
            width: 250px;
            left: -250px;
        }
    }
</style>

<nav class="mainNav">
    <a href="{{url('/')}}" class="sideNavHeaderIcon">
        <img src="{{asset('images/mainImage/dplIcon.jpg')}}" alt="dpl" style="width: 80%; margin-left: 10%; margin-right: 10%;">
    </a>

    <div class="sideNavMenuDiv">
        <a href="{{route('journal.index')}}" class="sideNavLink {{!isset($sideNavChoose) ? 'sideNavLinkChoose' : ''}}">
            {{__('Home')}}
        </a>
        @foreach($allCategory as $item)
            <a href="{{route('journal.list', ['kind' => 'category', 'value' => $item->name])}}" class="sideNavLink {{isset($sideNavChoose) && $sideNavChoose == $item->name ? 'sideNavLinkChoose' : ''}}">
                {{$item->name}}
            </a>
        @endforeach
    </div>

{{--    <div class="langDiv">--}}
{{--        <label for="languages" style="color: #a2a2a2; font-size: 18px">{{__('Language')}}</label>--}}
{{--        <select name="languages" id="languages" class="selectLang" onchange="location.href='{{url('admin/locale/')}}/' + this.value">--}}
{{--            <option value="en" {{app()->getLocale() == 'en' ? 'selected' : ''}}>English</option>--}}
{{--            @foreach($languages as $lang)--}}
{{--                <option value="{{$lang->symbol}}" {{app()->getLocale() == $lang->symbol ? 'selected' : ''}}>{{$lang->name}}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}

</nav>

<script>
    function toggleSideNav(x){
        x.classList.toggle("change");

        $('.mainNav').toggleClass('openSideNav');
        $('.mainBase').toggleClass('openMain');
    }
</script>
