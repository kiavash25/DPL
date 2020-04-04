<style>
    nav{
        position: fixed;
        height: 100%;
        width: 20%;
        background: white;
        transition: .5s;
        left: 0px;
    }
    .sideNavHeaderIcon{
        margin-top: 75px;
        display: block;
    }
    .sideNavMenuDiv{
        margin-top: 45px;
        width: 85%;
        display: flex;
        flex-direction: column;
        text-align: right;
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

    @media (max-width: 1200px) {
        nav{
            width: 30%;
        }
    }

    @media (max-width: 767px){
        nav{
            width: 250px;
            left: -250px;
        }
    }
</style>

<nav>
    <a href="{{url('/')}}" class="sideNavHeaderIcon">
        <img src="{{asset('images/mainImage/dplIcon.jpg')}}" alt="dpl" style="width: 80%; margin-left: 10%;">
    </a>

    <div class="sideNavMenuDiv">
        <a href="{{route('journal.index')}}" class="sideNavLink sideNavLinkChoose">
            Home
        </a>
        @foreach($allCategory as $item)
            <a href="#" class="sideNavLink">
                {{$item->name}}
            </a>
        @endforeach
    </div>
</nav>

<script>
    function toggleSideNav(){
        $('nav').toggleClass('openSideNav');
        $('.mainBase').toggleClass('openMain');
    }
</script>
