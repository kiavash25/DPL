<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PanelAdmin DPL</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontawesom/css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin/adminAllPages.css')}}">

    <script src="{{ asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap.min.js')}}"></script>

    @yield('head')

</head>
<body style="overflow-x: hidden">
<div>

    @include('common.alerts')

    @include('admin.layout.adminHeader')

    <main id="mainBody" class="goRight">
        <div class="container-fluid">
            @yield('body')
        </div>
    </main>

</div>
</body>


@yield('script')

</html>
