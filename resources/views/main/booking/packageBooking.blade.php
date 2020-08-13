@extends('layouts.base')

@section('head')

    <link rel="stylesheet" href="{{asset('css/calendar/lightpick.css')}}">
    <link rel="stylesheet" href="{{asset('css/pages/booking.css')}}">

    <style>
        .reviewDateSection{
            width: 100%;
            border: solid 1px #e2e2e2;
            border-radius: 5px;
            display: flex;

        }
        .reviewDateSection .dateSection{
            position: relative;
            width: 30%;
            min-height: 50px;
            padding: 10px;
        }

        .reviewDateSection .dateTextSection{
            width: 70%;
            border-left: solid 1px #e2e2e2;
            padding: 10px;
        }
        .reviewDateSection .dateTextSection .calendarIcon{
            background-image: url("{{asset('images/mainImage/calendar.svg')}}");
            background-position: center;
            background-size: cover;
            width: 20px;
            height: 20px;
            margin-top: 5px;
        }
        .reviewDateSection .dateTextSection .radeIcon{
            background-image: url("{{asset('images/mainImage/rade.svg')}}");
            background-position: center;
            background-size: cover;
            width: 20px;
            height: 20px;
            margin-top: 5px;
        }
        .reviewDateSection .dateTextSection .row{
            width: 100%;
            margin: 10px 0px;
            padding: 0px;
        }
        .reviewDateSection .dateTextSection .text{
            margin-left: 10px;
            margin-bottom: 20px;
        }
    </style>
@endsection


@section('body')
    <div class="mainBody">

        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    @if($package->freeTime == 1)
                        <div class="sections mainContentSection" style="margin-bottom: 20px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="header">
                                    {{__('Review your dates')}}
                                </div>
                                <div class="body" style="width: 100%">
                                    <div>
                                        <input type="hidden" id="sDateInput" value="0">
                                        <input type="hidden" id="eDateInput" value="0">

                                        <div class="reviewDateSection">
                                            <div class="dateSection">
                                                <div>
                                                    <div id="sDateText" style="font-weight: bold;"></div>
                                                    <div id="eDateText"></div>
                                                </div>
                                                <label for="chooseDate" style="color: #4a82ff; cursor: pointer">{{__('Choose date')}}</label>
                                                <input type="text" id="chooseDate" class="form-control form-control-sm" style="display: none" readonly/>
                                            </div>
                                            <div class="dateTextSection">
                                                <div class="row">
                                                    <div class="calendarIcon"></div>
                                                    <div class="text">
                                                        <div style="font-weight: bold">{{__('Book With Flexibility')}}</div>
                                                        To customize your tour, contact
                                                        <a href="mailto:info@discoverpersianland.com">info@discoverpersianland.com</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="radeIcon"></div>
                                                    <div class="text">
                                                        <div style="font-weight: bold">{{__('Instant Book')}}</div>
                                                        Your spaces will be instantly secured.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="sections mainContentSection">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="header">
                                    {{__('Traveller details')}}
                                </div>
                                <div class="body">
                                    <div>
                                        <div class="inputHeader bordBot">
                                            {{__('Address information')}}
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="inputLabel">{{__('Address')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="address" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="postalCode" class="inputLabel">{{__('Postal or Zip Code')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="postalCode" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="inputHeader bordBot">
                                            {{__('Lead Traveller')}}
                                        </div>
                                        <div class="form-group">
                                            <label for="firstName_0" class="inputLabel">{{__('First name')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="firstName_0" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName_0" class="inputLabel">{{__('Last name')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="lastName_0" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="inputLabel">{{__('Email')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="email" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="inputLabel">{{__('Phone')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="phone" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="natId_0" class="inputLabel">{{__('National Id / Passport Id')}}* :</label>
                                            <div class="inputWithError">
                                                <input type="text" id="natId_0" class="form-control importantInput" value="">
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="country_0" class="inputLabel">{{__('Country')}}* :</label>
                                            <div class="inputWithError">
                                                <select id="country_0" class="form-control importantInputSelect">
                                                    <option value="0"></option>
                                                    @foreach($country as $item)
                                                        <option value="{{$item->id}}">{{$item->countryName}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group radioSection">
                                            <label for="gender_0" class="inputLabel">{{__('Gender')}}* :</label>
                                            <div class="inputWithError">
                                                <div>
                                                    <label for="leadGenderM">Male</label>
                                                    <input type="radio" name="gender_0" id="leadGenderM" class="form-control importantInput" value="male">
                                                </div>

                                                <div>
                                                    <label for="leadGenderF">Female</label>
                                                    <input type="radio" name="gender_0" id="leadGenderF" class="form-control importantInput" value="female">
                                                </div>
                                                <div class="errorImportantInput"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="birthDate" class="inputLabel">{{__('Date of birth')}} :</label>
                                            <div class="birthDateDiv" style="display: flex">
                                                <select id="day_0" name="day_0" class="form-control importantInputSelect" style="border-radius: .25rem 0px 0px .25rem;">
                                                    <option value="0">{{__('Day')}}</option>
                                                    @for($i = 1; $i < 32; $i++)
                                                        <option value="{{$i < 10 ? '0'.$i : $i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <select id="month_0" name="month_0" class="form-control importantInputSelect" style="border-radius: 0px">
                                                    <option value="0">{{__('Month')}}</option>
                                                    @for($i = 1; $i < 13; $i++)
                                                        <option value="{{$i < 10 ? '0'.$i : $i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <select id="year_0" name="year_0" class="form-control importantInputSelect" style="border-radius:0px .25rem .25rem 0px;">
                                                    <option value="0">{{__('Year')}}</option>
                                                    @for($i = \Illuminate\Support\Carbon::now()->year; $i >= 1920 ; $i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                       <div class="form-group">
                                           <label class="inputLabel">{{__("National Image")}}* :</label>
                                           <div class="inputWithError picSection">
                                               <input type="file" id="natPic_0" class="form-control importantInput" onchange="showPics(this, 0)" style="display: none">
                                               <label for="natPic_0" style="cursor: pointer">
                                                   <img id="natImgTag_0" src="{{asset('images/mainImage/nationalPic.png')}}" style="width: 200px;">
                                               </label>
                                           </div>
                                       </div>
                                    </div>

                                    <div>
                                        <div class="inputHeader bordBot">
                                            {{__('Other Traveller')}}
                                        </div>
                                        <div id="travellerInfos"></div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <div id="errorSection" style="color: red; display: none">
                                        {{__('error.fillAll')}}
                                    </div>
                                    <div id="errorPicSection" style="color: red;display: none">
                                        {{__('Please set national images')}}
                                    </div>
                                    <div id="errorDateSection" style="color: red;display: none">
                                        {{__('error.chooseDate')}}
                                    </div>
                                    <button class="bookButton" onclick="register()">{{__('Book')}}</button>
                                    <button class="bookButton" onclick="fillData()">{{__('Fill')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4">
{{--                    sticky--}}
                    <div class="sections  sideInfosSection">
                        <div class="infos">
                            <div class="name">
                                {{$package->name}}
                                <span>
                                    ({{$package->destination}})
                                </span>
                            </div>
                            <div class="day">{{$package->day}} {{__('days')}}</div>
                            <div class="title">{{__('Code')}} : <span class="content">{{$package->code}}</span></div>
                            <div class="title">{{__('Special name')}} : <span class="content">{{$package->specialName}}</span></div>
                            @if($package->capacity != -1)
                                <div class="title">{{__('Capacity')}} : <span id="capacityContent" class="content">{{$package->capacity}}</span></div>
                            @endif
                            <div class="title">{{__('Start date')}} : <span class="content">{{$package->sDate}}</span></div>
                            <div class="title">{{__('End date')}} : <span class="content">{{$package->eDate}}</span></div>
                            @if(isset($package->brochure) && $package->brochure != null)
                                <a href="{{$package->brochure}}" target="_blank" class="brochure">{{__('get brochure')}}</a>
                            @endif
                        </div>
                        <div class="travellerCount">
                            <span class="title">
                                Number of travellers
                            </span>
                            <span class="counter">
                                <div class="button" onclick="increaseTraveller(1)">+</div>
                                <div class="travellerCounter">1</div>
                                <div class="button" onclick="increaseTraveller(-1)">-</div>
                            </span>
                        </div>
                        <div class="totalCount">
                            <div class="title">{{__('Total cost')}} : </div>
                            <div id="cost" class="cost">{{$package->money}} {{$currencySymbol}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')
    <script src="{{asset('js/calendar/moment.min.js')}}"></script>
    <script src="{{asset('js/calendar/lightpick.js')}}"></script>
    <script>

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

        $('.importantInputSelect').on('change', function () {
            if(this.value == '0') {
                $(this).addClass('invalidInput');
                $(this).parent().find('.errorImportantInput').text('{{__('This field is required')}}')
            }
            else {
                $(this).removeClass('invalidInput');
                $(this).parent().find('.errorImportantInput').text('')
            }
        });

        let nowYear = {{\Illuminate\Support\Carbon::now()->year}};
        let countries = {!! $country !!};
        let capacity = {{$package->capacity}};
        let cost = {{$package->money}};
        let packageId = {{$package->id}};
        let freeTime = {{$package->freeTime}};
        let travellerCount = 1;
        let travellerInfos = [];
        let leaderId = 0;
        let travellerData = [];

        function increaseTraveller(_kind){
            if(_kind == 1)
                travellerCount++;
            else
                travellerCount--;

            if(travellerCount < 1)
                travellerCount = 1;

            if(capacity != -1 && travellerCount > capacity)
                travellerCount = capacity;

            $('#cost').text((travellerCount * cost) + '{{$currencySymbol}}');

            $('.travellerCounter').text(travellerCount);
            createTravellerHtml();
        }

        function createTravellerHtml(){
            text = '';
            for(let i = 1; i < travellerCount; i++){
                if(!travellerInfos[i])
                    travellerInfos[i] = {
                        'lastName': '',
                        'firstName': '',
                        'natId': '',
                        'pic': "{{asset('images/mainImage/nationalPic.png')}}",
                        'country': 0,
                    };


                text += '<div id="traveller_' + i + '" class="bordBot" style="margin-bottom: 20px">\n' +
                        '   <label style="color: gray">Traveller ' + i + '</label>\n' +
                        '   <div class="form-group">\n' +
                        '       <label for="firstName_'+i+'" class="inputLabel">{{__("First name")}}* :</label>\n' +
                        '       <div class="inputWithError">\n' +
                        '           <input type="text" id="firstName_'+i+'" class="form-control importantInput" value="' + travellerInfos[i]["firstName"] + '" onchange="changeTravellerInfos(' + i + ', \'firstName\', this.value)">\n' +
                        '           <div class="errorImportantInput"></div>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '   <div class="form-group">\n' +
                        '       <label for="lastName_'+i+'" class="inputLabel">{{__("Last name")}}* :</label>\n' +
                        '       <div class="inputWithError">\n' +
                        '           <input type="text" id="lastName_'+i+'" class="form-control importantInput" value="' + travellerInfos[i]["lastName"] + '" onchange="changeTravellerInfos(' + i + ', \'lastName\', this.value)">\n' +
                        '           <div class="errorImportantInput"></div>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '   <div class="form-group">\n' +
                        '       <label for="country_'+i+'" class="inputLabel">{{__("Country")}}* :</label>\n' +
                        '       <div class="inputWithError">\n' +
                        '           <select id="country_'+i+'" class="form-control importantInputSelect" onchange="changeTravellerInfos(' + i + ', \'country\', this.value)">\n' +
                        '               <option value="0"></option>\n';

                for(let coun = 0; coun < countries.length; coun++) {
                    let selected = '';
                    if(travellerInfos[i]["country"] == countries[coun].id)
                        selected = 'selected';

                    text += '<option value="' + countries[coun].id + '" ' + selected + '>' + countries[coun].countryName + '</option>\n';
                }

                text += '           </select>\n' +
                        '           <div class="errorImportantInput"></div>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '   <div class="form-group radioSection">\n' +
                        '       <label for="gender_'+i+'" class="inputLabel">{{__("Gender")}}* :</label>\n' +
                        '       <div class="inputWithError">' +
                        '           <div>\n' +
                        '               <label for="genderM_'+i+'">{{__("Male")}}</label>\n' +
                        '               <input type="radio" name="gender_'+i+'" id="genderM_'+i+'" class="form-control importantInput" value="male">\n' +
                        '           </div>\n' +
                        '          <div>\n' +
                        '               <label for="genderF_'+i+'">{{__("Female")}}</label>\n' +
                        '               <input type="radio" name="gender_'+i+'" id="genderF_'+i+'" class="form-control importantInput" value="female">\n' +
                        '           </div>\n' +
                        '           <div class="errorImportantInput"></div>\n' +
                        '       </div>\n' +
                        '    </div>\n' +
                        '    <div class="form-group">\n' +
                        '       <label for="birthDate_'+i+'" class="inputLabel">{{__("Date of birth")}} :</label>\n' +
                        '       <div class="birthDateDiv" style="display: flex">\n' +
                        '           <select id="day_'+i+'" name="day_'+i+'" class="form-control importantInputSelect" style="border-radius: .25rem 0px 0px .25rem;">\n' +
                        '               <option value="0">{{__("Day")}}</option>\n';
                for(let day = 1; day < 32; day++) {
                    let showDay;
                    if(day < 10)
                        showDay = '0'+day;
                    else
                        showDay = day;
                    text += '<option value="' + showDay + '">' + day + '</option>';
                }
                text += '           </select>\n' +
                        '           <select id="month_'+i+'" name="month_'+i+'" class="form-control importantInputSelect" style="border-radius: 0px">\n' +
                        '               <option value="0">{{__("Month")}}</option>\n';
                for(let month = 1; month < 13; month++){
                    let showMont;
                    if(month < 10)
                        showMonth = '0'+month;
                    else
                        showMonth = month;
                    text += '<option value="' + showMonth + '">' + month + '</option>';
                }
                text += '           </select>\n' +
                        '           <select id="year_'+i+'" name="year_'+i+'" class="form-control importantInputSelect" style="border-radius:0px .25rem .25rem 0px;">\n' +
                        '               <option value="0">{{__("Year")}}</option>\n';
                for(let yea = nowYear; yea >= 1920; yea--)
                    text += '<option value="' + yea + '">' + yea + '</option>';

                text += '           </select>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '   <div class="form-group">\n' +
                        '       <label for="natId_'+i+'" class="inputLabel">{{__("National Id / Passport Id")}}* :</label>\n' +
                        '       <div class="inputWithError">\n' +
                        '           <input type="text" id="natId_'+i+'" class="form-control importantInput" value="' + travellerInfos[i]["natId"] + '" onchange="changeTravellerInfos(' + i + ', \'natId\', this.value)">\n' +
                        '           <div class="errorImportantInput"></div>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '   <div class="form-group">\n' +
                        '       <label class="inputLabel">{{__("National Image")}}* :</label>\n' +
                        '       <div class="inputWithError picSection">\n' +
                        '           <input type="file" id="natPic_'+i+'" class="form-control importantInput" onchange="showPics(this, ' + i + ')" style="display: none">\n' +
                        '           <label for="natPic_'+i+'" style="cursor: pointer"><img id="natImgTag_'+i+'" src="' + travellerInfos[i]["pic"] + '" style="width: 200px;"></label>'+
                        '       </div>\n' +
                        '   </div>\n' +
                        '</div>\n';

            }

            $('#travellerInfos').html(text);
        }

        function changeTravellerInfos(_index, _kind, _value){
            travellerInfos[_index][_kind] = _value;
        }

        function showPics(_input, _index){
            if(_input.files && _input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e) {
                    var pic = e.target.result;
                    $('#natImgTag_' + _index).attr('src', pic);
                    travellerInfos[_index]['picSrc'] = _input.files[0];
                    travellerInfos[_index]['pic'] = pic;
                };
                reader.readAsDataURL(_input.files[0]);
            }
        }

        function register(){
            let error = false;
            let errorPic = false;
            let errorDate = false;
            let address = $('#address').val();
            let postalCode = $('#postalCode').val();
            let email = $('#email').val();
            let phone = $('#phone').val();

            let sDate = $('#sDateInput').val();
            let eDate = $('#eDateInput').val();

            if(freeTime == 1 && (sDate == 0 && eDate == 0)){
                errorDate = true;
            }
            if(address.trim().length < 3) {
                $('#address').addClass('invalidInput');
                error = true;
            }
            if(postalCode.trim().length < 3) {
                $('#postalCode').addClass('invalidInput');
                error = true;
            }
            if(email.trim().length < 3) {
                $('#email').addClass('invalidInput');
                error = true;
            }
            if(phone.trim().length < 3) {
                $('#phone').addClass('invalidInput');
                error = true;
            }

            for(let i = 0; i < travellerCount; i++) {
                let d = {
                    'firstName' : $('#firstName_'+i).val(),
                    'lastName' : $('#lastName_'+i).val(),
                    'natId' : $('#natId_'+i).val(),
                    'country' : $('#country_'+i).val(),
                    'gender' : $('input[name="gender_'+i+'"]:checked').val(),
                    'day' : $('#day_'+i).val(),
                    'month' : $('#month_'+i).val(),
                    'year' : $('#year_'+i).val(),
                }

                if(d.firstName.trim().length < 2){
                    $('#firstName_'+i).addClass('invalidInput');
                    error = true;
                }
                if(d.lastName.trim().length < 2){
                    $('#lastName_'+i).addClass('invalidInput');
                    error = true;
                }
                if(d.natId.trim().length < 2){
                    $('#natId_'+i).addClass('invalidInput');
                    error = true;
                }
                if(d.country == 0){
                    $('#country_'+i).addClass('invalidInput');
                    error = true;
                }
                if(d.gender == undefined){
                    $('input[name="gender_'+i+'"]').addClass('invalidInput');
                    error = true;
                }
                if(d.day == '0'){
                    $('#day_'+i).addClass('invalidInput');
                    error = true;
                }
                if(d.month == '0'){
                    $('#month_'+i).addClass('invalidInput');
                    error = true;
                }
                if(d.year == '0'){
                    $('#year_'+i).addClass('invalidInput');
                    error = true;
                }

                let pic = $('#natPic_'+i);
                if(pic[0].files && pic[0].files[0])
                    d.pic = pic[0].files[0];
                else
                    errorPic = true;

                travellerData.push(d);
            }

            $('#errorSection').hide();
            $('#errorPicSection').hide();
            $('#errorDateSection').hide();

            if(error || errorPic || errorDate){
                if(errorPic)
                    $('#errorPicSection').show();
                if(error)
                    $('#errorSection').show();
                if(errorDate)
                    $('#errorDateSection').show();
                return;
            }
            else{
                openLoading();
                sendTravellerData(0);
            }
        }

        function sendTravellerData(i){
            let formData = new FormData();
            formData.append('_token', "{{csrf_token()}}");
            formData.append('firstName', travellerData[i].firstName);
            formData.append('lastName', travellerData[i].lastName);
            formData.append('natId', travellerData[i].natId);
            formData.append('country', travellerData[i].country);
            formData.append('gender', travellerData[i].gender);
            formData.append('day', travellerData[i].day);
            formData.append('month', travellerData[i].month);
            formData.append('year', travellerData[i].year);
            formData.append('natPic', travellerData[i].pic);
            formData.append('leaderId', leaderId);
            formData.append('packageId', packageId);
            formData.append('sDate', $('#sDateInput').val());
            formData.append('eDate', $('#eDateInput').val());

            if(leaderId == 0){
                formData.append('address', $('#address').val());
                formData.append('postalCode', $('#postalCode').val());
                formData.append('email', $('#email').val());
                formData.append('phone', $('#phone').val());
            }

            $.ajax({
                type: 'post',
                url: '{{route("book.package.store")}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    response = JSON.parse(response);
                    if(response.status == 'ok'){
                        leaderId = response.leaderId;
                        if(i == travellerData.length - 1){
                            alert('go to payment');
                            location.href = '{{url("/")}}';
                        }
                        else
                            sendTravellerData(i+1);
                    }
                    else{
                        deleteStoredBooking();
                        resultLoading('{{__("error.tryAgain")}}', 'danger');
                    }
                },
                error: function(err){
                    deleteStoredBooking();
                    resultLoading('{{__("error.tryAgain")}}', 'danger');
                }
            })
        }

        function fillData(){
            $('#address').val('iran, tehran, motahary street');
            $('#postalCode').val('839274442');
            $('#email').val('kiavashriddler@gmail.com');
            $('#phone').val('09122474393');

            for(let i = 0; i < travellerCount; i++){
                $('#firstName_'+i).val('First_Name_' + i);
                $('#lastName_'+i).val('Last_Name_' + i);
                $('#natId_'+i).val('NATIONAL_ID_' + i);
                $('#country_'+i).val(13);
                $('#day_'+i).val(21);
                $('#month_'+i).val('04');
                $('#year_'+i).val(1990);
            }
        }

        function deleteStoredBooking(){
            if(leaderId != 0){
                $.ajax({
                    type: 'post',
                    url : '{{route("book.package.delete")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        leaderId: leaderId
                    },
                    success: function(response){
                        console.log(response);
                        leaderId = 0;
                    },
                    error: function(err){
                        console.log(err);
                        leaderId = 0;
                    }
                })
            }
        }

    </script>


    @if($package->freeTime == 1)
        <script>
            let day = {{$package->day}};
            let sDate = '{{$package->sDate}}';
            let eDate = '{{$package->eDate}}';

            function trueDateFormat(start){
                let showStart = start.format('YYYY-MM-DD');
                let textShowStart = start.format('Do MMMM YYYY');

                let addStart = start.add(day, 'days');
                let showEnd = addStart.format('YYYY-MM-DD');
                let textShowEnd = addStart.format('Do MMMM YYYY');

                return [showStart, showEnd, textShowStart, textShowEnd];
            }

            new Lightpick({
                field: document.getElementById('chooseDate'),
                // secondField: document.getElementById('chooseDateE'),
                // repick: true,
                // minDays: day,
                // maxDays: day,
                // singleDate: false,

                singleDate: true,
                minDate: moment(sDate),
                maxDate: moment(eDate).subtract(day, 'days'),
                onSelect: function(start, end){
                    let text = trueDateFormat(start);

                    $('#sDateInput').val(text[0]);
                    $('#eDateInput').val(text[1]);

                    $('#sDateText').text(text[2]);
                    $('#eDateText').text(text[3]);
                }
            });
        </script>
    @endif

@endsection
