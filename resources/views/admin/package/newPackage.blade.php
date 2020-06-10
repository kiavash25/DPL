@extends('admin.layout.adminLayout')

@section('head')

    <link rel="stylesheet" type="text/css" href="{{asset('css/admin/newPackage.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" media="all" href="{{asset('css/calendar/daterangepicker.css')}}" />

    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone-amd-module.js')}}"></script>

    <script src="{{asset('js/calendar/moment.min.js')}}"></script>
    <script src="{{asset('js/calendar/daterangepicker.js')}}"></script>

    <script src="{{asset('js/ckeditor.js')}}"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <style>

        .textEditor{
            height: 30vh;
            border: solid 1px var(--ck-color-toolbar-border) !important;
            border-top: none !important;
            border-radius: 5px !important;
        }
    </style>

@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12" style="display: flex; align-items: center;">
            <h2>
                @if($kind == 'new')
                    {{__('Create New Package')}}
                @else
                    {{__('Edit')}} {{__('Package')}} : {{$package->name}}
                @endif
            </h2>

            <div class="form-group" style="width: auto; margin-right: 30px; display: {{app()->getLocale() != 'en' ? 'block': 'none'}}">
                <label for="source">{{__('Source')}}</label>
                <select name="source" id="source" class="form-control" onchange="showPicSection(this.value)">
                    <option value="0" {{isset($package->langSource) && $package->langSource == 0 ? 'selected' : ''}}>{{__('New')}}</option>
                    @foreach($sourceParent as $s)
                        <option value="{{$s->id}}" {{isset($package->langSource) && $package->langSource == $s->id ? 'selected' : ''}}>{{$s->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <label for="name" class="inputLabel">{{__('Package Name')}}</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Package Name" value="{{isset($package->name) ? $package->name : ''}}">
                    </div>
                </div>
                <div class="col-md-2 relatedSection">
                    <div class="form-group">
                        <label class="rocker">
                            <input id="showPack" type="checkbox" {{isset($package->showPack) && $package->showPack == 1 ? 'checked' : ''}}>
                            <span class="switch-left">
                                <div style="font-size: 12px">
                                    {{__('Show')}}
                                </div>
                            </span>
                            <span class="switch-right">
                                <div style="font-size: 12px">
                                    {{__('Draft')}}
                                </div>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">{{__('Package Description')}}</label>
                    <div class="toolbar-container"></div>
                    <div id="description" class="textEditor" >
                        {!! isset($package->description) ? $package->description : '' !!}
                    </div>
                </div>
            </div>

            <div class="row marg30">
                <div class="col-xl-3">
                    <div class="row">
                        <div class="form-group">
                            <label for="destination" class="inputLabel">{{__('Destination')}}</label>
                            <div id="destination" class="ui fluid search selection dropdown">
                                <input type="hidden" name="destination" id="DestinationId" onchange="changeDestination(this.value)">
                                <div class="default text">{{__('Select Destination')}}</div>
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
                            <label for="activity" class="inputLabel">{{__('Main Activity')}}</label>
                            <div id="activityDropDown"  class="ui fluid search selection dropdown">
                                <input type="hidden" name="activityDropDown" id="activity">
                                <i class="dropdown icon"></i>
                                <div class="default text">{{__('Main Activity')}}</div>
                                <div class="menu">
                                    @foreach($activity as $item)
                                        <div class="header">
                                            {{$item->name}}
                                        </div>
                                        @foreach($item->sub as $sub)
                                            <div class="item" data-value="{{$sub->id}}">
                                                {{$sub->name}}
                                            </div>
                                        @endforeach
                                        <div class="divider"></div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
{{--                    <div class="row marg30">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="sideActivity" class="inputLabel">Side Activity</label>--}}
{{--                            <select id="sideActivity" class="ui fluid search dropdown" multiple="">--}}
{{--                                @foreach($activity as $item)--}}
{{--                                    <option value="{{$item->id}}" {{isset($package->activities) && in_array($item->id, $package->activities) ? 'selected' : ''}}>{{$item->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                    </div>--}}
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

                        <div class="col-md-6 relatedSection">
                            <div class="form-group">
                                <label class="inputLabel" for="code">{{__('Package Code')}}</label>
                                <input type="text" id="code" name="code" class="form-control" value="{{isset($package->code) ? $package->code : ''}}">
                            </div>
                        </div>

                        <div class="col-md-6 relatedSection">
                            <div class="form-group">
                                <label class="inputLabel" for="day">{{__('Day')}}</label>
                                <input type="number" id="day" name="day" class="form-control" value="{{isset($package->day) ? $package->day : ''}}">
                            </div>
                        </div>

                        <div class="col-md-6 relatedSection">
                            <div class="form-group">
                                <label class="inputLabel" for="season">{{__('Season')}}</label>
                                <select name="season" id="season" class="form-control">
                                    <option value="spring" {{isset($package->season) && $package->season == 'spring' ? 'selected' : ''}}>{{__('Spring')}}</option>
                                    <option value="summer" {{isset($package->season) && $package->season == 'summer' ? 'selected' : ''}}>{{__('Summer')}}</option>
                                    <option value="fall" {{isset($package->season) && $package->season == 'fall' ? 'selected' : ''}}>{{__('Fall')}}</option>
                                    <option value="winter" {{isset($package->season) && $package->season == 'winter' ? 'selected' : ''}}>{{__('Winter')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 relatedSection">
                            <div class="form-group">
                                <label class="inputLabel" for="level">{{__('Level')}}</label>
                                <select name="level" id="level" class="form-control">
                                    <option value="easy" {{isset($package->level) && $package->level == 'easy' ? 'selected' : ''}}>{{__('Easy')}}</option>
                                    <option value="medium" {{isset($package->level) && $package->level == 'medium' ? 'selected' : ''}}>{{__('Medium')}}</option>
                                    <option value="hard" {{isset($package->level) && $package->level == 'hard' ? 'selected' : ''}}>{{__('Hard')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 relatedSection">
                            <div class="form-group">
                                <label class="inputLabel" for="sDate">{{__('Start date')}}</label>
                                <input type="text" id="sDate" name="sDate" class="form-control" value="{{isset($package->sDate) ? $package->sDate : ''}}" readonly>
                                <button class="btn btn-danger" onclick="$('#sDate').val('')">{{__('clear Start Date')}}</button>
                            </div>
                        </div>
                        <div class="col-md-6 relatedSection">
                            <div class="form-group">
                                <label class="inputLabel" for="eDate">{{__('End Date')}}</label>
                                <input type="text" id="eDate" name="eDate" class="form-control" value="{{isset($package->eDate) ? $package->eDate : ''}}" readonly>
                                <button class="btn btn-danger" onclick="$('#eDate').val('')">{{__('clear End Date')}}</button>
                            </div>
                        </div>
                        <?php
                            $currentLang = \App\models\Language::where('symbol', app()->getLocale())->first();
                            if($currentLang != null)
                                $currencyName = $currentLang->currencyName;
                            else
                                $currencyName = 'Euro';
                        ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="inputLabel" for="cost">{{__('Cost')}} ({{$currencyName}})</label>
                                <input type="text" id="cost" name="Cost" class="form-control" value="{{isset($package->money) ? $package->money : '0'}}">
                            </div>
                        </div>

                        <div id="brochureDiv" class="col-md-6" style="display: {{isset($package->id) ? 'block' : 'none'}}">
                            <label class="inputLabel" for="brochure">{{__('Brochure')}}</label>
                            <input type="file" name="brochure" id="brochure" onchange="uploadBrochure(this)">
                            <a href="{{isset($package->brochureUrl) ? $package->brochureUrl : ''}}">
                                <button id="brochureName" class="btn btn-success">{{isset($package->brochure) ? $package->brochure : ''}}</button>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row" style="padding: 10px; border: solid lightgray 1px; border-radius: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="inputLabel" style="display: flex; align-items: center">
                                    {{__('Package Tags')}}
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
                                            <input type="text" name="tags[]" class="form-control" placeholder="{{__('Title')}}" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onchange="closeSearch(this)" value="{{$package->tags[$i]}}">
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
                                            <input type="text" name="tags[]" class="form-control" placeholder="{{__('Title')}}" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)">
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

                <hr>
                <div class="col-md-12">
                    <h2>
                        {{__('Side Info')}}
                    </h2>

                </div>
                <div class="col-md-12">
                    <div id="sideInfoDiv" class="row">

                    </div>
                    <div class="addNewSideInfo" onclick="addNewSideInfo()">
                        <i class="fas fa-plus-circle" style="cursor: pointer;"></i>
                    </div>
                </div>
                <hr>

                <div class="col-md-3 centerContent relatedSection" style="flex-direction: column; justify-content: end">
                    <label class="inputLabel">
                        {{__('Main Picture')}}
                    </label>
                    <label for="mainPic" class="mainPicSection">
                        <img id="mainPicImg" src="{{isset($package->pic) && $package->pic != null ? $package->pic : '#'}}" style="width: 100%; display: {{isset($package->pic) && $package->pic != null ? 'block' : 'none'}};" >
                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($package->pic) && $package->pic != null ? 'none' : 'block'}};"></i>
                    </label>

                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPic(this, 'mainPic')">
                </div>
                <div class="col-md-9 relatedSection">
                    <div id="uploadedPic" class="row">
                        @if(isset($package->sidePic) && count($package->sidePic) > 0)
                            @foreach($package->sidePic as $item)
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

                <div class="col-12 relatedSection" style="display: flex; justify-content: center">
                    <button id="uploadPicButton" class="btn btn-primary" style="font-size: 30px; border-radius: 20px;" onclick="uploadPicModal()">{{__('Upload Picture')}}</button>
                </div>
                <hr>
                <div class="col-md-12 relatedSection" style="flex-direction: column">
                    <h2>
                        {{__('Thumbnail Picture')}}
                    </h2>
                    <div id="thumbnailRow" class="row">
                        @if(isset($package->thumbnail))
                            @foreach($package->thumbnail as $item)
                                <div id="thumbnail_{{$item->id}}" class="thumbnailPicDiv">
                                    <img src="{{$item->pic}}" class="resizeImageClass thumbnailPic">
                                    <div class="uploadedPicHover">
                                        <button class="btn btn-danger" onclick="deleteThumbnailPic({{$item->id}}, this)">delete</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-12" style="display: flex; justify-content: center">
                            <button id="uploadPicButton" class="btn thumbnailUploadButton" onclick="uploadThumbnailPicModal()">{{__('Upload Thumbnail Picture')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row marg30" style="display: flex; justify-content: center;">
                <button class="btn btn-success" style="font-size: 30px; border-radius: 20px" onclick="submitForm()">{{__('Submit')}}</button>
            </div>

        </div>

        <div class="modal" id="uploadPic">
            <div class="modal-dialog modal-xl" style="max-width: 1500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Upload Pictures')}}</h4>
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

        <div class="modal" id="uploadThumbnailPic">
            <div class="modal-dialog modal-xl" style="max-width: 1500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Upload Thumbnail Picture')}}</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="dropzoneThumbnail" class="dropzone"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="sideInfoModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="sideInfoModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal"  onclick="closeSideInfoModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="justify-content: center">
                            <input type="hidden" id="sideInfoId">
                            <input type="file" id="fileInputSideInfo" style="display: none" onchange="showMoreInfoIcon(this)">
                            <label for="fileInputSideInfo" class="iconSideInfoDiv">
                                <img id="iconSideInfo" src="#" style="width: 100%; height: 100%; display: none">
                                <i id="newIconSideInfo" class="fas fa-plus-circle" style="cursor: pointer;  display: block;"></i>
                            </label>
                            <div class="form-group sideInfoInput">
                                <textarea type="text" class="form-control" id="sideInfoTextInput" style="margin-left: 10px" maxlength="500"></textarea>
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center; align-items: center">
                            <button class="btn btn-success" onclick="storeSideInfo()">
                                {{__('Store')}}
                            </button>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeSideInfoModal()">{{__('Close')}}</button>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@section('script')

    <script>
        DecoupledEditor.create( document.querySelector('#description'), {
            toolbar: ['heading', 'bold', 'italic', 'link' ],
            language: '{{app()->getLocale()}}'
        }).then( editor => {
            const toolbarContainer = document.querySelector( 'main .toolbar-container');
            toolbarContainer.prepend( editor.ui.view.toolbar.element );
            window.editor = editor ;
        } )
            .catch( err => {
                console.error( err.stack );
            } );

        $('#sDate').datepicker({
            dateFormat: "yy-mm-dd"

        });
        $('#eDate').datepicker({
            dateFormat: "yy-mm-dd"
        });

        $('#destination').dropdown('set selected', {{isset($package->destId) ? $package->destId : 0}});

        $('#sideActivity').dropdown();
        $('#activityDropDown').dropdown('set selected', {{isset($package->mainActivityId) ? $package->mainActivityId : 0}});

        function deleteTag(_element){
            $(_element).parent().parent().remove();
        }
        function addTag() {
            text = '<div class="col-lg-4">\n' +
                '<div class="form-group" style="position: relative">\n' +
                '<input type="text" name="tags[]" class="form-control" placeholder="{{__('Title')}}" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)"> \n' +
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
            let source = $('#source').val();
            let showPack = $('#showPack:checked').val();
            if(showPack == undefined)
                showPack = 0;
            else
                showPack = 1;

            let description = window.editor.getData();
            let lat = $('#lat').val();
            let lng = $('#lng').val();
            let code = $('#code').val();
            let destinationId = $('#DestinationId').val();
            let mainActivity = $('#activity').val();
            let sideActivity = $('#sideActivity').val();
            let day = $('#day').val();
            let sDate = $('#sDate').val();
            let eDate = $('#eDate').val();
            let cost = $('#cost').val();
            let season = $('#season').val();
            let level = $('#level').val();
            let tagsElement = $("input[name*='tags']");
            let tags = [];
            let error = '<ul style="text-align: left">';
            let checkError = '<ul style="text-align: left">' ;

            for(i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val() != null && $(tagsElement[i]).val().trim().length != 0)
                    tags[tags.length] = $(tagsElement[i]).val();
            }

            if(source == 0){
                if(code.trim().length == 0)
                    error += '<li style="margin: 15px 0px"> Please Choose Code.</li>';

                if(lat == 0 && lng == 0)
                    error += '<li style="margin: 15px 0px"> Please select a location from the map.</li>';
            }
            if(name.trim().length == 0)
                error += '<li style="margin: 15px 0px"> Please Choose Name.</li>';

            if(destinationId == 0)
                error += '<li style="margin: 15px 0px"> Please Choose Destination.</li>';

            if(mainActivity == 0 )
                error += '<li style="margin: 15px 0px"> Please Choose MainActivity.</li>';


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
                        code: code,
                        tags: JSON.stringify(tags),
                        id: packageId,
                        mainActivity: mainActivity,
                        sideActivity: sideActivity,
                        day: day,
                        sDate: sDate,
                        eDate: eDate,
                        cost: cost,
                        season: season,
                        level: level,
                        source: source,
                        showPack: showPack
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                packageId = response['id'];
                                resultLoading('{{__('Your Package Stored')}}', 'success', goToImagePage);
                                $('#pictureSection').css('display', 'flex');
                                $('#brochureDiv').css('display', 'block');
                            }
                            else if(response['status'] == 'nok1')
                                resultLoading('The name of the pack at this destination is a duplicate', 'danger');
                            else if(response['status'] == 'nok9')
                                resultLoading('The Code is duplicate', 'danger');
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
            response = JSON.parse(response);
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

        let myDropzoneThumbnail = new Dropzone("div#dropzoneThumbnail", {
            url: "{{route('admin.package.storeImg')}}",
            paramName: "pic",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    formData.append("kind", 'thumbnail');
                    formData.append("id", packageId);
                });
            },
        }).on('success', function(file, response){
            response = JSON.parse(response);
            if(response['status'] == 'ok'){
                let text =  '<div id="thubmnail_' + response['id'] + '" class="thumbnailPicDiv">\n' +
                            '   <img src="' + file['dataURL'] + '" class="resizeImageClass thumbnailPic">\n' +
                            '   <div class="uploadedPicHover">\n' +
                            '       <button class="btn btn-danger" onclick="deleteThumbnailPic(' + response['id'] + ', this)">delete</button>\n' +
                            '   </div>\n' +
                            '</div>';
                $('#thumbnailRow').append(text);
                resizeImg('resizeImageClass');
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
                    id: _id,
                    kind: 'side'
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        $(_element).parent().parent().remove();
                }
            })
        }

        function uploadBrochure(_element){
            if(_element.files && _element.files[0]) {
                var data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', packageId);
                data.append('kind', 'brochure');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.package.storeVideoAudio")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        try {
                            response = JSON.parse(response);
                            if (response['status'] == 'ok') {
                                $('#brochureName').text(response['name']);
                                $('#brochureName').parent().attr('href', response['url']);
                            }
                        }
                        catch (e) {

                        }
                    },
                    error: function(err){
                        console.log(err);
                    }
                });
            }
        }



        function uploadThumbnailPicModal(){
            $('#uploadThumbnailPic').modal('show');
        }

        function deleteThumbnailPic(_id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("admin.package.deleteImg")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id,
                    kind: 'thumbnail'
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

    <script !src="">
        let sideInfoIcon = null;
        let sideInfos = [];
        @if(isset($package->sideInfos))
            sideInfos = {!! $package->sideInfos !!}
        @endif

        function addNewSideInfo(){
            sideInfoIcon = null;
            $('#fileInputSideInfo').val('');
            $('#sideInfoId').val(0);
            $('#sideInfoTextInput').val('');
            $('#sideInfoModalHeader').text("{{__('new Side Info')}}");
            $('#newIconSideInfo').show();
            $('#iconSideInfo').hide();
            $('#sideInfoModal').modal('show');
            $('#sideInfoModal').modal({backdrop: 'static', keyboard: false});
        }

        function closeSideInfoModal(){
            $('#sideInfoModal').modal('hide');
        }

        function showMoreInfoIcon(_input){
            if(_input.files && _input.files[0]){
                let reader = new FileReader();
                reader.onload = function(e) {
                    let mainPic = e.target.result;
                    $('#iconSideInfo').attr('src', mainPic);

                    $('#iconSideInfo').css('display', 'block');
                    $('#iconSideInfo').next().css('display', 'none');

                    sideInfoIcon = _input.files[0];
                };
                reader.readAsDataURL(_input.files[0]);
            }
        }

        function storeSideInfo(){
            let id = $('#sideInfoId').val();
            let text = $('#sideInfoTextInput').val();

            if(id == 0 && (text.trim().length == 0 || sideInfoIcon == null)){
                alert('You must fill text and icon');
                return;
            }

            let formData = new FormData();
            formData.append('id', id);
            formData.append('text', text);
            formData.append('icon', sideInfoIcon);
            formData.append('packageId', packageId);
            formData.append('_token', '{{csrf_token()}}');

            $.ajax({
                type: 'post',
                url: '{{route("admin.package.storeSideInfo")}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok') {
                        if(id == 0) {
                            sideInfos.push(response['result']);
                            createNewSideInfoRow(response['result']);
                        }
                        else{
                            $('#textSideInfo_' + id).text(response['result']['text']);
                            $('#iconSideInfo_' + id).attr('src', response['result']['icon']);
                        }

                        $('#sideInfoModal').modal('hide');
                    }
                    else
                        alert('Error storing information')
                }
            })
        }

        function createNewSideInfoRow(_value){
            let text = '<div id="sideInfoRow_' + _value['id'] + '" class="row sideInfoRow">\n' +
                '<label class="iconSideInfoDiv">\n' +
                '<img id="iconSideInfo_' + _value["id"] + '" src="' + _value["icon"] + '" style="width: 100%; height: 100%;">\n' +
                '</label>\n' +
                '<div id="textSideInfo_' + _value["id"] + '" class="sideInfoInput">' + _value["text"] + '</div>\n' +
                '<button class="btn btn-primary" onclick="editSideInfo(' + _value["id"] + ')"> {{__('Edit')}}</button>\n' +
                '<div class="closeSideInfoIcon" onclick="deleteSideInfo(' + _value["id"] + ')">\n' +
                '<i class="fas fa-times"></i>\n' +
                '</div>\n' +
                '</div>';

            $('#sideInfoDiv').append(text);
        }

        function deleteSideInfo(_id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.package.deleteSideInfo")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        $('#sideInfoRow_' + _id).remove();
                }
            })
        }

        function editSideInfo(_id){
            let side = null;
            for(let i = 0; i < sideInfos.length; i++){
                if(_id == sideInfos[i].id){
                    side = sideInfos[i];
                    break;
                }
            }
            if(side != null){
                sideInfoIcon = null;
                $('#fileInputSideInfo').val('');
                $('#sideInfoId').val(side.id);
                $('#sideInfoTextInput').val(side.text);
                $('#sideInfoModalHeader').text("{{__('Edit side info')}}");
                $('#newIconSideInfo').hide();
                $('#iconSideInfo').show();
                $('#iconSideInfo').attr('src', side.icon);
                $('#sideInfoModal').modal('show');
                $('#sideInfoModal').modal({backdrop: 'static', keyboard: false});
            }
        }

        for(let i = 0; i < sideInfos.length; i++)
            createNewSideInfoRow(sideInfos[i]);


        function showPicSection(_value) {
            if (_value == 0)
                $('.relatedSection').css('display', 'flex');
            else
                $('.relatedSection').css('display', 'none');
        }

        @if(!isset($package))
            showPicSection(0);
        @else
            showPicSection({{$package->langSource}});
        @endif

    </script>
@endsection

