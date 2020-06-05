<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DPL') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesom/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">

    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('js/popper.min.js')}}"></script>
    <script src="{{ asset('js/tippy-bundle.umd.min.js')}}"></script>
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <style>


        body{
            font-family: 'Varela Round', sans-serif;
            /*font-family: 'Montserrat', sans-serif;*/
        }
        .loadingDiv{
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000000d6;
            justify-content: center;
            align-items: center;
            z-index: 5;
        }
        .loadingLogo{
            background: white;
            width: 200px;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            overflow: hidden;
        }
    </style>

    @yield('head')

    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>

    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlBase.css')}}">
    @endif

</head>
<body style="overflow-x: hidden">
<div>

    <div class="loadingDiv">
        <div class="loadingLogo">
            <img id="loadingImg" src="{{asset('images/mainImage/mainLoading.gif')}}" style="width: 270px;">
        </div>
    </div>

    @include('layouts.header')

    <main>
        @yield('body')
    </main>

    @include('layouts.footer')

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

    $(document).ready(function(){
        resizeImg('resizeImage');
    });
    $(window).ready(function(){
        resizeImg('resizeImage');
    });

    function openLoading(){
        $('.loadingDiv').css('display', 'flex');
    }
    function closeLoading(){
        $('.loadingDiv').css('display', 'none');
    }

    let acc = document.getElementsByClassName("accordion");

    for (let i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            this.classList.toggle("activePanel");
            var panel = this.nextElementSibling;
            if (panel.style.display === "flex") {
                panel.style.display = "none";
            } else {
                panel.style.display = "flex";
            }
        });
    }
</script>

@yield('script')


@if(app()->getLocale() == 'fa')
    <script src="{{asset('js/persianNumber.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('*').persiaNumber('fa');
        });
    </script>
@endif

</html>
