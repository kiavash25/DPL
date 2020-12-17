@extends('profile.layout.profileLayout')

@section('head')

    <link rel="stylesheet" type="text/css" href="{{asset('css/admin/newPackage.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('css/dropZone/dropzone.css')}}">

    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone.js')}}"></script>
    <script src="{{asset('js/dropZone/dropzone-amd-module.js')}}"></script>

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
                @if(isset($nat))
                    {{__('Edit')}} {{__('Nature friend')}} : {{$nat->name}}
                @else
                    {{__('Create new Nature friend')}}
                @endif
            </h2>

            <div class="form-group" style="width: auto; margin-right: 30px; display: {{app()->getLocale() != 'en' ? 'block': 'none'}}">
                <label for="source">{{__('Source')}}</label>
                <select name="source" id="source" class="form-control" onchange="showPicSection(this.value)">
                    <option value="0" {{isset($nat->langSource) && $nat->langSource == 0 ? 'selected' : ''}}>{{__('New')}}</option>
                    @foreach($sourceParent as $s)
                        <option value="{{$s->id}}" {{isset($nat->langSource) && $nat->langSource == $s->id ? 'selected' : ''}}>{{$s->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <hr>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="natName" class="inputLabel">{{__('Nature friend name')}}</label>
                    <input type="text" id="natName" name="natName" class="form-control" placeholder="Nature friend name" value="{{isset($nat->name) ? $nat->name : ''}}">
                </div>
            </div>
            <div class="row marg30">
                <div class="form-group">
                    <label for="natDescription" class="inputLabel">{{__('Nature friend description')}}</label>
                    <div class="toolbar-container"></div>
                    <div id="natDescription" class="textEditor" >
                        {!! isset($nat->description) ? $nat->description : '' !!}
                    </div>
                </div>
            </div>
            <div class="row marg30" id="pictureSection" style="display: {{isset($nat)? 'flex': 'none'}}">
                <div class="col-md-3 centerContent relatedSection" style="flex-direction: column; justify-content: end">
                    <label class="inputLabel">
                        {{__('Main Picture')}}
                    </label>
                    <label for="mainPic" class="mainPicSection">
                        <img id="mainPicImg" src="{{isset($nat->pic) && $nat->pic != null ? $nat->pic : '#'}}" style="width: 100%; display: {{isset($nat->pic) && $nat->pic != null ? 'block' : 'none'}};" >
                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($nat->pic) && $nat->pic != null ? 'none' : 'block'}};"></i>
                    </label>
                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPic(this, 'mainPic')">
                </div>
                <div class="col-md-9 relatedSection">
                    <div id="uploadedPic" class="row">
                        @if(isset($nat->sidePic) && count($nat->sidePic) > 0)
                            @foreach($nat->sidePic as $item)
                                <div class="col-md-3 uploadedPic">
                                    <img src="{{$item->pic}}" class="uploadedPicImg">
                                    <div class="uploadedPicHover">
                                        <button class="btn btn-primary" data-alt="{{$item->alt}}" onclick="openAltModal({{$item->id}}, this)">{{__('alt')}} : {{$item->alt}}</button>
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
            </div>

            <div class="row marg30" id="videoSection" style="display: {{isset($nat) ? 'flex': 'none'}}">
                <div class="col-md-6" style="margin-top: 40px;">
                    <div style="display: flex; flex-direction: column">
                        <div>
                            <label class="inputLabel">
                                {{__('Video')}}
                                Iframe code:
                                <input type="text" id="videoEmbeded" value="{{(isset($nat->isEmbeded) && $nat->isEmbeded == 1) ? $nat->video : ''}}" class="form-control" onchange="changeEmbeded(this)" style="margin-top: 10px">
                                <button class="btn btn-danger" onclick="$('#videoEmbeded').val(''); $('#embededSHow').html('')">{{__('Empty Embeded')}}</button>
                            </label>
                            <div id="embededSHow" class="mainPicSection" style="display: block; height: auto">
                                @if(isset($nat->isEmbeded) && $nat->isEmbeded == 1)
                                    {!! $nat->video !!}
                                @endif
                            </div>
                        </div>
                        {{__('OR')}}
                        <div>
                            <label class="inputLabel">
                                {{__('Video')}}
                                <label for="video" class="videoButton">
                                    {{isset($nat->video) ? 'change' : 'add'}} video
                                </label>
                            </label>

                            <label class="mainPicSection">
                                <video id="videoTag" poster="placeholder.png" preload="none" controls style="width: 100%; height: 100%; display: {{isset($nat->video) ? 'block' : 'none'}} ">
                                    <source id="videoSource" src="{{isset($nat->video) ? $nat->video : '#'}}">
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
                            {{isset($nat->podcast) ? 'change' : 'add'}} podcast
                        </label>
                    </label>
                    <label style="height: 50px; width: 100%;">
                        <audio id="audioTag" preload="none" controls style="width: 100%; height: 100%; display: {{isset($nat->podcast) ? 'block' : 'none'}} ">
                            <source id="audioSource" src="{{isset($nat->podcast) ? $nat->podcast : '#'}}">
                        </audio>
                        <img id="audioLoader" src="{{asset('images/mainImage/loading.gif')}}" style="height: 100%; display: none;" >
                    </label>

                    <input type="file" name="audio" id="audio" accept="audio/*" style="display: none" onchange="uploadAudio(this)">
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

        <div id="pictureAltModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{__('Choose a photo alt')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="$('#pictureAltModal').modal('hide')">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="margin: 0px;">
                            <div class="form-group">
                                <label for="newAlt">{{__('alt')}}</label>
                                <input type="text" id="newAlt" class="form-control">
                            </div>
                        </div>
                        <div class="row" style="justify-content: center">
                            <button class="btn btn-success" onclick="storePictureAlt()">{{__('Submit')}}</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#pictureAltModal').modal('hide')">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var pictureAltElement = null;
            var pictureAltId = 0;
            function openAltModal(_id, _element){
                $('#pictureAltModal').modal('show');
                $('#pictureAltModal').modal({backdrop: 'static', keyboard: false});
                $('#newAlt').val($(_element).attr('data-alt'));
                pictureAltElement = _element;
                pictureAltId = _id;
            }

            function storePictureAlt(){
                var value = $('#newAlt').val();
                if(value.trim().length > 1) {
                    openLoading();
                    $.ajax({
                        type: 'POST',
                        url: '{{route("admin.natureFriend.storeAltImg")}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            id: pictureAltId,
                            alt: value.trim(),
                        },
                        success: response =>{
                            if(response.status == 'ok') {
                                pictureAltId = 0;
                                $(pictureAltElement).attr('data-alt', value.trim()).text('alt: ' + value.trim());
                                resultLoading('Alt Stored', 'success');
                                $('#pictureAltModal').modal('hide');
                            }
                            else
                                resultLoading(response.status, 'danger');
                        },
                        error: err =>{
                            resultLoading('Error', 'danger');
                        }
                    });
                }
            }
        </script>

    </div>

@endsection


@section('script')

    <script>
        DecoupledEditor.create( document.querySelector('#natDescription'), {
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

        let natId = {{isset($nat) ? $nat->id : 0}};
        let lang = '{{app()->getLocale()}}';

        function submitForm(){
            openLoading();

            var name = $('#natName').val();
            var source = $('#source').val();
            var description = window.editor.getData();
            var videoEmbeded = $('#videoEmbeded').val();
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
                    url: '{{route("admin.natureFriend.store")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        name: name,
                        description: description,
                        videoEmbeded: videoEmbeded,
                        source: source,
                        lang: lang,
                        id: natId
                    },
                    success: function(response){
                        try {
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                natId = response['id'];
                                resultLoading('{{__("Your nature friend stored")}}', 'success', goToImagePage);
                                showPicSection(source);
                                $('#videoSection').css('display', 'flex');
                            }
                            else if(response['status'] == 'duplicate')
                                resultLoading('{{__("Your nature friend name is duplicate")}}', 'danger');
                            else
                                resultLoading('{{__("error.tryAgain")}}', 'danger');
                        }
                        catch (e) {
                            resultLoading('{{__("error.tryAgain")}}', 'danger');
                        }
                    },
                    error: function(err){
                        resultLoading('{{__("error.tryAgain")}}', 'danger');
                    }
                })
            }
        }

        let myDropzone = new Dropzone("div#dropzone", {
            url: "{{route('admin.natureFriend.storeImg')}}",
            paramName: "pic",
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            init: function() {
                this.on("sending", function(file, xhr, formData){
                    formData.append("kind", 'side');
                    formData.append("id", natId);
                });
            },
        }).on('success', function(file, response){
            response = JSON.parse(response);
            if(response['status'] == 'ok'){
                let text =  '<div class="col-md-3 uploadedPic">\n' +
                            '   <img src="' + file['dataURL'] + '" class="uploadedPicImg">\n' +
                            '   <div class="uploadedPicHover">\n' +
                            '       <button class="btn btn-primary" data-alt="" onclick="openAltModal('+response['id']+', this)">{{__("alt")}} : </button>\n' +
                            '       <button class="btn btn-danger" onclick="deletePic(' + response['id'] + ', this)">{{__('delete')}}</button>\n' +
                            '   </div>\n' +
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
                    data.append('id', natId);
                    data.append('kind', 'mainPic');
                    data.append('_token', '{{csrf_token()}}');

                    $.ajax({
                        type: 'post',
                        url: '{{route("admin.natureFriend.storeImg")}}',
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

        function uploadAudio(_element){

            if(_element.files && _element.files[0]) {

                $('#audioLoader').css('display', 'block');
                $('#audioTag').css('display', 'none');

                var data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', natId);
                data.append('kind', 'audio');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.natureFriend.storeVideoPodcast")}}',
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

        function uploadVideo(_element){

            if(_element.files && _element.files[0]) {

                $('#videoLoader').css('display', 'block');
                $('#videoTag').css('display', 'none');

                var data = new FormData();

                data.append('file', _element.files[0]);
                data.append('id', natId);
                data.append('kind', 'video');
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.natureFriend.storeVideoPodcast")}}',
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

        function deletePic(_id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("admin.natureFriend.deleteImg")}}',
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

        function showPicSection(_value) {
            if (_value == 0)
                $('.relatedSection').css('display', 'flex');
            else
                $('.relatedSection').css('display', 'none');
        }

        @if(!isset($nat))
            showPicSection(0);
        @else
            showPicSection({{$nat->langSource}});
        @endif

    </script>
@endsection

