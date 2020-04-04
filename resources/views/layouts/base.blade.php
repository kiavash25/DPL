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
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesom/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/allPages.css')}}">

    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Varela Round', sans-serif;
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
        .footer{
            width: 100%;
        }
        .footerContent{
            border-top: 1px solid #c7d0d9;
            display: flex;
            justify-content: space-around;
            padding: 30px;
        }
        .socialContent{
            border-top: 1px solid #c7d0d9;
            display: flex;
            padding: 40px 20px;
            justify-content: center;
        }
        .footerSocialIcons{
            width: 40px;
            height: 40px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            margin-right: 10px;
        }
        .contentRow{
            display: flex;
            flex-direction: column;
        }
        .footerContentHeader{
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
            padding: 5px;
            color: #2c3e50 !important;
        }
        .footerContentNormal{
            font-size: 14px;
            margin-bottom: 8px;
            cursor: pointer;
            padding: 5px;
            color: #2c3e50;
        }
        .footerContentNormal:hover{
            color: white !important;
            background-color: #30759d;
            border-radius: 4px;
        }
        .pcFooter{
            display: flex;
        }
        .mobileFooter{
            display: none;
            flex-direction: column;
        }
        .accordion {
            background-color: white;
            border: none;
            border-bottom: 1px solid #c7d0d9;
            border-top: 1px solid #c7d0d9;
            color: #2c3e50;
            cursor: pointer;
            padding: 18px;
            width: 100%;
            text-align: left;
            outline: none;
            transition: 0.4s;
            font-size: 14px;
            font-weight: bold;
            padding-left: 45px;
        }
        .inPanel{
            color: #2c3e50;
            margin: 3px;
            font-size: 17px;
            font-weight: 200;
        }
        .panel {
            padding: 10px 18px;
            background-color: white;
            display: none;
            flex-direction: column;
            text-align: center;
            overflow: hidden;
        }
        @media (max-width: 700px){
            .footer{
                padding: 0px;
            }
            .pcFooter{
                display: none;
            }
            .mobileFooter{
                display: flex;
            }
        }

    </style>

    @yield('head')

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

    <footer>
        <div class="container footer">
            <div class="footerContent pcFooter">
                <div class="contentRow">
                    <div class="footerContentHeader">
                        Destinations
                    </div>
                    @foreach($destCategory as $item)
                        <a href="{{route('show.list', ['kind' => 'destination', 'value1' => $item->name ])}}" class="footerContentNormal">
                            {{$item->name}}
                        </a>
                    @endforeach
                </div>
                <div class="contentRow">
                    <div class="footerContentHeader">
                        Activity
                    </div>
                    @foreach($activitiesList as $item)
                        <a href="{{url('list/activity/'. $item->name)}}" class="footerContentNormal">
                            {{$item->name}}
                        </a>
                    @endforeach
                </div>
                <div class="contentRow">
                    <div class="footerContentHeader">
                        Fest & Event
                    </div>
                </div>
                <div class="contentRow">
                    <a href="{{route('journal.index')}}" class="footerContentHeader">
                        Journal
                    </a>
                    <a href="{{route('aboutUs')}}" class="footerContentHeader">
                        About us
                    </a>
                    <a href="#" class="footerContentHeader">
                        Contact us
                    </a>
                </div>

            </div>

            <div class="mobileFooter">
                <button class="accordion">Destinations</button>
                <div class="panel">
                    @foreach($destCategory as $item)
                        <a href="{{route('show.list', ['kind' => 'destination', 'value1' => $item->name ])}}" class="inPanel">
                            {{$item->name}}
                        </a>
                    @endforeach
                </div>

                <button class="accordion">Activity</button>
                <div class="panel">
                    @foreach($activitiesList as $item)
                        <a href="{{url('list/activity/'. $item->name)}}" class="inPanel">
                            {{$item->name}}
                        </a>
                    @endforeach
                </div>

                <button class="accordion"> Fest & Event</button>

                <a href="{{route('journal.index')}}">
                    <button class="accordion">Journal</button>
                </a>

                <a href="{{route('aboutUs')}}">
                    <button class="accordion">About us</button>
                </a>

                <a href="#">
                    <button class="accordion">Contact us</button>
                </a>
            </div>

            <div class="socialContent">
                <a href="#" class="footerSocialIcons">
                    <img src="{{asset('images/mainImage/facebook.png')}}" style="width: 100%; height: 100%">
                </a>
                <a href="#" class="footerSocialIcons">
                    <img src="{{asset('images/mainImage/insta.png')}}" style="width: 100%; height: 100%">
                </a>
                <a href="#" class="footerSocialIcons">
                    <img src="{{asset('images/mainImage/twitter.png')}}" style="width: 100%; height: 100%">
                </a>
                <a href="#" class="footerSocialIcons">
                    <img src="{{asset('images/mainImage/teleg.png')}}" style="width: 100%; height: 100%">
                </a>
            </div>

            <div style="border-top: 1px solid #c7d0d9; text-align: center; padding: 30px 0px; font-size: 11px">
                Copyright © DPL. All rights reserved
            </div>
        </div>
    </footer>

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

    function openLoading(){
        $('.loadingDiv').css('display', 'flex');
    }
    function closeLoading(){
        $('.loadingDiv').css('display', 'none');
    }

    var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
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

</html>
