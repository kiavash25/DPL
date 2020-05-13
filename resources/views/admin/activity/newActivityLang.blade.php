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
        <div class="col-md-12">
            <h2>
                @if(isset($activity))
                    @if(isset($showLang->symbol) && $showLang->symbol == 'fa')
                        {{__('Edit')}} {{__('Activity')}} {{$activity->name}}
                    @else
                        {{__('Edit')}} {{$activity->name}} {{__('Activity')}}
                    @endif

                    <div class="btn-group">
                        <a href="{{route("admin.activity.edit", ['id' => $activity->id])}}">
                            <button type="button" class="btn btn-{{$showLang == null ? 'success': 'primary'}}">English</button>
                        </a>
                        @foreach($langs as $lang)
                            <a href="{{route("admin.activity.edit", ['id' => $activity->id, 'lang' => $lang->symbol])}}">
                                <button type="button" class="btn btn-{{$showLang != null && $showLang->id == $lang->id ? 'success' : 'primary'}}">{{$lang->name}}</button>
                            </a>
                        @endforeach
                    </div>
                @else
                    {{__('submit new language')}} {{$mainActivity->name}} {{$showLang->symbol}}
                @endif
            </h2>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">

                <div class="col-md-9">
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="inputLabel">{{__('Activity Name')}}</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Activity Name" value="{{isset($activity->name) ? $activity->name : $mainActivity->name}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                        <div class="form-group">
                            <label for="parentId" class="inputLabel">{{__('Activity Category')}}</label>
                            <select name="parentId" id="parentId" class="form-control">
                                <option value="0">{{__('Main')}}</option>
                                @foreach($parent as $item)
                                    <option value="{{$item->id}}" {{isset($activity->category) && $item->id == $activity->category ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">{{__('Activity Description')}}</label>

                    <div class="toolbar-container"></div>
                    <div id="description" class="textEditor" >
                        {!! isset($activity->description) ? $activity->description : $mainActivity->description !!}
                    </div>

                </div>
            </div>


            <div class="row marg30" id="pictureSection" style="display: {{isset($activity->id) ? 'flex': 'none'}}">

                <div class="col-md-12">
                    <div class="row marg30">
                        <div class="col-md-12">
                            <div class="form-group" style="display:flex;">
                                <label class="inputLabel"> {{__('Activity Titles')}}</label>

                                <div class="col-lg-2 col-md-2">
                                    <div class="addTagIcon">
                                        <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addTitle()"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div  id="addNewTag" class="row" style="width: 100%">
                            @if(isset($activity->titles) && count($activity->titles) != 0)
                                @foreach( $activity->titles as $item)
                                    <div class="col-lg-3 col-md-4">
                                        <div class="form-group" style="position: relative">
                                            <input id="title_{{$item->id}}" type="text" class="form-control" placeholder="Title" value="{{$item->name}}" onchange="storeTitle({{$item->id}})">
                                            <div class="closeTagIcon" onclick="deleteTitle(this, {{$item->id}})">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6" style="margin-top: 40px;">
                    <label class="inputLabel">
                        Video
                        <label for="video" class="videoButton">
                            {{isset($activity->video) ? 'change' : 'add'}} video
                        </label>
                    </label>
                    <label class="mainPicSection">
                        <video id="videoTag" poster="placeholder.png" preload="none" controls style="width: 100%; height: 100%; display: {{isset($activity->video) ? 'block' : 'none'}} ">
                            <source id="videoSource" src="{{isset($activity->video) ? $activity->video : '#'}}">
                        </video>
                        <img id="videoLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                    </label>

                    <input type="file" name="video" id="video" accept="video/*" style="display: none" onchange="uploadVideo(this)">
                </div>

                <div class="col-md-6" style="margin-top: 40px;">
                    <label class="inputLabel">
                        podcast
                        <label for="audio" class="videoButton">
                            {{isset($activity->podcast) ? 'change' : 'add'}} podcast
                        </label>
                    </label>
                    <label style="height: 50px; width: 100%;">
                        <audio id="audioTag" preload="none" controls style="width: 100%; height: 100%; display: {{isset($activity->podcast) ? 'block' : 'none'}} ">
                            <source id="audioSource" src="{{isset($activity->podcast) ? $activity->podcast : '#'}}">
                        </audio>
                        <img id="audioLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                    </label>

                    <input type="file" name="audio" id="audio" accept="audio/*" style="display: none" onchange="uploadAudio(this)">
                </div>

            </div>

            <div class="row marg30" style="display: flex; justify-content: center; flex-direction: column; align-items: center">
                <a id="descriptionButton" href="{{isset($activity->id) ? route('admin.activity.description', ['id' => $activity->id]) : ''}}" style="display: {{isset($activity->id) ? 'block' : 'none'}}">
                    <button class="btn btn-warning" style="font-size: 30px; border-radius: 20px;">Descriptions Page</button>
                </a>

                <button class="btn btn-success" style="font-size: 30px; border-radius: 20px; width: 100%;; margin-top: 20px" onclick="submitForm()">Submit</button>

            </div>

        </div>

        <div class="modal" id="titleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Title</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#titleModal').modal('hide')">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="margin: 0px;">
                            <div class="form-group">
                                <label for="newTitle">New title</label>
                                <input type="text" id="newTitle" class="form-control">
                            </div>
                        </div>
                        <div class="row" style="justify-content: center">
                            <button class="btn btn-success" onclick="storeTitle(0)">Submit</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#titleModal').modal('hide')">Close</button>
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
            language: '{{$showLang->symbol}}'
        }).then( editor => {
            const toolbarContainer = document.querySelector( 'main .toolbar-container');
            toolbarContainer.prepend( editor.ui.view.toolbar.element );
            window.editor = editor ;
        } )
            .catch( err => {
                console.error( err.stack );
            } );

        let descriptionButtonUrl = '{{url("/admin/activity/description")}}';

    </script>


    <script>
        let mainActivityId = {{$mainActivity->id}};
        var activityId = {{isset($activity->id) ? $activity->id : 0}};
        let lang = '{{$showLang->symbol}}';
        function submitForm(){
            openLoading();

            let name = $('#name').val();
            let parentId = $('#parentId').val();
            let description = window.editor.getData();
            let error = '<ul>';

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(error != '<ul>'){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.storeLang")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        description: description,
                        mainId: mainActivityId,
                        lang: lang,
                        id: activityId,
                        parentId: parentId
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                activityId = response['id'];
                                $('#descriptionButton').attr('href', descriptionButtonUrl +'/'+ response['id']);

                                resultLoading('Your Activity Stored', 'success', goToImagePage);
                            }
                            else if(response[0] == 'nok2')
                                resultLoading('Your Activity name is duplicate', 'danger');
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

        function goToImagePage(){
            $('#uploadPicButton').css('display', 'block');
            $('#pictureSection').css('display', 'flex');

            $('#descriptionButton').css('display', 'block');
        }

        function uploadVideo(_element){

            if(_element.files && _element.files[0]) {

                $('#videoLoader').css('display', 'block');
                $('#videoTag').css('display', 'none');

                var data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', activityId);
                data.append('kind', 'video');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.storeVideoAudio")}}',
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
                data.append('id', activityId);
                data.append('kind', 'audio');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.storeVideoAudio")}}',
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

        function deleteTitle(_element, _id){
            openLoading();

            $.ajax({
                type: 'post',
                url: '{{route("admin.activity.deleteTitle")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id,
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            $(_element).parent().parent().remove();
                            resultLoading('Deleted..!', 'success');
                        }
                        else
                            resultLoading('Error 0', 'danger');
                    }
                    catch(e) {
                        resultLoading('Error 1', 'danger');
                    }
                },
                error: function(err){
                    resultLoading('Error 2', 'danger');
                }
            })
        }

        function storeTitle(_id){
            let name;
            if(_id == 0)
                name = $('#newTitle').val();
            else
                name = $('#title_' + _id).val();

            if(name.trim().length > 0) {
                openLoading();
                $('#titleModal').modal('hide');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.storeTitle")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id,
                        name: name,
                        activityId: activityId,
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                resultLoading('Stored', 'success');
                                if(_id == 0)
                                    createNewTitle(response['id'], response['name']);
                            }
                            else if(response['status'] == 'nok1')
                                resultLoading('Title name Duplicate!', 'danger');
                        }
                        catch(e) {
                            resultLoading('Error 1', 'danger');
                        }
                    },
                    error: function(err){
                        resultLoading('Error 2', 'danger');
                    }
                })
            }
        }

        function addTitle() {
            $('#newTitle').val('');
            $('#titleModal').modal('show');
            $('#titleModal').modal({backdrop: 'static', keyboard: false});
        }

        function createNewTitle(_id, _name){
            text = '<div class="col-md-3">\n' +
                '<div class="form-group" style="position: relative">\n' +
                '<input id="title_' + _id + '" type="text" class="form-control" placeholder="Title" value="' + _name + '" onchange="storeTitle(' + _id + ')"> \n' +
                '<div class="closeTagIcon" onclick="deleteTitle(this, ' + _id + ')">\n' +
                '<i class="fas fa-times"></i>\n' +
                '</div>\n' +
                '</div>\n' +
                '</div>';

            $('#addNewTag').append(text);
        }

    </script>

@endsection

