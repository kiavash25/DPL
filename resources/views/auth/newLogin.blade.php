<!DOCTYPE html>
<html lang="en">
<head>
    <title>DPL Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="{{asset('css/fontawesom/css/all.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/animate/animate.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/css-hamburgers/hamburgers.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/animsition/css/animsition.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/select2/select2.min.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/auth/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/auth/main.css')}}">
    <!--===============================================================================================-->

    <style>
        *{
            font-family: SourceSansPro-Bold;
        }
        .invalidInput{
            border: solid 1px #dc3545;
            margin-bottom: 0px;
        }
        .invalid-feedback{
            display: block;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>

    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>
    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlBase.css')}}">
        <style>
            *{
                font-family: IRANSans !important;
            }
        </style>
    @endif
</head>
<body>


<div class="container-login100" style="background-image: url('{{asset("images/107592.jpg")}}');">
    <div class="wrap-login100 p-l-55 p-r-55 p-t-40 p-b-30">
        <a href="{{url('/')}}" class="m-b-15" style="display: flex; justify-content: center; align-items: center; height: 40px">
            <img src="{{asset('images/mainImage/dplIcon.jpg')}}" alt="DPL" style="height: 100%">
        </a>

        <form id="signinForm" class="login100-form" method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="redirectBack" value="{{$redirectBack}}">
            <span class="login100-form-title p-b-37">
                {{__('Sign In')}}
            </span>

            <div class="wrap-input100 m-b-20 @error('email') invalidInput @enderror" data-validate="{{__('Enter email')}}" style="margin-bottom: 0px">
                <input class="input100" type="text" name="email" value="{{ old('email') }}" placeholder="{{__('email')}}">
                <span class="focus-input100"></span>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="wrap-input100 m-b-25" data-validate = "{{__('Enter password')}}" style="margin-top: 20px">
                <input class="input100" type="password" name="password" placeholder="{{__('password')}}">
                <span class="focus-input100"></span>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="container-login100-form-btn">
                <button class="login100-form-btn" type="submit">
                    {{__('Sign In')}}
                </button>
            </div>

            <div class="text-center p-t-30 p-b-20">
                <span class="txt1">
                    {{__('Or login with')}}
                </span>
            </div>

            <div class="flex-c p-b-10">
                <a href="#" class="login100-social-item">
                    <img src="{{asset('images/icon-google.png')}}" alt="GOOGLE">
                </a>
            </div>

            <div class="text-center" style="display: flex; justify-content: space-between;">
                <a href="#forget" class="txt2 hov1" onclick="toggleRegister('forget')" style="cursor:pointer;">
                    {{__('Forgot your password?')}}
                </a>
                <a href="#register" class="txt2 hov1" onclick="toggleRegister('register')" style="cursor:pointer;">
                    {{__('Sign Up')}}
                </a>
            </div>
        </form>

        <form id="registerForm" method="POST" action="{{ route('register') }}" class="login100-form validate-form" style="display: none" >
            @csrf
            <span class="login100-form-title p-b-37">
                {{__('Register')}}
            </span>

            <div class="wrap-input100 m-b-20 @error('name') invalidInput @enderror" data-validate="{{__('Enter full name')}}">
                <input class="input100" type="text" name="name" value="{{old('name')}}" placeholder="{{__('Full name')}}">
                <span class="focus-input100"></span>
            </div>
            @error('name')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="wrap-input100 m-b-20 @error('email') invalidInput @enderror" data-validate="{{__('Enter email')}}">
                <input class="input100" type="text" name="email" value="{{old('email')}}" placeholder="{{__('email')}}">
                <span class="focus-input100"></span>
            </div>
            @error('email')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="wrap-input100 m-b-25 @error('password') invalidInput @enderror" data-validate = "{{__('Enter password')}}">
                <input class="input100" type="password" name="password" placeholder="{{__('password')}}">
                <span class="focus-input100"></span>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="wrap-input100 m-b-25 @error('password_confirmation') invalidInput @enderror" data-validate = "{{__('Enter password_confirmation')}}">
                <input class="input100" type="password" name="password_confirmation" placeholder="{{__('re-password')}}">
                <span class="focus-input100"></span>
            </div>
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="container-login100-form-btn">
                <button class="login100-form-btn">
                    {{__('Register')}}
                </button>
            </div>

            <div class="text-center" style="margin-top: 30px">
                <a href="#singin" class="txt2 hov1" onclick="toggleRegister('signin')" style="cursor:pointer;">
                    {{__('Sign In')}}
                </a>
            </div>
        </form>

        <div id="forgetPassword" style="display: none">
            <span class="login100-form-title p-b-37" style="font-size: 25px; padding-bottom: 25px">
                {{__('Forgot your password?')}}
            </span>
            <div style="text-align: center; color: #2c3e50;">
                <div style="font-size: 20px; opacity: .5;">
                    {{__("No problem. We're here to help.")}}
                </div>
                <div style="margin-top: 5px; margin-bottom: 15px;">
                   {{__(" Enter your email and we'll send you an email have a new password.")}}
                </div>
            </div>

            <div class="wrap-input100 m-b-20 " data-validate="{{__('Enter email')}}" style="margin-bottom: 0px">
                <input class="input100" type="text" id="forgetEmail" placeholder="{{__('email')}}">
                <span class="focus-input100"></span>
            </div>
            <span id="wrongEmail" class="invalid-feedback errorForget" role="alert" style="display: none">
                {{__('This email not registered')}}
            </span>
            <span id="wrongTime" class="invalid-feedback errorForget" role="alert" style="display: none">
                {{__('auth.forgetTime')}}
            </span>

            <div class="container-login100-form-btn" style="margin: 25px 0px">
                <button class="login100-form-btn" onclick="sendNewPassword()">
                    {{__('Submit')}}
                </button>
            </div>

            <div class="text-center">
                <a href="#signin" class="txt2 hov1" onclick="toggleRegister('signin')" style="cursor:pointer;">
                    {{__('Sign In')}}
                </a>
            </div>
        </div>

        <div id="submitForgetPassword" style="display: none">
            <span class="login100-form-title p-b-37" style="font-size: 25px; padding-bottom: 25px">
                {{__('Forgot your password?')}}
            </span>
            <div style="text-align: center; color: #2c3e50;font-size: 20px;">
               {{__("Please, check your email")}}
                <div style="opacity: .6; font-size: 15px; margin-top: 10px; margin-bottom: 20px;">
                    {{__('auth.successSendEmailText')}}
                </div>
            </div>

            <div class="text-center">
                <a href="#signin" class="txt2 hov1" onclick="toggleRegister('signin')" style="cursor:pointer;">
                    {{__('Sign In')}}
                </a>
            </div>
        </div>
    </div>
</div>



<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
<script src="{{asset('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('vendor/bootstrap/js/popper.js')}}"></script>
<script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('vendor/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
<script src="{{asset('js/pages/auth/main.js')}}"></script>

<script>
    let url = new URL(location.href);
    function toggleRegister(_kind){
        $('#signinForm').hide();
        $('#registerForm').hide();
        $('#forgetPassword').hide();
        $('#submitForgetPassword').hide();

        if(_kind == 'register')
            $('#registerForm').show();
        else if(_kind == 'signin')
            $('#signinForm').show();
        else if(_kind == 'forget')
            $('#forgetPassword').show();
        else if(_kind == 'submitForget')
            $('#submitForgetPassword').show();
    }

    if(url.hash == '#register') {
        toggleRegister('register');
        $('#signinForm').find('.invalid-feedback').remove();
        $('#signinForm').find('.invalidInput').removeClass('invalidInput');
    }
    else{
        $('#registerForm').find('.invalid-feedback').remove();
        $('#registerForm').find('.invalidInput').removeClass('invalidInput');
    }

    function sendNewPassword(){
        let email = $('#forgetEmail').val();
        if(email.trim().length > 5){
            $.ajax({
                type: 'post',
                url: '{{route('forgetPassword')}}',
                data:{
                    _token: '{{csrf_token()}}',
                    email: email,
                },
                success: function(response){
                    $('#forgetEmail').parent().removeClass('invalidInput');
                    $('.errorForget').hide();

                    if(response == 'ok')
                        toggleRegister('submitForget');
                    else if(response == 'notUser'){
                        $('#forgetEmail').parent().addClass('invalidInput');
                        $('#wrongEmail').show();
                    }
                    else if(response == 'beforeSent')
                        $('#wrongTime').show();

                }
            })
        }
    }

</script>


</body>
</html>
