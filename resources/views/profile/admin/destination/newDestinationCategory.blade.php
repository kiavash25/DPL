@extends('profile.layout.profileLayout')

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
        .titleInput{
            font-weight: bold;
            color: black;
        }
    </style>

@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12" style="display: flex; align-items: center;">
            <h2>
                @if(isset($category) == 'new')

                    {{__('Edit category destination')}} : {{$category->name}}
                @else
                    {{__('Create New Category Destination')}}
                @endif
            </h2>


            <div class="form-group" style="width: auto; margin-right: 30px; display: {{app()->getLocale() != 'en' ? 'block': 'none'}}">
                <label for="source">{{__('Source')}}</label>
                <select name="source" id="source" class="form-control" onchange="showPicSection(this.value)">
                    <option value="0" {{isset($category->langSource) && $category->langSource == 0 ? 'selected' : ''}}>{{__('New')}}</option>
                    @foreach($sourceParent as $s)
                        <option value="{{$s->id}}" {{isset($category->langSource) && $category->langSource == $s->id ? 'selected' : ''}}>{{$s->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="name" class="inputLabel">{{__('Category Name')}}</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Destination Name" value="{{isset($category->name) ? $category->name : ''}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="viewOrder" class="inputLabel">{{__('View Order')}}</label>
                        <input type="number" id="viewOrder" name="viewOrder" class="form-control" value="{{isset($category->viewOrder) ? $category->viewOrder : '1'}}">
                    </div>
                </div>
            </div>

            <div class="row marg30">
                <div class="form-group">
                    <label for="description" class="inputLabel">{{__('Category Description')}}</label>

                    <div class="toolbar-container"></div>
                    <div id="description" class="textEditor" >
                        {!! isset($category->description) ? $category->description : '' !!}
                    </div>
                </div>
            </div>

            <div class="row"></div>

            <div class="row marg30" id="pictureSection" style="display: {{isset($category)? 'flex': 'none'}}">

                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group">
                            <label class="inputLabel"> {{__('Category Titles')}}</label>
                        </div>
                    </div>
                    <div class="row" style="width: 100%">
                        @if(isset($category->titles) && count($category->titles) != 0)
                            @for($i = 0; $i < count($category->titles); $i++)
                                <div id="title_{{$category->titles[$i]->id}}" class="col-lg-3 col-md-4">
                                    <div class="form-group" style="position: relative">
                                        <input type="text" name="titles[]" class="form-control titleInput" placeholder="Title" value="{{$category->titles[$i]->name}}" titleId="{{$category->titles[$i]->id}}" onchange="storeTitle(this)">
                                        <div class="closeTagIcon" onclick="deleteTitle(this)">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>

                                </div>
                            @endfor
                        @else
                            @for($i = 0; $i < 5; $i++)
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group" style="position: relative">
                                        <input type="text" name="titles[]" class="form-control titleInput" placeholder="Title" titleId="0" onchange="storeTitle(this)">
                                        <div class="closeTagIcon" onclick="deleteTitle(this)" >
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @endif
                        <div id="addNewTitle" class="col-lg-2 col-md-2">
                            <div class="addTagIcon">
                                <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addTitle()"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                <div class="row commonPicSection" style="width: 100%;">
                    <div class="col-md-3 centerContent marg30" style="flex-direction: column; justify-content: end">
                        <label class="inputLabel">
                            {{__('Icon For Map')}}
                        </label>
                        <label for="mainPic" class="mainPicSection">
                            <img id="mainPicImg" src="{{isset($category->icon) && $category->icon != null ? $category->icon : '#'}}" style="max-width: 100%; max-height: 100%; display: {{isset($category->icon) && $category->icon != null ? 'block' : 'none'}};" >
                            <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                            <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($category->icon) && $category->icon != null ? 'none' : 'block'}};"></i>
                        </label>

                        <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPics(this, 'mainPicImg', showMainPic)">
                    </div>
                    <div class="col-md-9 marg30">
                        <div id="uploadedPic" class="row">
                            @if(isset($category->pic) && count($category->pic) > 0)
                                @foreach($category->pic as $item)
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

                <div class="col-md-6" style="margin-top: 40px;">

                    <div style="display: flex; flex-direction: column">
                        <div>
                            <label class="inputLabel">
                                {{__('Video')}}
                                Iframe code:
                                <input type="text" id="videoEmbeded" value="{{ (isset($category->isEmbeded) && $category->isEmbeded == 1) ? $category->video : ''}}" class="form-control" onchange="changeEmbeded(this)" style="margin-top: 10px">
                                <button class="btn btn-danger" onclick="$('#videoEmbeded').val(''); $('#embedSHow').html('')">{{__('Empty Embeded')}}</button>
                            </label>
                            <div id="embedSHow" class="mainPicSection" style="display: block; height: auto">
                                @if(isset($category->isEmbeded) && $category->isEmbeded == 1)
                                    {!! $category->video !!}
                                @endif
                            </div>

                        </div>
                        {{__('OR')}}
                        <div>
                            <label class="inputLabel">
                                {{__('Video')}}
                                <label for="video" class="videoButton">
                                    {{isset($category->video) ? 'change' : 'add'}} video
                                </label>
                            </label>

                            <label class="mainPicSection">
                                <video id="videoTag" poster="placeholder.png" preload="none" controls style="width: 100%; height: 100%; display: {{isset($category->video) ? 'block' : 'none'}} ">
                                    <source id="videoSource" src="{{isset($category->video) ? $category->video : '#'}}">
                                </video>
                                <img id="videoLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                            </label>
                        </div>
                    </div>

                    <input type="file" name="video" id="video" accept="video/*" style="display: none" onchange="uploadVideo(this)">
                </div>
                <div class="col-md-6" style="margin-top: 40px;">
                    <label class="inputLabel">
                        {{__('podcast')}}
                        <label for="audio" class="videoButton">
                            {{isset($category->podcast) ? 'change' : 'add'}} podcast
                        </label>
                    </label>
                    <label style="height: 50px; width: 100%;">
                        <audio id="audioTag" preload="none" controls style="width: 100%; height: 100%; display: {{isset($category->podcast) ? 'block' : 'none'}} ">
                            <source id="audioSource" src="{{isset($category->podcast) ? $category->podcast : '#'}}">
                        </audio>
                        <img id="audioLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                    </label>

                    <input type="file" name="audio" id="audio" accept="audio/*" style="display: none" onchange="uploadAudio(this)">
                </div>
            </div>

            <div class="row marg30" style="display: flex; justify-content: center; flex-direction: column; align-items: center">
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
        <div class="modal" id="deleteTitleModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            {{__('Delete Title')}}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="deleteTitleId">
                        Do you want to delete the <span id="deleteTitleName" style="color: red"></span>? Note that all relevant text will be deleted if deleted.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                        <button type="button" class="btn btn-danger" onclick="deleteTitleSend()">{{__('Delete')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('script')

    <script>
        let categoryId = {{isset($category->id) ? $category->id : 0}};

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

        function submitForm(){
            openLoading();

            let name = $('#name').val();
            let source = $('#source').val();
            let viewOrder = $('#viewOrder').val();
            let videoEmbeded = $('#videoEmbeded').val();
            let descriptionVal =  window.editor.getData();
            var titleElement = $("input[name*='titles']");
            var titles = [];
            var error = '<ul>';

            for(i = 0; i < titleElement.length; i++){
                if($(titleElement[i]).val() != null && $(titleElement[i]).val().trim().length != 0)
                    titles[titles.length] = $(titleElement[i]).val();
            }

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(error != '<ul>'){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.store")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        description: descriptionVal,
                        viewOrder: viewOrder,
                        source: source,
                        videoEmbeded: videoEmbeded,
                        id: categoryId
                    },
                    success: function(response){
                       try{
                           response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                categoryId = response['id'];
                                resultLoading('Your Category Destination Stored', 'success', goToImagePage);
                                $('#pictureSection').css('display', 'flex');
                            }
                            else if(response['status'] == 'nok1')
                                resultLoading('Your Category Name is duplicate. Please change it', 'danger');
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
            url: "{{route('admin.destination.category.storeImg')}}",
            paramName: "pic",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    formData.append("kind", 'side');
                    formData.append("id", categoryId);
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

        function uploadPicModal(){
            $('#uploadPic').modal('show');
        }

        function goToImagePage(){
            $('#uploadPicButton').css('display', 'block');
        }

        function showMainPic(_pic){
            let mainPic = _pic;

            $('#mainPicImg').css('display', 'none');
            $('#mainPicImg').next().css('display', 'block');
            $('#mainPicImg').next().next().css('display', 'none');

            let data = new FormData();

            data.append('pic', mainPic);
            data.append('id', categoryId);
            data.append('kind', 'icon');
            data.append('_token', '{{csrf_token()}}');

            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.storeImg")}}',
                data: data,
                processData: false,
                contentType: false,
                success: function(response){
                    try{
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
                    catch (e) {
                        $('#mainPicImg').css('display', 'none');
                        $('#mainPicImg').next().css('display', 'none');
                        $('#mainPicImg').next().next().css('display', 'block');
                    }
                },
                error: function(err){
                    console.log(err);
                    $('#mainPicImg').css('display', 'none');
                    $('#mainPicImg').next().css('display', 'none');
                    $('#mainPicImg').next().next().css('display', 'block');
                }
            })
        }

        function deletePic(_id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.deleteImg")}}',
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

                let data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', categoryId);
                data.append('kind', 'video');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.storeVideoAudio")}}',
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

                let data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', categoryId);
                data.append('kind', 'audio');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.storeVideoAudio")}}',
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

        function addTitle(){
            text = '<div class="col-md-3">\n' +
                '<div class="form-group" style="position: relative">\n' +
                '<input type="text" name="titles[]" class="form-control titleInput" placeholder="{{__('Title')}}" titleId="0" onchange="storeTitle(this)"> \n' +
                '<div class="closeTagIcon" onclick="deleteTitle(this)">\n' +
                '<i class="fas fa-times"></i>\n' +
                '</div>\n' +
                '</div>\n' +
                '</div>';

            $(text).insertBefore($('#addNewTitle'));
        }

        function storeTitle(_element){
            let id = $(_element).attr('titleId');
            let title = $(_element).val();

            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.title.store")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                    categoryId: categoryId,
                    name: title
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $(_element).attr('titleId', response['id']);
                            $(_element).parent().parent().attr('id', 'title_' + response['id']);
                        }
                        else if(response['status'] == 'nok1'){
                            $(_element).val(response['lastTitle']);
                            alert('Title name Duplicate!');
                        }
                    }
                    catch(e) {
                        console.log(e);
                        $(_element).val('');
                    }
                },
                error: function(err){
                    console.log(err);
                    $(_element).val('');
                }
            })
        }

        function deleteTitle(_element){
            val = $(_element).prev().val();
            id = $(_element).prev().attr('titleId');

            $('#deleteTitleName').text(val);
            $('#deleteTitleId').val(id);

            $('#deleteTitleModal').modal('show');
        }

        function deleteTitleSend(){
            $('#deleteTitleModal').modal('hide');

            openLoading();
            let titleId = $('#deleteTitleId').val();
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.title.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: titleId,
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#title_' + titleId).remove();
                            resultLoading('Title Deleted', 'success');
                        }
                        else{
                            resultLoading('Error 3', 'danger');
                        }
                    }
                    catch (e) {
                        console.log(e);
                        resultLoading('Error 2', 'danger');
                    }
                },
                error: function(err){
                    console.log(err);
                    resultLoading('Error 1', 'danger');
                }
            })
        }

        function showPicSection(_value) {
            if (_value == 0)
                $('.commonPicSection').css('display', 'block');
            else
                $('.commonPicSection').css('display', 'none');
        }

        @if(!isset($category))
            showPicSection(0);
        @else
            showPicSection({{$category->langSource}});
        @endif


        function changeEmbeded(_element) {
            $('#embedSHow').html($(_element).val());
        }
    </script>

@endsection

