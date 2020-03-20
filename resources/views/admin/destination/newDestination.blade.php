@extends('admin.layout.adminLayout')

@section('head')

    <style>
        hr{
            width: 100%;
        }
        .form-group{
            width: 100%;
        }
        .inputLabel{
            font-size: 23px;
            font-weight: 500;
        }
        .whiteBase{
            background-color: white;
            border-radius: 20px;
            width: 100%;
            margin: 0;
            padding: 15px;
        }
        .marg30{
            margin-top: 30px;
        }
        .closeTagIcon{
            position: absolute;
            color: white;
            top: 8px;
            right: 10px;
            height: 20px;
            font-size: 13px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            background: red;
            width: 20px;
            border-radius: 50%;
        }
        .addTagIcon{
            color: green;
            font-size: 35px;
        }
        .tagSearchResult{
            display: none;
            flex-direction: column;
            position: absolute;
            z-index: 1;
            background: white;
            width: 93%;
            border: solid lightgray 1px;
            border-radius: 4px;
            padding: 10px;
            top: 35px;
        }
        .tagResult{
            padding: 5px;
            font-size: 14px;
            transition: 0.1s;
            color: gray;
            cursor: pointer;
        }
        .tagResult:hover{
            background-color: #30759d;
            color: white;
            border-radius: 8px;
        }
        .mainPicSection{
            overflow: hidden;
            cursor: pointer;
            width: 100%;
            height: 200px;
            border-radius: 10px;
            border: dashed 1px gray;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 90px;
            color: green;
        }
        .uploadedPic{
            margin: 10px 0px;
            position: relative;
            padding: 0px;
            overflow: hidden;
            height: 180px;
            border-radius: 15px;
            display: flex;
        }
        .uploadedPicHover{
            position: absolute;
            top: 0px;
            right: 0px;
            width: 100%;
            height: 0%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #000000d1;
            border-radius: 15px;
            transition: 0.3s;
            overflow: hidden;
        }
        .uploadedPic:hover .uploadedPicHover{
            height: 100%;
        }
        .uploadedPicImg{
            width: 100%;
            border-radius: 15px;
            padding: 0px 10px;
        }
        @media (max-width: 768px){
            .addTagIcon{
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/dropzone.css')}}">

    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone-amd-module.js')}}"></script>
@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                @if($kind == 'new')
                    Create New Destination
                @else
                    Edit {{$destination->name}} Destination
                @endif
            </h2>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">
                <div class="form-group">
                    <label for="name" class="inputLabel">Destination Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Destination Name" value="{{isset($destination->name) ? $destination->name : ''}}">
                </div>
            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">Destination Description</label>
                    <textarea type="text" id="description" name="description" class="form-control" placeholder="Destination Description" rows="6"> {!! isset($destination->description) ? $destination->description : '' !!}</textarea>
                </div>
            </div>

            <div class="row marg30">
                <div class="col-xl-3">
                    <div class="row">
                        <div class="form-group">
                            <label for="country" class="inputLabel">Destination Country</label>

                            <div id="country" class="ui fluid search selection dropdown">
                                <input type="hidden" name="country" id="CountryId" onchange="changeCountry(this.value)" value="{{isset($destination->countryId) ? $destination->countryId : 0}}">
                                <div class="default text">Select Country</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @foreach($countries as $country)
                                        <div class="item" data-value="{{$country->id}}"><i class="{{strtolower($country->code)}} flag"></i>{{$country->name}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row marg30" style="position: relative">
                        <div class="form-group">
                            <label for="city" class="inputLabel">Destination City</label>
                            <input type="text" id="city" name="city" class="form-control" placeholder="Destination City" onkeyup="findCity(this)"onfocus="clearAllSearchResult()" onchange="closeSearch(this)" value="{{isset($destination->city) ? $destination->city : ''}}">
                            <input type="hidden" id="cityId" name="cityId" value="{{isset($destination->cityId) ? $destination->cityId : 0}}">
                        </div>
                        <div class="tagSearchResult" style="width: 100%; top: 65px"></div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="map" style="background-color: red; height: 400px; display: flex; justify-content: center; align-items: center; border-radius: 10px"></div>
                    <input type="hidden" id="lat" name="lat" value="{{isset($destination->lat) ? $destination->lat : 0}}">
                    <input type="hidden" id="lng" name="lng" value="{{isset($destination->lng) ? $destination->lng : 0}}">
                </div>
            </div>

            <div class="row marg30">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="inputLabel"> Destination Tags</label>
                    </div>
                </div>
                <div class="row" style="width: 100%">
                    @if(isset($destination->tags) && count($destination->tags) != 0)
                        @for($i = 0; $i < count($destination->tags); $i++)
                            <div class="col-lg-3 col-md-4">
                                <div class="form-group" style="position: relative">
                                    <input type="text" name="tags[]" class="form-control" placeholder="Tag" onkeyup="findTag(this)"onfocus="clearAllSearchResult()" onchange="closeSearch(this)" value="{{$destination->tags[$i]}}">
                                    <div class="closeTagIcon" onclick="deleteTag(this)">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>

                                <div class="tagSearchResult"></div>
                            </div>
                        @endfor
                    @else
                        @for($i = 0; $i < 5; $i++)
                            <div class="col-lg-3 col-md-4">
                                <div class="form-group" style="position: relative">
                                    <input type="text" name="tags[]" class="form-control" placeholder="Tag" onkeyup="findTag(this)"onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)">
                                    <div class="closeTagIcon" onclick="deleteTag(this)">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>

                                <div class="tagSearchResult"></div>
                            </div>
                        @endfor
                    @endif
                    <div id="addNewTag" class="col-lg-2 col-md-2">
                        <div class="addTagIcon">
                            <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addTag()"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row marg30" id="pictureSection" style="display: {{$kind == 'new'? 'none': 'flex'}}">
                <div class="col-md-3 centerContent" style="flex-direction: column; justify-content: end">
                    <label class="inputLabel">
                        Main Picture
                    </label>
                    <label for="mainPic" class="mainPicSection">
                        <img id="mainPicImg" src="{{isset($destination->pic) && $destination->pic != null ? $destination->pic : '#'}}" style="width: 100%; display: {{isset($destination->pic) && $destination->pic != null ? 'block' : 'none'}};" >
                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($destination->pic) && $destination->pic != null ? 'none' : 'block'}};"></i>
                    </label>

                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPic(this, 'mainPic')">
                </div>
                <div class="col-md-9">
                    <div id="uploadedPic" class="row">
                        @if(isset($destination->sidePic) && count($destination->sidePic) > 0)
                            @foreach($destination->sidePic as $item)
                                <div class="col-md-3 uploadedPic">
                                    <img src="{{$item->pic}}" class="uploadedPicImg">
                                    <div class="uploadedPicHover">
                                        <button class="btn btn-danger" onclick="deletePic({{$item->id}}, this)">delete</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-12" style="display: flex; justify-content: center">
                    <button id="uploadPicButton" class="btn btn-primary" style="font-size: 30px; border-radius: 20px;" onclick="uploadPicModal()">Upload Picture</button>
                </div>
            </div>

            <div class="row marg30" style="display: flex; justify-content: center;">
                <button class="btn btn-success" style="font-size: 30px; border-radius: 20px" onclick="submitForm()">Submit</button>
            </div>

        </div>

        <div class="modal" id="uploadPic">
            <div class="modal-dialog modal-xl" style="max-width: 1500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Upload Pictures</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="dropzone" class="dropzone"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')

    <script>
        $('#country')
            .dropdown({
                clearable: true,
                placeholder: 'any'
            });

        function deleteTag(_element){
            $(_element).parent().parent().remove();
        }
        function addTag() {
            text = '<div class="col-md-3">\n' +
                '<div class="form-group" style="position: relative">\n' +
                '<input type="text" name="tags[]" class="form-control" placeholder="Tag" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)"> \n' +
                '<div class="closeTagIcon" onclick="deleteTag(this)">\n' +
                '<i class="fas fa-times"></i>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="tagSearchResult"></div>' +
                '</div>';

            $(text).insertBefore($('#addNewTag'));
        }
    </script>


    <script>
        var lat = 33.340562481212146;
        var lng = 54.711382812500005;
        var countries = {!! $countries !!};
        var map;
        var marker = 0;
        var destId = {{isset($destination->id) ? $destination->id : 0}};

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: 2
            });

            google.maps.event.addListener(map, 'click', function(event) {
                getLat(event.latLng);
            });

            @if(isset($destination->lat) && isset($destination->lng))
                map.setZoom(8);
                map.panTo({
                    lat: parseFloat( {{$destination->lat}} ),
                    lng: parseFloat( {{$destination->lng}} )
                });
                marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat( {{$destination->lat}} ),
                        lng: parseFloat( {{$destination->lng}} )
                    },
                    map: map,
                })
            @endif
        }

        function getLat(location){
            if(marker != 0)
                marker.setMap(null);
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            $("#lat").val(marker.getPosition().lat());
            $("#lng").val(marker.getPosition().lng());
        }

        function changeCountry(_value){
            var lat;
            var lng;
            for(i = 0; i < countries.length; i++){
                if(countries[i]['id'] == _value){
                    lat = countries[i]['lat'];
                    lng = countries[i]['lng'];
                    break;
                }
            }
            $('#cityId').val(0);
            $('#city').val('');

            map.setZoom(5);
            map.panTo({
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            });
        }

        function findCity(_element){
            var country = $('#CountryId').val();
            var value = $(_element).val();

            if(value.trim().length != 0 && country != 0){
                $.ajax({
                    type: 'post',
                    url: '{{route("findCity")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        city: value,
                        countryId: country
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){

                            var answers = '';
                            var citites = response[1];
                            for(i = 0; i < citites.length; i++)
                                answers += '<div class="tagResult" onclick="setCity(this)" cityId="' + citites[i]["id"] + '">' + citites[i]["name"] + '</div>';

                            answers += '<div class="tagResult" onclick="newCity(\'' + value + '\')" style="color: blue">Add ' + value + '</div>';

                            $(_element).parent().next().html(answers);
                            if(answers == '')
                                $(_element).parent().next().css('display', 'none');
                            else
                                $(_element).parent().next().css('display', 'flex');
                        }
                    }
                })
            }
            else if(country == 0) {
                alert('Please First Choose Country');
                $(_element).val('');
            }

        }

        function setCity(_element){
            var name = $(_element).text();
            var id = $(_element).attr('cityId');

            $('#cityId').val(id);
            $('#city').val(name);

            $(_element).parent().html('')
            $(_element).parent().css('display', 'none');
        }

        function newCity(_value){
            var country = $('#CountryId').val();

            if(_value.trim().length != 0) {

                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.addCity")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        city: _value,
                        countryId: country
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            resultLoading(_value + ' Added To DataBase', 'success');
                            $('#cityId').val(response[1]);
                            $('#city').val(_value);
                        }
                        else if(response[0] == 'nok2'){
                            resultLoading( _value + ' Exist In DataBase', 'danger');
                            $('#cityId').val(0);
                            $('#city').val('');
                        }

                    },
                    error: function (err) {
                        resultLoading('we have problem', 'danger');
                        $('#cityId').val(0);
                        $('#city').val('');
                    }
                });
            }
        }

        var tagSelected;
        function findTag(_element){
             var value = $(_element).val();

            if(value.trim().length != 0){
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.findTag")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        tag: value
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            tagSelected = _element;
                            var answers = '';
                            var tags = response[1];
                            for(i = 0; i < tags.length; i++)
                                answers += '<div class="tagResult" onclick="setTag(this)">' + tags[i]["tag"] + '</div>';

                            $(_element).parent().next().html(answers);
                            if(answers == '')
                                $(_element).parent().next().css('display', 'none');
                            else
                                $(_element).parent().next().css('display', 'flex');
                        }
                    }
                })
            }
            else{
                $(_element).parent().next().html('');
                $(_element).parent().next().css('display', 'none');
            }

        }

        function setTag(_element){
            var value = $(_element).text();
            $(tagSelected).val(value);

            $(tagSelected).parent().next().html('');
            $(tagSelected).parent().next().css('display', 'none');

            tagSelected = '';
        }

        function clearAllSearchResult(){
            $('.tagSearchResult').html('');
            $('.tagSearchResult').css('display', 'none');
        }

        function closeSearch(_element){
            setTimeout(function () {
                $(_element).parent().next().html('');
                $(_element).parent().next().css('display', 'none');
            }, 100)
        }

        function submitForm(){
             openLoading();

            var name = $('#name').val();
            var description = $('#description').val();
            var lat = $('#lat').val();
            var lng = $('#lng').val();
            var countryId = $('#CountryId').val();
            var cityId = $('#cityId').val();
            var tagsElement = $("input[name*='tags']");
            var tags = [];
            var error = '<ul>';

            for(i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val() != null && $(tagsElement[i]).val().trim().length != 0)
                    tags[tags.length] = $(tagsElement[i]).val();
            }

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(countryId == 0)
                error += '<li> Please Choose Country.</li>';

            if(cityId == 0)
                error += '<li> Please Choose City.</li>';

            if(lat == 0 && lng == 0)
                error += '<li> Please Map Cordination.</li>';


            if(error != '<ul>'){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.store")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        description: description,
                        countryId: countryId,
                        cityId: cityId,
                        lat: lat,
                        lng: lng,
                        tags: JSON.stringify(tags),
                        id: destId
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            destId = response[1];
                            resultLoading('Your Destination Stored', 'success', goToImagePage);
                            $('#pictureSection').css('display', 'flex')
                        }
                        else
                            resultLoading('Please Try Again', 'danger');
                    },
                    error: function(err){
                        resultLoading('Please Try Again', 'danger');
                    }
                })
            }

        }

        var myDropzone = new Dropzone("div#dropzone", {
            url: "{{route('admin.destination.storeImg')}}",
            paramName: "pic",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    formData.append("kind", 'side');
                    formData.append("id", destId);
                });
            },

        }).on('success', function(file, response){

            if(response['status'] == 'ok'){
                var text =  '<div class="col-md-3 uploadedPic">\n' +
                            '<img src="' + file['dataURL'] + '" class="uploadedPicImg">\n' +
                            '<div class="uploadedPicHover">\n' +
                            '<button class="btn btn-danger" onclick="deletePic(' + response['id'] + ', this)">delete</button>\n' +
                            '</div>\n' +
                            '</div>';
                $('#uploadedPic').append(text);
            }
        });

        function uploadPicModal(){
            $('#uploadPic').modal('show');
        }

        function goToImagePage(){
            $('#uploadPicButton').css('display', 'block');
        }

        function showPic(_input, _kind){

            if(_input.files && _input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e) {
                    var mainPic = e.target.result;
                    $('#mainPicImg').attr('src', mainPic);

                    $('#mainPicImg').css('display', 'none');
                    $('#mainPicImg').next().css('display', 'block');
                    $('#mainPicImg').next().next().css('display', 'none');

                    var data = new FormData();

                    data.append('pic', _input.files[0]);
                    data.append('id', destId);
                    data.append('kind', 'mainPic');
                    data.append('_token', '{{csrf_token()}}');

                    $.ajax({
                        type: 'post',
                        url: '{{route("admin.destination.storeImg")}}',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response){
                            response = JSON.parse(response);
                            if(response[0] == 'ok'){
                                $('#mainPicImg').css('display', 'block');
                                $('#mainPicImg').next().css('display', 'none');
                                $('#mainPicImg').next().next().css('display', 'none');
                            }
                            else{
                                $('#mainPicImg').css('display', 'none');
                                $('#mainPicImg').next().css('display', 'none');
                                $('#mainPicImg').next().next().css('display', 'block');
                            }
                        }
                    })

                };
                reader.readAsDataURL(_input.files[0]);
            }

        }

        function deletePic(_id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.deleteImg")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        $(_element).parent().parent().remove();
                }
            })
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>
@endsection

