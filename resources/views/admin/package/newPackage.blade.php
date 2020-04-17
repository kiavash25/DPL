@extends('admin.layout.adminLayout')

@section('head')

    <style>
        .row{
            width: 100%;
        }
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

        /*on off button   */
        /* Switch starts here */
        .rocker {
            display: inline-block;
            position: relative;
            /*
            SIZE OF SWITCH
            ==============
            All sizes are in em - therefore
            changing the font-size here
            will change the size of the switch.
            See .rocker-small below as example.
            */
            font-size: 1.2em;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            color: #888;
            width: 7em;
            height: 4em;
            overflow: hidden;
            border-bottom: 0.5em solid #eee;
        }

        .rocker-small {
            font-size: 0.75em; /* Sizes the switch */
            margin: 1em;
        }

        .rocker::before {
            content: "";
            position: absolute;
            top: 0.5em;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #999;
            border: 0.5em solid #eee;
            border-bottom: 0;
        }

        .rocker input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-left,
        .switch-right {
            cursor: pointer;
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 2.5em;
            width: 3em;
            transition: 0.2s;
        }

        .switch-left {
            height: 2.4em;
            width: 2.75em;
            left: 0.85em;
            bottom: 0.4em;
            background-color: #ddd;
            transform: rotate(15deg) skewX(15deg);
        }

        .switch-right {
            right: 0.5em;
            bottom: 0;
            background-color: #bd5757;
            color: #fff;
        }

        .switch-left::before,
        .switch-right::before {
            content: "";
            position: absolute;
            width: 0.4em;
            height: 2.45em;
            bottom: -0.45em;
            background-color: #ccc;
            transform: skewY(-65deg);
        }

        .switch-left::before {
            left: -0.4em;
        }

        .switch-right::before {
            right: -0.375em;
            background-color: transparent;
            transform: skewY(65deg);
        }

        input:checked + .switch-left {
            background-color: #0084d0;
            color: #fff;
            bottom: 0px;
            left: 0.5em;
            height: 2.5em;
            width: 3em;
            transform: rotate(0deg) skewX(0deg);
        }

        input:checked + .switch-left::before {
            background-color: transparent;
            width: 3.0833em;
        }

        input:checked + .switch-left + .switch-right {
            background-color: #ddd;
            color: #888;
            bottom: 0.4em;
            right: 0.8em;
            height: 2.4em;
            width: 2.75em;
            transform: rotate(-15deg) skewX(-15deg);
        }

        input:checked + .switch-left + .switch-right::before {
            background-color: #ccc;
        }

        /* Keyboard Users */
        input:focus + .switch-left {
            color: #333;
        }

        input:checked:focus + .switch-left {
            color: #fff;
        }

        input:focus + .switch-left + .switch-right {
            color: #fff;
        }

        input:checked:focus + .switch-left + .switch-right {
            color: #333;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{asset('css/calendar/daterangepicker.css')}}" />

    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone-amd-module.js')}}"></script>

    <script src="{{asset('js/calendar/moment.min.js')}}"></script>
    <script src="{{asset('js/calendar/daterangepicker.js')}}"></script>
@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                @if($kind == 'new')
                    Create New Package
                @else
                    Edit {{$package->name}} Package
                @endif
            </h2>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="name" class="inputLabel">Package Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Package Name" value="{{isset($package->name) ? $package->name : ''}}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="rocker">
                            <input id="showPack" type="checkbox" {{isset($package->showPack) && $package->showPack == 1 ? 'checked' : ''}}>
                            <span class="switch-left">
                                <div style="font-size: 12px">
                                    Show
                                </div>
                            </span>
                            <span class="switch-right">
                                <div style="font-size: 12px">
                                    Draft
                                </div>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">Package Description</label>
                    <textarea type="text" id="description" name="description" class="form-control" placeholder="Package Description" rows="6"> {!! isset($package->description) ? $package->description : '' !!}</textarea>
                </div>
            </div>

            <div class="row marg30">
                <div class="col-xl-3">
                    <div class="row">
                        <div class="form-group">
                            <label for="destination" class="inputLabel">Destination</label>
                            <div id="destination" class="ui fluid search selection dropdown">
                                <input type="hidden" name="destination" id="DestinationId" onchange="changeDestination(this.value)" value="{{isset($package->destId) ? $package->destId : 0}}">
                                <div class="default text">Select Destination</div>
                                <i class="dropdown icon"></i>
                                <div class="menu">
                                    @foreach($destinations as $destination)
                                        <div class="divider"></div>
                                        <div class="header">
                                            {{$destination->category->name}}
                                        </div>
                                        <div class="divider"></div>
                                        @for($i = 0; $i < count($destination); $i++)
                                            <div class="item" data-value="{{$destination[$i]->id}}">
                                                {{$destination[$i]->name}}
                                            </div>
                                        @endfor
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row marg30">
                        <div class="form-group">
                            <label for="activity" class="inputLabel">Main Activity</label>
                            <select id="activity" class="ui fluid search dropdown">
                                <option value="0"></option>
                                @foreach($activity as $item)
                                    <option value="{{$item->id}}" {{isset($package->mainActivityId) && $item->id == $package->mainActivityId ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="row marg30">
                        <div class="form-group">
                            <label for="sideActivity" class="inputLabel">Side Activity</label>
                            <select id="sideActivity" class="ui fluid search dropdown" multiple="">
                                @foreach($activity as $item)
                                    <option value="{{$item->id}}" {{isset($package->activities) && in_array($item->id, $package->activities) ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="map" style="background-color: red; height: 400px; display: flex; justify-content: center; align-items: center; border-radius: 10px"></div>
                    <input type="hidden" id="lat" name="lat" value="{{isset($package->lat) ? $package->lat : 0}}">
                    <input type="hidden" id="lng" name="lng" value="{{isset($package->lng) ? $package->lng : 0}}">
                </div>
            </div>

            <div class="row marg30">
                <div class="col-md-6">
                    <div class="row" style="padding: 10px; border: solid lightgray 1px; border-radius: 10px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="inputLabel" for="season">Season</label>
                                <select name="season" id="season" class="form-control">
                                    <option value="spring" {{isset($package->season) && $package->season == 'spring' ? 'selected' : ''}}>Spring</option>
                                    <option value="summer" {{isset($package->season) && $package->season == 'summer' ? 'selected' : ''}}>Summer</option>
                                    <option value="fall" {{isset($package->season) && $package->season == 'fall' ? 'selected' : ''}}>Fall</option>
                                    <option value="winter" {{isset($package->season) && $package->season == 'winter' ? 'selected' : ''}}>winter</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="inputLabel" for="day">Day</label>
                                <input type="number" id="day" name="day" class="form-control" value="{{isset($package->day) ? $package->day : ''}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="inputLabel" for="sDate">Start Date</label>
                                <input type="text" id="sDate" name="sDate" class="form-control" value="{{isset($package->sDate) ? $package->sDate : ''}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="inputLabel" for="sDate">End Date</label>
                                <input type="text" id="eDate" name="eDate" class="form-control" value="{{isset($package->eDate) ? $package->eDate : ''}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="inputLabel" for="cost">Cost</label>
                                <input type="text" id="cost" name="Cost" class="form-control" value="{{isset($package->money) ? $package->money : ''}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="inputLabel" for="cost">Draft</label>
                                <input type="text" id="cost" name="Cost" class="form-control" value="{{isset($package->money) ? $package->money : ''}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row" style="padding: 10px; border: solid lightgray 1px; border-radius: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="inputLabel" style="display: flex; align-items: center">
                                    Package Tags
                                    <div class="addTagIcon" style="margin-left: 10px">
                                        <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addTag()"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div id="addNewTag" class="row" style="width: 100%">
                            @if(isset($package->tags) && count($package->tags) != 0)
                                @for($i = 0; $i < count($package->tags); $i++)
                                    <div class="col-lg-4">
                                        <div class="form-group" style="position: relative">
                                            <input type="text" name="tags[]" class="form-control" placeholder="Tag" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onchange="closeSearch(this)" value="{{$package->tags[$i]}}">
                                            <div class="closeTagIcon" onclick="deleteTag(this)">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>

                                        <div class="tagSearchResult"></div>
                                    </div>
                                @endfor
                            @else
                                @for($i = 0; $i < 5; $i++)
                                    <div class="col-lg-4">
                                        <div class="form-group" style="position: relative">
                                            <input type="text" name="tags[]" class="form-control" placeholder="Tag" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)">
                                            <div class="closeTagIcon" onclick="deleteTag(this)">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>

                                        <div class="tagSearchResult"></div>
                                    </div>
                                @endfor
                            @endif
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
                        <img id="mainPicImg" src="{{isset($package->pic) && $package->pic != null ? $package->pic : '#'}}" style="width: 100%; display: {{isset($package->pic) && $package->pic != null ? 'block' : 'none'}};" >
                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($package->pic) && $package->pic != null ? 'none' : 'block'}};"></i>
                    </label>

                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPic(this, 'mainPic')">
                </div>
                <div class="col-md-9">
                    <div id="uploadedPic" class="row">
                        @if(isset($package->sidePic) && count($package->sidePic) > 0)
                            @foreach($package->sidePic as $item)
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
        $('#destination')
            .dropdown({
                clearable: true,
                placeholder: 'any'
            });

        $('#sideActivity')
            .dropdown();
        $('#activity')
            .dropdown({
                allowAdditions: true
            });

        $(function() {

            $('#sDate').daterangepicker({
                "autoUpdateInput": false,
            }, function(start, end, label) {
                $('#sDate').val(start.format('YYYY-MM-DD'));
                $('#eDate').val(end.format('YYYY-MM-DD'));
            });

            $('#eDate').on('click', function(){
                document.getElementById('sDate').click();
            })
        });

        function deleteTag(_element){
            $(_element).parent().parent().remove();
        }
        function addTag() {
            text = '<div class="col-lg-4">\n' +
                '<div class="form-group" style="position: relative">\n' +
                '<input type="text" name="tags[]" class="form-control" placeholder="Tag" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)"> \n' +
                '<div class="closeTagIcon" onclick="deleteTag(this)">\n' +
                '<i class="fas fa-times"></i>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="tagSearchResult"></div>' +
                '</div>';

            $('#addNewTag').append(text);
        }
    </script>


    <script>
        let lat = 32.427908;
        let lng = 53.688046;
        let destinations = {!! $allDestination !!};
        let map;
        let marker = 0;
        let packageId = {{isset($package->id) ? $package->id : 0}};

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: 5
            });

            google.maps.event.addListener(map, 'click', function(event) {
                getLat(event.latLng);
            });

            @if(isset($package->lat) && isset($package->lng))
                map.setZoom(12);
                map.panTo({
                    lat: parseFloat( {{$package->lat}} ),
                    lng: parseFloat( {{$package->lng}} )
                });
                marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat( {{$package->lat}} ),
                        lng: parseFloat( {{$package->lng}} )
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

        function changeDestination(_value){
            let lat;
            let lng;
            for(i = 0; i < destinations.length; i++){
                if(destinations[i]['id'] == _value){
                    lat = destinations[i]['lat'];
                    lng = destinations[i]['lng'];
                    break;
                }
            }
            map.setZoom(10);
            map.panTo({
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            });

            if(marker != 0)
                marker.setMap(null);

            marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                },
                map: map,
            });
            $('#lat').val(parseFloat(lat));
            $('#lng').val(parseFloat(lng));
        }

        let tagSelected;
        function findTag(_element){
            let value = $(_element).val();

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
                            let answers = '';
                            let tags = response[1];
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
            let value = $(_element).text();
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

            let name = $('#name').val();
            let showPack = $('#showPack:checked').val();
            if(showPack == undefined)
                showPack = 0;
            else
                showPack = 1;

            let description = $('#description').val();
            let lat = $('#lat').val();
            let lng = $('#lng').val();
            let destinationId = $('#DestinationId').val();
            let mainActivity = $('#activity').val();
            let sideActivity = $('#sideActivity').val();
            let day = $('#day').val();
            let sDate = $('#sDate').val();
            let eDate = $('#eDate').val();
            let cost = $('#cost').val();
            let season = $('#season').val();
            let tagsElement = $("input[name*='tags']");
            let tags = [];
            let error = '<ul style="text-align: left">';
            let checkError = '<ul style="text-align: left">' ;

            for(i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val() != null && $(tagsElement[i]).val().trim().length != 0)
                    tags[tags.length] = $(tagsElement[i]).val();
            }

            if(name.trim().length == 0)
                error += '<li style="margin: 15px 0px"> Please Choose Name.</li>';

            if(destinationId == 0)
                error += '<li style="margin: 15px 0px"> Please Choose Destination.</li>';

            if(lat == 0 && lng == 0)
                error += '<li style="margin: 15px 0px"> Please select a location from the map.</li>';

            if(mainActivity == 0 )
                error += '<li style="margin: 15px 0px"> Please Choose MainActivity.</li>';

            if(day == 0 )
                error += '<li style="margin: 15px 0px"> Please specify the number of days.</li>';

            if(sDate.trim().length == 0 || eDate.trim().length == 0)
                error += '<li style="margin: 15px 0px"> Please specify the start and end dates.</li>';


            if(error != checkError){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.package.store")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        description: description,
                        destinationId: destinationId,
                        lat: lat,
                        lng: lng,
                        tags: JSON.stringify(tags),
                        id: packageId,
                        mainActivity: mainActivity,
                        sideActivity: sideActivity,
                        day: day,
                        sDate: sDate,
                        eDate: eDate,
                        cost: cost,
                        season: season,
                        showPack: showPack
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                packageId = response['id'];
                                resultLoading('Your Package Stored', 'success', goToImagePage);
                                $('#pictureSection').css('display', 'flex')
                            }
                            else if(response['status'] == 'nok2')
                                resultLoading('The name of the pack at this destination is a duplicate', 'danger');
                            else
                                resultLoading('Please Try Again', 'danger');
                        }
                        catch (e) {
                            resultLoading('Please Try Again', 'danger');
                        }
                    },
                    error: function(err){
                        resultLoading('Please Try Again', 'danger');
                    }
                })
            }

        }

        let myDropzone = new Dropzone("div#dropzone", {
            url: "{{route('admin.package.storeImg')}}",
            paramName: "pic",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    formData.append("kind", 'side');
                    formData.append("id", packageId);
                });
            },

        }).on('success', function(file, response){

            if(response['status'] == 'ok'){
                let text =  '<div class="col-md-3 uploadedPic">\n' +
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
                let reader = new FileReader();
                reader.onload = function(e) {
                    let mainPic = e.target.result;
                    $('#mainPicImg').attr('src', mainPic);

                    $('#mainPicImg').css('display', 'none');
                    $('#mainPicImg').next().css('display', 'block');
                    $('#mainPicImg').next().next().css('display', 'none');

                    let data = new FormData();

                    data.append('pic', _input.files[0]);
                    data.append('id', packageId);
                    data.append('kind', 'mainPic');
                    data.append('_token', '{{csrf_token()}}');

                    $.ajax({
                        type: 'post',
                        url: '{{route("admin.package.storeImg")}}',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function(response){
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
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
                url: '{{route("admin.package.deleteImg")}}',
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

