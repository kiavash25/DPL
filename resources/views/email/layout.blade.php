<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 " />
    <meta charset="UTF-8">
    <title>DPL</title>
    <style>
        *{
            font-family: Tahoma,Geneva,sans-serif;
        }
        .header{
            width: 100%;
            height: 50px;
        }
        .header .logo{
            height: 100%;
            width: fit-content;
            margin: 0px auto;
        }
        .body{
            width: 600px;
            margin: 20px auto;
            border: solid 2px gray;
            border-radius: 14px;
            padding: 27px;
            direction: ltr;
            text-align: left;
        }
    </style>

    @yield('head')

</head>
<body style="width: 100%; direction: ltr">
<div class="container">
    <div class="header">
        <div class="logo">
            <img src="http://discoverpersialand.com/images/mainImage/dplIcon.jpg" alt="DPL" title="DPL" style="height: 100%; margin: 0px auto">
        </div>
    </div>

    <div class="body">
        @yield('body')
    </div>
</div>
</body>

@yield('script')
</html>
