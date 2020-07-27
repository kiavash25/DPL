@extends('profile.layout.profileLayout')

@section('head')
    <style>
        .inputLabel{
            color: #235a79;
            font-size: 15px;
            margin-bottom: 0px;
        }
        .imgSection{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: gray;
        }
        .uploadImgSection{
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            position: relative;
            background: #30759d;
        }
        .uploadText{
            margin-top: 19px;
            font-size: 12px;
            width: 50%;
        }
        .overUserImg{
            position: absolute;
            color: white;
            font-size: 40px;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border-radius: 50%;
            margin-bottom: 0px;
        }
        .submitButton{
            border: none;
            background-color: #30759d;
            color: white;
            padding: 5px 25px;
            border-radius: 9px;
            margin: 0px auto;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 30px">
        <div class="col-md-12" style="display: flex; align-items: center;">
            <h2>
                {{__('Account Settings')}}
            </h2>
        </div>
        <hr>

        <div class="col-md-6">
            <div class="row form-group">
                <label for="name" class="inputLabel">{{__('Full name')}}</label>
                <input type="text" id="name" class="form-control importantInput"  value="{{$user->name}}">
                <div class="errorImportantInput"></div>
            </div>

            <div class="row form-group">
                <label for="email" class="inputLabel">{{__('Email')}}</label>
                <input type="text" id="email" class="form-control importantInput" value="{{$user->email}}">
                <div class="errorImportantInput"></div>
            </div>

            <div class="row form-group">
                <label for="phone" class="inputLabel">{{__('Phone number')}}</label>
                <input type="text" id="phone" class="form-control" value="{{$user->phone}}">
            </div>

            <div class="row form-group">
                <label for="country" class="inputLabel">{{__('Country')}}</label>
                <select id="country" class="form-control">
                    <option value="0"></option>
                    @foreach($country as $item)
                        <option value="{{$item->id}}" {{$user->countryId == $item->id ? 'selected' : ''}}>{{$item->countryName}}</option>
                    @endforeach
                </select>
            </div>

            <div class="row form-group" style="display:block;">
                <label for="birthDate" class="inputLabel">{{__('Date of birth')}}</label>
                <div style="display: flex">
                    <select id="day" name="day" class="form-control" style="border-radius: .25rem 0px 0px .25rem;">
                        <option value="00">{{__('Select day...')}}</option>
                        @for($i = 1; $i < 32; $i++)
                            <option value="{{$i < 10 ? '0'.$i : $i}}" {{isset($user->day) && $user->day == ($i < 10 ? '0'.$i : $i) ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                    </select>
                    <select id="month" name="month" class="form-control" style="border-radius: 0px">
                        <option value="00">{{__('Select month...')}}</option>
                        @for($i = 1; $i < 13; $i++)
                            <option value="{{$i < 10 ? '0'.$i : $i}}" {{isset($user->month) && $user->month == ($i < 10 ? '0'.$i : $i) ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                    </select>
                    <select id="year" name="year" class="form-control" style="border-radius:0px .25rem .25rem 0px;">
                        <option value="0000">{{__('Select year...')}}</option>
                        @for($i = \Illuminate\Support\Carbon::now()->year; $i >= 1920 ; $i--)
                            <option value="{{$i}}" {{isset($user->year) && $user->year == $i ? 'selected' : ''}}>{{$i}}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="row form-group">
                <label for="gender" class="inputLabel">{{__('Gender')}}</label>
                <select id="gender" name="gender" class="form-control">
                    <option value="-1"></option>
                    <option value="1" {{$user->gender === 1 ? 'selected' : ''}}>{{__('Male')}}</option>
                    <option value="0" {{$user->gender === 0 ? 'selected' : ''}}>{{__('Female')}}</option>
                </select>
            </div>

            <div class="row">
                <div class="submitButton" onclick="storeMainData(this)">
                    <span>{{__('Save change')}}</span>
                    <i class="fas fa-cog rotating" aria-hidden="true" style="display: none;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 imgSection">
            <div class="uploadImgSection">
                <img id="userImg" src="{{$user->pic}}" style="height: 100%; display: {{$user->pic != null ? 'block' : 'none'}}">
                <label for="newImg" class="overUserImg">
                    <i id="selectImg" class="fa fa-camera" aria-hidden="true" style="opacity: .8;"></i>
                    <i id="loadingImg" class="fas fa-cog rotating" aria-hidden="true" style="display: none;"></i>
                </label>
                <input type="file" id="newImg" accept="image/*" style="display: none" onchange="showPics(this, '', uploadImg)">
            </div>
            <div class="uploadText">
                {{__('profile.uploadImg')}}
            </div>
        </div>

    </div>


    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12" style="display: flex; align-items: center;">
            <h2>
                {{__('Login & Security')}}
            </h2>
        </div>
        <hr>

        <div class="col-md-6">
            <div class="row form-group">
                <label for="password" class="inputLabel">{{__('New password')}}</label>
                <input type="password" id="password" class="form-control">
                <div class="errorImportantInput"></div>
            </div>

            <div class="row form-group">
                <label for="confirmPassword" class="inputLabel">{{__('Confirm password')}}</label>
                <input type="password" id="confirmPassword" class="form-control">
                <div class="errorImportantInput"></div>
            </div>

            <div class="row" style="flex-direction: column">
                <div style="color: green; text-align: center; margin-bottom: 5px; display: none">{{__('Password Changed')}}</div>
                <div class="submitButton" onclick="storePassword(this)">
                    <span>{{__('Save change')}}</span>
                    <i class="fas fa-cog rotating" aria-hidden="true" style="display: none;"></i>
                </div>
            </div>
        </div>


    </div>

@endsection

@section('script')
    <script>
        function uploadImg(_pic){
            $('#selectImg').hide();
            $('#loadingImg').show();

            var data = new FormData();
            data.append('_token', '{{csrf_token()}}');
            data.append('pic', _pic);
            $.ajax({
                type: 'post',
                url: '{{route("profile.setting.uploadProfilePic")}}',
                data: data,
                processData: false,
                contentType: false,
                success: function(response){
                    $('#selectImg').show();
                    $('#loadingImg').hide();
                    try{
                        response = JSON.parse(response);
                        if(response.status == 'ok') {
                            $('#userImg').show();
                            $('#userImg').attr('src', response.result);
                        }
                    }
                    catch (e) {

                    }
                },
                error: function(err){
                    $('#selectImg').show();
                    $('#loadingImg').hide();
                }
            })
        }

        function storeMainData(_element){
            let name = $('#name').val();
            let email = $('#email').val();
            let phone = $('#phone').val();
            let country = $('#country').val();
            let gender = $('#gender').val();
            let day = $('#day').val();
            let month = $('#month').val();
            let year = $('#year').val();

            if(name.trim().length > 2 && email.trim().length > 5){
                let birthDate = year + '-' + month + '-' + day;
                $(_element).find('i').show();
                $(_element).find('span').hide();
                $.ajax({
                    type: 'post',
                    url: '{{route("profile.setting.updateProfileInfo")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        email: email,
                        phone: phone,
                        country: country,
                        gender: gender,
                        birthDate: birthDate,
                    },
                    success: function(response){
                        $(_element).find('i').hide();
                        $(_element).find('span').show();
                        try{
                            response = JSON.parse(response);
                            if(response.status == 'email'){
                                $('#email').addClass('invalidInput');
                                $('#email').parent().find('.errorImportantInput').text('{{__("profile.duplicateEmail")}}');
                            }
                        }
                        catch (e) {
                            location.reload();
                        }
                    },
                    error: function(err){
                        location.reload();
                    }
                })
            }
        }

        function storePassword(_element){
            let password = $('#password').val();
            let confirm = $('#confirmPassword').val();
            if(password.trim().length > 5) {
                $('#password').parent().find('.errorImportantInput').text('');
                if (password == confirm) {
                    $('#confirmPassword').parent().find('.errorImportantInput').text('');
                    $(_element).find('i').show();
                    $(_element).find('span').hide();

                    $.ajax({
                        type: 'post',
                        url: '{{route('profile.setting.updateProfilePassword')}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            password: password,
                            confirm: confirm
                        },
                        success: function(response){
                            $(_element).find('i').hide();
                            $(_element).find('span').show();

                            if(response == 'ok'){
                                $(_element).prev().show();
                                setTimeout(() => $(_element).prev().hide(), 2000);
                                $('#password').val('');
                                $('#confirmPassword').val('');
                            }
                        },
                        error: function(err){
                            $(_element).find('i').hide();
                            $(_element).find('span').show();
                        }
                    })
                }
                else
                    $('#confirmPassword').parent().find('.errorImportantInput').text('{{__('profile.notSamePassword')}}');
            }
            else
                $('#password').parent().find('.errorImportantInput').text('{{__('profile.lessPassword')}}');
        }

    </script>
@endsection

