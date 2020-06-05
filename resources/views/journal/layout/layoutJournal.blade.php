<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DPL') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->

    <link rel="stylesheet" href="{{asset('semanticUi/semantic.min.css')}}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesom/css/all.css')}}">

    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>

    <script src="{{ asset('js/popper.min.js')}}"></script>
    <script src="{{ asset('js/tippy-bundle.umd.min.js')}}"></script>

    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,400&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">
    <style>
        *{
            font-family: 'Varela Round', sans-serif;
            /*font-family: 'Montserrat', sans-serif;*/
        }
        body{
            line-height: normal;
        }
        .mainBase{
            transition: .5s;
            position: fixed;
            right: 0px;
            /*width: 80%;*/
            padding-bottom: 50px;
            width: 100%;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .sideNavButton{
            position: absolute;
            top: 0px;
            left: 0px;
            display: none;
            z-index: 5;
        }
        .navThreeLine1, .navThreeLine2, .navThreeLine3{
            border-radius: 100px;
            width: 30px;
            height: 5px;
            margin-top: 5px;
            background-color: black;
        }
        .threeLineDiv{
            padding: 15px 0;
            margin: 0 16px 0 6px;
            position: fixed;
        }

        .change .navThreeLine1 {
            -webkit-transform: rotate(-45deg) translate(-9px, 6px);
            transform: rotate(-45deg) translate(-6px, 6px);
        }

        .change .navThreeLine2 {opacity: 0;}

        .change .navThreeLine3 {
            -webkit-transform: rotate(45deg) translate(-8px, -8px);
            transform: rotate(45deg) translate(-8px, -8px);
        }

        /*.logoNavDiv{*/
        /*    display: none !important;*/
        /*}*/
        /*.navSearchBar{*/
        /*    display: none !important;*/
        /*}*/
        /*.navUl{*/
        /*    margin-left: auto !important;*/
        /*    width: auto !important;*/
        /*}*/

        @media (max-width: 1200px) {
            /*.mainBase{*/
            /*    width: 70%;*/
            /*}*/
        }
        @media (max-width: 767px){
            .mainBase{
                width: 100%;
            }
            .sideNavButton{
                display: block;
            }
            .openMain{
                right: -250px;
            }
        }
    </style>

    @yield('head')

</head>
<body style="overflow-x: hidden;">

@include('layouts.header')

<div>


    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>

    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlBase.css')}}">
        <link rel="stylesheet" href="{{asset('css/rtl/journalBase.css')}}">
    @endif

{{--    @include('journal.layout.sideNaveJournal')--}}

    <main>
        <div class="mainBase" style="direction: ltr">
{{--            <div class="sideNavButton threeLineDiv" onclick="toggleSideNav(this)">--}}
{{--                <div class="navThreeLine1"></div>--}}
{{--                <div class="navThreeLine2"></div>--}}
{{--                <div class="navThreeLine3"></div>--}}
{{--            </div>--}}

            @yield('body')
        </div>
    </main>

</div>
</body>

<script>
    function resizeImg(_class) {
        var imgs = $('.' + _class);
        for(i = 0; i < imgs.length; i++){
            var img = $(imgs[i]);
            var imgW = img.width();
            var imgH = img.height();

            var secW = img.parent().width();
            var secH = img.parent().height();

            if(imgH < secH){
                img.css('height', '100%');
                img.css('width', 'auto');
            }
            else if(imgW < secW){
                img.css('width', '100%');
                img.css('height', 'auto');
            }
        }
    }

</script>

@yield('script')

<script >
    $(document).ready(function(){
        resizeImg('resizeImage');
        $(window).resize(function(){
            resizeImg('resizeImage');
        });

        $('#mainContentDiv').transition({
            animation  : 'fade up',
            duration   : '1s',
        });

        setTimeout(function(){
            $('#sideContentDiv').transition({
                animation  : 'fade up',
                duration   : '1s',
            });
        }, 300);
    });

</script>

</html>
