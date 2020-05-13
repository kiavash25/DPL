@extends('admin.layout.adminLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/admin/adminAllPages.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/dropzone.css')}}">

    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone-amd-module.js')}}"></script>

    <script src="{{asset('js/ckeditor.js')}}"></script>

    <style>
        .textEditor{
            height: 30vh;
            border: solid 1px var(--ck-color-toolbar-border) !important;
            border-top: none !important;
            border-radius: 5px !important;
        }
        .videoButton{
            cursor: pointer;
            padding: 10px;
            font-size: 13px;
            border-radius: 10px;
            background-color: #30759d;
            color: white;
        }
    </style>

@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12" style="display: flex; align-items: center;">
            <h2>
                @if($kind == 'new')
                    {{__('Create New Destination')}}
                @else
                    {{__('Edit')}} {{$destination->name}} Destination
                @endif
            </h2>

            <div class="form-group" style="width: auto; margin-right: 30px; display: {{app()->getLocale() != 'en' ? 'block': 'none'}}">
                <label for="source">{{__('Source')}}</label>
                <select name="source" id="source" class="form-control" onchange="showPicSection(this.value)">
                    <option value="0" {{isset($destination->langSource) && $destination->langSource == 0 ? 'selected' : ''}}>{{__('New')}}</option>
                    @foreach($sourceParent as $s)
                        <option value="{{$s->id}}" {{isset($destination->langSource) && $destination->langSource == $s->id ? 'selected' : ''}}>{{$s->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">
                <div class="form-group">
                    <label for="name" class="inputLabel">{{__('Destination Name')}}</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Destination Name" value="{{isset($destination->name) ? $destination->name : ''}}">
                </div>
            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">{{__('Destination Description')}}</label>

                    <div class="toolbar-container"></div>
                    <div id="description" class="textEditor" >
                        {!! isset($destination->description) ? $destination->description : '' !!}
                    </div>

                </div>
            </div>

            <div class="row marg30">
                <div class="col-xl-3">

                    <div class="row">
                        <div class="form-group">
                            <label for="category" class="inputLabel">{{__('Destination Category')}}</label>
                            <div id="category" class="ui fluid search selection dropdown">
                                <input type="hidden" name="categoryId" id="categoryId" value="{{isset($destination->categoryId) ? $destination->categoryId : 0}}">
                                <div class="default text">{{__('Select Category')}}</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @foreach($category as $item)
                                        <div class="item" data-value="{{$item->id}}">{{$item->name}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

{{--                    <div class="row marg30" style="position: relative">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="city" class="inputLabel">Destination City (Optional)</label>--}}
{{--                            <input type="text" id="city" name="city" class="form-control" placeholder="Destination City" onkeyup="findCity(this)"onfocus="clearAllSearchResult()" onchange="closeSearch(this)" value="{{isset($destination->city) ? $destination->city : ''}}">--}}
                            <input type="hidden" id="cityId" name="cityId" value="{{isset($destination->cityId) ? $destination->cityId : 0}}">
{{--                        </div>--}}
{{--                        <div class="tagSearchResult" style="width: 100%; top: 65px"></div>--}}
{{--                    </div>--}}
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
                        <label class="inputLabel"> {{__('Destination Tags')}}</label>
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

            <div class="row"></div>

            <div class="row marg30" id="pictureSection">
                <div class="col-md-3 centerContent" style="flex-direction: column; justify-content: end">
                    <label class="inputLabel">
                        {{__('Main Picture')}}
                    </label>
                    <label for="mainPic" class="mainPicSection">
                        <img id="mainPicImg" src="{{isset($destination->pic) && $destination->pic != null ? $destination->pic : '#'}}" style="width: 100%; display: {{isset($destination->pic) && $destination->pic != null ? 'block' : 'none'}};" >
                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($destination->pic) && $destination->pic != null ? 'none' : 'block'}};"></i>
                    </label>

                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPics(this, 'mainPicImg', showMainPic)">
                </div>
                <div class="col-md-9">
                    <div id="uploadedPic" class="row">
                        @if(isset($destination->sidePic) && count($destination->sidePic) > 0)
                            @foreach($destination->sidePic as $item)
                                <div class="col-md-3 uploadedPic">
                                    <img src="{{$item->pic}}" class="uploadedPicImg">
                                    <div class="uploadedPicHover">
                                        <button class="btn btn-danger" onclick="deletePic({{$item->id}}, this)">{{__('Delete')}}</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-12" style="display: flex; justify-content: center">
                    <button id="uploadPicButton" class="btn btn-primary" style="font-size: 30px; border-radius: 20px;" onclick="uploadPicModal()">{{__('Upload Picture')}}</button>
                </div>
            </div>

            <div class="row marg30" id="videoSection" style="display: {{$kind == 'new'? 'none': 'flex'}}">
                <div class="col-md-6" style="margin-top: 40px;">
                    <label class="inputLabel">
                        {{__('Video')}}
                        <label for="video" class="videoButton">
                            {{isset($destination->video) ? 'change' : 'add'}} video
                        </label>
                    </label>
                    <label class="mainPicSection">
                        <video id="videoTag" poster="placeholder.png" preload="none" controls style="width: 100%; height: 100%; display: {{isset($destination->video) ? 'block' : 'none'}} ">
                            <source id="videoSource" src="{{isset($destination->video) ? $destination->video : '#'}}">
                        </video>
                        <img id="videoLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                    </label>

                    <input type="file" name="video" id="video" accept="video/*" style="display: none" onchange="uploadVideo(this)">
                </div>
                <div class="col-md-6" style="margin-top: 40px;">
                    <label class="inputLabel">
                        {{__('podcast')}}
                        <label for="audio" class="videoButton">
                            {{isset($destination->podcast) ? 'change' : 'add'}} podcast
                        </label>
                    </label>
                    <label style="height: 50px; width: 100%;">
                        <audio id="audioTag" preload="none" controls style="width: 100%; height: 100%; display: {{isset($destination->podcast) ? 'block' : 'none'}} ">
                            <source id="audioSource" src="{{isset($destination->podcast) ? $destination->podcast : '#'}}">
                        </audio>
                        <img id="audioLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                    </label>

                    <input type="file" name="audio" id="audio" accept="audio/*" style="display: none" onchange="uploadAudio(this)">
                </div>
            </div>

            <div class="row marg30" style="display: flex; justify-content: center; flex-direction: column; align-items: center">
                <a id="descriptionButton" href="{{isset($destination->id) ? route('admin.destination.description', ['id' => $destination->id]) : ''}}" style="display: {{isset($destination->id) ? 'block' : 'none'}}">
                    <button class="btn btn-warning" style="font-size: 30px; border-radius: 20px;">{{__('Descriptions Page')}}</button>
                </a>

                <button class="btn btn-success" style="font-size: 30px; border-radius: 20px; width: 100%;; margin-top: 20px" onclick="submitForm()">{{__('Submit')}}</button>

            </div>

        </div>

        <div class="modal" id="uploadPic">
            <div class="modal-dialog modal-xl" style="max-width: 1500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Upload Picture')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="dropzone" class="dropzone"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')

    <script>

        DecoupledEditor.create( document.querySelector('#description'), {
            toolbar: [ 'bold', 'italic', 'link' ],
            language: '{{app()->getLocale()}}'
        }).then( editor => {
            const toolbarContainer = document.querySelector( 'main .toolbar-container');
            toolbarContainer.prepend( editor.ui.view.toolbar.element );
            window.editor = editor ;
        } )
            .catch( err => {
                console.error( err.stack );
            } );

        let descriptionButtonUrl = '{{url("/admin/destination/description")}}';

        $('#category')
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
        var lat = 32.427908;
        var lng = 53.688046;
        var countries = {!! $countries !!};
        var map;
        var marker = 0;
        var destId = {{isset($destination->id) ? $destination->id : 0}};

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: 5
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
                    url: '{{route("findTag")}}',
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
            var source = $('#source').val();
            var description = window.editor.getData();
            var lat = $('#lat').val();
            var lng = $('#lng').val();
            var cityId = $('#cityId').val();
            var categoryId = $('#categoryId').val();
            var tagsElement = $("input[name*='tags']");
            var tags = [];
            var error = '<ul>';

            for(i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val() != null && $(tagsElement[i]).val().trim().length != 0)
                    tags[tags.length] = $(tagsElement[i]).val();
            }

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(categoryId == 0)
                error += '<li> Please Choose Category.</li>';

            if(lat == 0 && lng == 0)
                error += '<li> Please select a location from the map.</li>';


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
                        categoryId: categoryId,
                        cityId: cityId,
                        lat: lat,
                        lng: lng,
                        source: source,
                        tags: JSON.stringify(tags),
                        id: destId
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            destId = response[1];
                            resultLoading('Your Destination Stored', 'success', goToImagePage);
                            showPicSection(source);
                            $('#videoSection').css('display', 'flex');

                            $('#descriptionButton').attr('href', descriptionButtonUrl +'/'+ response[1]);
                            $('#descriptionButton').css('display', 'block');
                        }
                        else if(response[0] == 'nok1')
                            resultLoading('Your destination name is duplicate', 'danger');
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
            response = JSON.parse(response);
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

        function showMainPic(_pic){
            var mainPic = _pic;

            $('#mainPicImg').css('display', 'none');
            $('#mainPicImg').next().css('display', 'block');
            $('#mainPicImg').next().next().css('display', 'none');

            var data = new FormData();

            data.append('pic', mainPic);
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

        function uploadVideo(_element){

            if(_element.files && _element.files[0]) {

                $('#videoLoader').css('display', 'block');
                $('#videoTag').css('display', 'none');

                var data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', destId);
                data.append('kind', 'video');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.storeVideoAudio")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        try {
                            response = JSON.parse(response);
                            if (response['status'] == 'ok') {
                                $('#videoLoader').css('display', 'none');
                                $('#videoTag').css('display', 'block');
                                $('#videoSource').attr('src', response['result']);
                            }
                            else {
                                let src = $('#videoSource').attr('src');
                                if(src != '#'){
                                    $('#videoLoader').css('display', 'none');
                                    $('#videoTag').css('display', 'block');
                                }
                                else{
                                    $('#videoLoader').css('display', 'none');
                                    $('#videoTag').css('display', 'none');
                                }
                            }
                        }
                        catch (e) {
                            let src = $('#videoSource').attr('src');
                            if(src != '#'){
                                $('#videoLoader').css('display', 'none');
                                $('#videoTag').css('display', 'block');
                            }
                            else{
                                $('#videoLoader').css('display', 'none');
                                $('#videoTag').css('display', 'none');
                            }
                        }
                    },
                    error: function(err){
                        console.log(err);
                        let src = $('#videoSource').attr('src');
                        if(src != '#'){
                            $('#videoLoader').css('display', 'none');
                            $('#videoTag').css('display', 'block');
                        }
                        else{
                            $('#videoLoader').css('display', 'none');
                            $('#videoTag').css('display', 'none');
                        }
                    }
                });
            }
        }

        function uploadAudio(_element){

            if(_element.files && _element.files[0]) {

                $('#audioLoader').css('display', 'block');
                $('#audioTag').css('display', 'none');

                var data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', destId);
                data.append('kind', 'audio');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.storeVideoAudio")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        try {
                            response = JSON.parse(response);
                            if (response['status'] == 'ok') {
                                $('#audioLoader').css('display', 'none');
                                $('#audioTag').css('display', 'block');
                                $('#audioTag').attr('src', response['result']);
                            }
                            else {
                                let src = $('#audioSource').attr('src');
                                if(src != '#'){
                                    $('#audioLoader').css('display', 'none');
                                    $('#audioTag').css('display', 'block');
                                }
                                else{
                                    $('#audioLoader').css('display', 'none');
                                    $('#audioTag').css('display', 'none');
                                }
                            }
                        }
                        catch (e) {
                            let src = $('#audioSource').attr('src');
                            if(src != '#'){
                                $('#audioLoader').css('display', 'none');
                                $('#audioTag').css('display', 'block');
                            }
                            else{
                                $('#audioLoader').css('display', 'none');
                                $('#audioTag').css('display', 'none');
                            }
                        }
                    },
                    error: function(err){
                        console.log(err);
                        let src = $('#audioSource').attr('src');
                        if(src != '#'){
                            $('#audioLoader').css('display', 'none');
                            $('#audioTag').css('display', 'block');
                        }
                        else{
                            $('#audioLoader').css('display', 'none');
                            $('#audioTag').css('display', 'none');
                        }
                    }
                });
            }
        }

        function showPicSection(_value) {

            if (_value == 0)
                $('#pictureSection').css('display', 'flex');
            else
                $('#pictureSection').css('display', 'none');
        }

        @if(!isset($destination))
            showPicSection(1);
        @else
            showPicSection({{$destination->langSource}});
        @endif

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('Map_api')}}&callback=initMap"async defer></script>

@endsection

