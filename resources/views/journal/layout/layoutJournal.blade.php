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

    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,400&display=swap" rel="stylesheet">

    <style>
        *{
            font-family: 'Montserrat', sans-serif;
        }
        body{
            line-height: normal;
        }
        .mainBase{
            transition: .5s;
            position: fixed;
            right: 0px;
            width: 80%;
            height: 100vh;
            overflow-y: auto;
        }
        .sideNavButton{
            position: absolute;
            top: 0px;
            left: 0px;
            display: none;
            z-index: 5;
        }
        @media (max-width: 1200px) {
            .mainBase{
                width: 70%;
            }
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
<div>

    @include('journal.layout.sideNaveJournal')

    <main>
        <div class="mainBase">
            <button class="sideNavButton" onclick="toggleSideNav()">click</button>
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

</html>
