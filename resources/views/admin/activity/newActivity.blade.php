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
                    Edit {{$activity->name}} Activity
                @else
                    Create New Activity
                @endif
            </h2>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">

                <div class="col-md-9">
                    <div class="row">
                        <div class="form-group">
                            <label for="name" class="inputLabel">Activity Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Activity Name" value="{{isset($activity->name) ? $activity->name : ''}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="row">
                        <div class="form-group">
                            <label for="parentId" class="inputLabel">Activity Category</label>
                            <select name="parentId" id="parentId" class="form-control">
                                <option value="0">Main</option>
                                @foreach($parent as $item)
                                    <option value="{{$item->id}}" {{$item->id == $activity->category ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">Activity Description</label>

                    <div class="toolbar-container"></div>
                    <div id="description" class="textEditor" >
                        {!! isset($activity->description) ? $activity->description : '' !!}
                    </div>

                </div>
            </div>

            <div class="row marg30">
                <div class="col-md-12">
                    <div class="form-group" style="display:flex;">
                        <label class="inputLabel"> Activity Titles</label>

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

            <div class="row"></div>

            <div class="row marg30" id="pictureSection" style="display: {{isset($activity->id) ? 'flex': 'none'}}">
{{--                <div class="col-md-3 centerContent" style="flex-direction: column; justify-content: end">--}}
{{--                    <label class="inputLabel">--}}
{{--                        Activity Icon--}}
{{--                    </label>--}}
{{--                    <label for="mainPic" class="mainPicSection">--}}
{{--                        <img id="mainPicImg" src="{{isset($activity->icon) && $activity->icon != null ? $activity->icon : '#'}}" style="width: 100%; display: {{isset($activity->icon) && $activity->icon != null ? 'block' : 'none'}};" >--}}
{{--                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >--}}
{{--                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($activity->icon) && $activity->icon != null ? 'none' : 'block'}};"></i>--}}
{{--                    </label>--}}

{{--                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPics(this, 'mainPicImg', showMainPic)">--}}
{{--                </div>--}}

                <div class="col-md-12">
                    <div id="uploadedPic" class="row">
                        @if(isset($activity->sidePic) && count($activity->sidePic) > 0)
                            @foreach($activity->sidePic as $item)
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
            toolbar: [ 'bold', 'italic', 'link' ]
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
        var activityId = {{isset($activity->id) ? $activity->id : 0}};

        var myDropzone = new Dropzone("div#dropzone", {
            url: "{{route('admin.activity.storeImg')}}",
            paramName: "pic",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    formData.append("kind", 'side');
                    formData.append("id", activityId);
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

        function submitForm(){
            openLoading();

            var name = $('#name').val();
            var description = window.editor.getData();
            var parentId = $('#parentId').val();
            var error = '<ul>';

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(error != '<ul>'){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.store")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        description: description,
                        parentId: parentId,
                        id: activityId
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

        function uploadPicModal(){
            $('#uploadPic').modal('show');
        }

        function goToImagePage(){
            $('#uploadPicButton').css('display', 'block');
            $('#pictureSection').css('display', 'flex');

            $('#descriptionButton').css('display', 'block');
        }

        function showMainPic(_pic){
            var mainPic = _pic;

            $('#mainPicImg').css('display', 'none');
            $('#mainPicImg').next().css('display', 'block');
            $('#mainPicImg').next().next().css('display', 'none');

            var data = new FormData();

            data.append('pic', mainPic);
            data.append('id', activityId);
            data.append('kind', 'icon');
            data.append('_token', '{{csrf_token()}}');

            $.ajax({
                type: 'post',
                url: '{{route("admin.activity.storeImg")}}',
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
                url: '{{route("admin.activity.deleteImg")}}',
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

