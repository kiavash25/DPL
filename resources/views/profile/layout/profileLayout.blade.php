<!DOCTYPE html>
<html translate="no">
<head>
    <meta charset="utf-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Request::is('admin') || Request::is('admin/*'))
        <title>PanelAdmin DPL</title>
    @else
        <title>Profile DPL</title>
    @endif


    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesom/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin/adminAllPages.css')}}">

    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>


    <style>
        .haveNotLang{
            background: #ffa7a7;
        }
    </style>
    @yield('head')

</head>
<body style="overflow-x: hidden">
<div>

    @include('common.alerts')

    @if(Request::is('admin') || Request::is('admin/*'))
        @include('profile.layout.adminHeader')
    @else
        @include('profile.layout.profileHeader')
    @endif

    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>

    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/adminRtl.css')}}">
    @endif

    <main id="mainBody" class="goRight">
        <div class="container-fluid">
            @yield('body')
        </div>
    </main>

</div>
</body>

<script>
    function showPics(_input, _kind, _callBack){
        if(_input.files && _input.files[0]){
            var reader = new FileReader();
            reader.onload = function(e) {
                var pic = e.target.result;
                if(_kind != '')
                    $('#' + _kind).attr('src', pic);

                if(typeof _callBack == 'function')
                    _callBack(_input.files[0]);
            };
            reader.readAsDataURL(_input.files[0]);
        }
    }

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
        resizeImg('resizeImageClass');
    });
    $(window).resize(function(){
        resizeImg('resizeImageClass');
    });

    $('.importantInput').on('change', function () {
        if(this.value.trim().length == 0) {
            $(this).addClass('invalidInput');
            $(this).parent().find('.errorImportantInput').text('{{__('This field is required')}}')
        }
        else {
            $(this).removeClass('invalidInput');
            $(this).parent().find('.errorImportantInput').text('')
        }
    });
</script>

@yield('script')

</html>
