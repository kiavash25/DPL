@extends('profile.layout.profileLayout')

@section('head')
    <script src="{{asset('js/autosize.min.js')}}"></script>
    <script src="{{asset('js/ckeditor.js')}}"></script>

    <style>
        .row{
            width: 100%;
            margin: 0px;
        }
        .aboutUsDiv{
            min-height: 50vh;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            overflow: hidden;
        }
        .aboutUsText {
            width: 100%;
            color: white;
            min-height: 50vh;
            text-align: justify;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #00000042;
            font-size: 20px;
            padding: 30px;
        }

        .contactUsTextsHeader{
            font-size: 35px ;
            font-weight: bold;
            display: flex;
            color: #2c3e50;
        }
        .supportUsSection{
            width: 100%;
            margin-top: 15px;
        }
        .supportUsContent{
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }
        .supportUsDiv{
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 150px;
            margin-top: 20px;
            cursor: pointer;
        }
        .supportUsImgDiv{
            width: 120px;
            height: 120px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
        }
        .supportUsImg{
            height: 100%;
            transition: .2s;
        }
        .supportUsName{
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            margin-top: 10px;
            transition: .2s;
            color: #2c3e50;
        }
        .supportUsDiv:hover .supportUsImg{
            transform: scale(1.1);
        }
        .supportUsDiv:hover .supportUsName{
            color: #1f75b9;
        }
    </style>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                {{__('Main page setting')}}
{{--                <a href="{{route('admin.destination.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">--}}
{{--                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>--}}
{{--                </a>--}}
            </h2>
        </div>
        <hr>
        <div class="col-md-12">
            <h3>
                {{__('About us')}}

            </h3>
            <div class="row">
                <div class="container-fluid">
                    <div id="aboutusBackground" class="aboutUsDiv" style="background: black; background-image: url({{ isset($aboutUs->pic) ? $aboutUs->pic : '#'}});
                                                        background-size: cover, contain;
                                                        background-repeat: no-repeat;
                                                        background-position: center;">
                        <div class="aboutUsText">
                            <div class="container">
                                <div class="toolbar-container"></div>
                                <div id="text_aboutus" class="textEditor">
                                    {!! isset($aboutUs->text) ? $aboutUs->text : '' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="justify-content: space-evenly;">
                <button class="btn btn-success" onclick="changeHeaderText('aboutus')">{{__('Store about us text')}}</button>
                <label for="aboutusPic">
                    <div class="btn btn-warning">{{__('Change about us image')}}</div>
                </label>
                <input type="file" name="aboutusPic" id="aboutusPic" accept="image/*" style="display: none" onchange="changeAboutUsPic(this)">
            </div>
            <hr>

            <div class="row">
                <div id="centerResult" style="width: 100%;">
                    @foreach($center as $item)
                        <div id="row_{{$item->id}}" class="col-12">
                            <div id="header_{{$item->id}}" class="container" style="margin-bottom: 10px; text-align: center; font-size: 25px; font-weight: bold;">
                                {{$item->header}}
                            </div>
                            <div class="aboutUsDiv">
                                <img id="pic_{{$item->id}}" src="{{$item->pic}}" style="max-height: 100%; max-width: 100%">
                            </div>
                            <div class="row" style="justify-content: space-evenly;">
                                <button class="btn btn-primary" onclick="editCenter({{$item->id}})">{{__('Edit')}}</button>
                                <button class="btn btn-danger" onclick="deleteCenter({{$item->id}})">{{__('Delete')}}</button>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>

                <div style="width: 100%; text-align: center">
                    <div class="addTagIcon" style="margin-left: 30px; color: green" onclick="openNewCenterModal()">
                        <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="supportUsSection">
                    <div class="container supportUsCont">
                        <div class="contactUsTextsHeader">
                            {{__('Who support us')}}
                            <div class="addTagIcon" style="margin-left: 30px; color: green" onclick="openNewSupportUs()">
                                <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                            </div>
                        </div>

                        <div id="supportUsDiv" class="supportUsContent">
                            @foreach($supportUs as $item)
                                <div id="supp_{{$item->id}}" class="supportUsDiv">
                                    <div class="supportUsImgDiv">
                                        <img id="suppPic_{{$item->id}}" src="{{$item->pic}}" class="supportUsImg">
                                    </div>
                                    <a href="{{$item->link}}" id="suppName_{{$item->id}}" class="supportUsName" target="_blank">{{$item->name}}</a>
                                    <div>
                                        <button class="btn btn-primary" onclick="editSupportUs({{$item->id}})">{{__('Edit')}}</button>
                                        <button class="btn btn-danger" onclick="deleteSupportUs({{$item->id}})">{{__('Delete')}}</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal" id="newCenter">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="newHeader">{{__('Title')}}</label>
                            <input type="text" class="form-control" name="newHeader" id="newHeader" maxlength="191">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="newPic">{{__('Image')}}</label>
                            <input type="file" class="form-control" name="newPic" id="newPic" onchange="showPics(this, 'newImg', setNewPic)">
                        </div>
                    </div>
                    <div class="row">
                        <img src="" id="newImg" style="max-width: 100%; max-height: 100%;">
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <input type="hidden" id="editId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    <button type="button" class="btn btn-success" onclick="storeNewCenter()" data-dismiss="modal">{{__('Store')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="newSupportUs">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="newSupportUsName">{{__('Name')}}</label>
                            <input type="text" class="form-control" name="newSupportUsName" id="newSupportUsName" maxlength="191">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="newSupportUsLink">{{__('Link')}}</label>
                            <input type="text" class="form-control" name="newSupportUsLink" id="newSupportUsLink" maxlength="191">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="newSupportUsPic">{{__('Image')}}</label>
                            <input type="file" class="form-control" name="newSupportUsPic" id="newSupportUsPic" onchange="showPics(this, 'newSupportUsImg', setNewPicSuppUs)">
                        </div>
                    </div>
                    <div class="row">
                        <div style="width: 120px; height: 120px; overflow: hidden; display: flex; justify-content: center; align-items: center; border-radius: 50%;">
                            <img src="" id="newSupportUsImg" style="height: 100%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <input type="hidden" id="editSupportUsId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Cancel')}}</button>
                    <button type="button" class="btn btn-success" onclick="storeNewSupportUs()">{{__('Store')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Delete')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{__('Are you sure you want to remove the content :')}} <span id="deleteName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
                    <button type="button" class="btn btn-danger" onclick="doDelete()" data-dismiss="modal">{{__('Yes Deleted')}}</button>
                    <input type="hidden" id="deleteId">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let newPic = '';
        let newSupportUsPic = '';
        let centerInfo = {!! json_encode($center) !!};
        let supportUs = {!! json_encode($supportUs) !!};

        $(window).ready(function(){
            autosize($('textarea'));
        });

        DecoupledEditor.create( document.querySelector( '#text_aboutus'),{
            language: '{{app()->getLocale()}}'
        }).then( editor => {
            const toolbarContainer = document.querySelector( 'main .toolbar-container');
            toolbarContainer.prepend( editor.ui.view.toolbar.element );

            window.editor = editor;
        } )
            .catch( err => {
                console.error( err.stack );
            } );

        function changeAboutUsPic(_input){
            if(_input.files && _input.files[0]){
                let reader = new FileReader();
                reader.onload = function(e) {
                    openLoading();
                    let mainPic = e.target.result;
                    let data = new FormData();
                    data.append('pic', _input.files[0]);
                    data.append('header', 'aboutus');
                    data.append('_token', '{{csrf_token()}}');

                    $.ajax({
                        type: 'post',
                        url: '{{route("admin.setting.storeAboutUsPic")}}',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            try{
                                response = JSON.parse(response);
                                if(response['status'] == 'ok'){
                                    $('#aboutusBackground').css('background-image', 'url("' + response['url'] + '")');
                                    resultLoading("{{__('Image Change')}}", 'success');
                                }
                                else {
                                    console.log(response['status']);
                                    resultLoading("{{__('Error in store')}}", 'danger');
                                }
                            }
                            catch (e) {
                                console.log(e);
                                resultLoading("{{__('Error in result')}}", 'danger');
                            }
                        },
                        error: function(error){
                            console.log(error);
                            resultLoading("{{__('Error in Server connection')}}", 'danger');
                        }
                    })

                };
                reader.readAsDataURL(_input.files[0]);
            }
        }

        function changeHeaderText(_id){
            let text;
            if(_id != 'aboutus')
                text = $('#text_' + _id).val();
            else
                text = window.editor.getData();

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.storeAboutUs")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id,
                    text: text
                },
                success: function (response) {
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            resultLoading("{{__('Text updated')}}", 'success');
                        else {
                            console.log(response['status']);
                            resultLoading("{{__('Error in store')}}", 'danger');
                        }
                    }
                    catch (e) {
                        console.log(e);
                        resultLoading("{{__('Error in result')}}", 'danger');
                    }
                },
                error: function(error){
                    console.log(error);
                    resultLoading("{{__('Error in Server connection')}}", 'danger');
                }
            })
        }

        function setNewPic(_pic){
            newPic = _pic;
        }

        function openNewCenterModal(){
            $('#newImg').attr('src', '');
            $('#newPic').val('');
            $('#newHeader').val('');
            $('#editId').val(0);
            newPic = '';
            $('#newCenter').modal({backdrop: 'static', keyboard: false});
        }

        function storeNewCenter(){
            let header = $('#newHeader').val();
            let id = $('#editId').val();

            if(header.trim().length > 0 && ((id == 0 && newPic != '') || id != 0)){
                let formData = new FormData();
                formData.append('_token', '{{csrf_token()}}');
                formData.append('pic', newPic);
                formData.append('header', header);
                formData.append('id', id);
                openLoading();

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.setting.storeCenterHeaderPic")}}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response.status == 'ok'){
                                resultLoading("{{__('Store')}}", 'success');
                                console.log(response);
                                if(id == 0)
                                    createNewRow(response.result);
                                else
                                    updateRow(response.result)
                            }
                            else{
                                console.log(response.status);
                                resultLoading("Error 3", 'danger');
                            }
                        }
                        catch (e) {
                            console.log(e);
                            resultLoading("Error 2", 'danger');
                        }
                    },
                    error: function(err){
                        console.log(err);
                        resultLoading("Error 1", 'danger');
                    }
                })
            }
        }

        function editCenter(_id){
            let cent = null;
            centerInfo.forEach(item => {
                if(item.id == _id)
                    cent = item;
            });

            if(cent != null) {
                $('#newImg').attr('src', cent.pic);
                $('#newPic').val('');
                $('#newHeader').val(cent.header);
                $('#editId').val(cent.id);
                newPic = '';
                $('#newCenter').modal({backdrop: 'static', keyboard: false});
            }
        }

        function updateRow(_result){
            _result = JSON.parse(_result);
            centerInfo.forEach(item => {
                if(item.id == _result.id){
                    item.pic = _result.pic;
                    item.header = _result.header;
                }
            });

            $('#header_' + _result.id).text(_result.header);
            $('#pic_' + _result.id).attr('src', _result.pic);
        }

        function createNewRow(_result){
            _result = JSON.parse(_result);

            let text = '<div id="row_' + _result.id + '" class="col-12">\n' +
                '                            <div class="container" style="margin-bottom: 10px; text-align: center; font-size: 25px; font-weight: bold;">' + _result.header + '</div>\n' +
                '                            <div class="aboutUsDiv">\n' +
                '                                <img src="' + _result.pic + '" style="max-height: 100%; max-width: 100%">\n' +
                '                            </div>\n' +
                '                            <div class="row" style="justify-content: space-evenly;">\n' +
                '                                <button class="btn btn-primary" onclick="editCenter(' + _result.id + ')">{{__('Edit')}}</button>\n' +
                '                                <button class="btn btn-danger" onclick="deleteCenter(' + _result.id + ')">{{__('Delete')}}</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                        <hr>';

            $('#centerResult').append(text);
        }

        function deleteCenter(_id){
            let cent = null;
            centerInfo.forEach(item => {
                if(item.id == _id)
                    cent = item;
            });

            if(cent != null){
                $('#deleteName').text(cent.header) ;
                $('#deleteId').val(cent.id);

                $('#deleteModal').modal('show');
            }
        }

        function doDelete(){
            let id = $('#deleteId').val();

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.deleteCenterHeaderPic")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response.status == 'ok') {
                            $('#row_' + id).remove();
                            resultLoading('Deleted', 'success');
                        }
                        else
                            resultLoading(response.status, 'danger');
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


        function openNewSupportUs(){
            $('#editSupportUsId').val(0);
            newSupportUsPic = '';

            $('#newSupportUsName').val('');
            $('#newSupportUsLink').val('');
            $('#newSupportUsPic').val('');
            $('#newSupportUsImg').attr('href', '');

            $('#newSupportUs').modal({backdrop: 'static', keyboard: false});
        }

        function setNewPicSuppUs(_pic){
            newSupportUsPic = _pic;
        }

        function storeNewSupportUs(){
            let id = $('#editSupportUsId').val();
            let name = $('#newSupportUsName').val();
            let link = $('#newSupportUsLink').val();

            if(name.trim().length > 0 && ((id == 0 && newSupportUsPic != '') || id != 0)){
                let formData = new FormData();
                formData.append('_token', '{{csrf_token()}}');
                formData.append('id', id);
                formData.append('pic', newSupportUsPic);
                formData.append('name', name);
                formData.append('link', link);
                openLoading();

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.setting.supportUs.store")}}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response.status == 'ok'){
                                resultLoading("{{__('Store')}}", 'success');
                                console.log(response.result);
                                if(id == 0)
                                    createSupportUs(response.result);
                                else
                                    updateSupportUs(response.result)
                            }
                            else{
                                console.log(response.status);
                                resultLoading("Error 3", 'danger');
                            }
                        }
                        catch (e) {
                            console.log(e);
                            resultLoading("Error 2", 'danger');
                        }
                    },
                    error: function(err){
                        console.log(err);
                        resultLoading("Error 1", 'danger');
                    }
                })
            }
            else
                alert("error")
        }

        function createSupportUs(_result){
            _result = JSON.parse(_result);
            let _id   = _result.id;
            let _name = _result.name;
            let _link = _result.link;
            let _pic  = _result.pic;

            let text =  '<div id="supp_' + _id + '" class="supportUsDiv">\n' +
                        '                                <div class="supportUsImgDiv">\n' +
                        '                                    <img id="suppPic_' + _id + '" src="' + _pic + '" class="supportUsImg">\n' +
                        '                                </div>\n' +
                        '                                <a href="' + _link + '" id="suppName_' + _id + '" class="supportUsName"  target="_blank">' + _name + '</a>\n' +
                        '                                <div>\n' +
                        '                                    <button class="btn btn-primary" onclick="editSupportUs(' + _id + ')">{{__('Edit')}}</button>\n' +
                        '                                    <button class="btn btn-danger" onclick="deleteSupportUs(' + _id + ')">{{__('Delete')}}</button>\n' +
                        '                                </div>\n' +
                        '                            </div>';

            $('#supportUsDiv').append(text);
        }

        function updateSupportUs(_result){
            _result = JSON.parse(_result);
            supportUs.forEach(item => {
                if(item.id == _result.id){
                    item.pic = _result.pic;
                    item.name = _result.name;
                    item.link = _result.link;
                }
            });

            $('#suppName_' + _result.id).text(_result.name);
            $('#suppPic_' + _result.id).attr('src', _result.pic);
            $('#suppName_' + _result.id).attr('href', _result.link);
        }

        function deleteSupportUs(_id){
            openLoading();

            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.supportUs.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    if(response == 'ok') {
                        $('#supp_' + _id).remove();
                        resultLoading('{{__('Deleted')}}', 'success');
                    }
                    else
                        resultLoading(response, 'danger');
                },
                error: function(err){
                    console.log(err);
                    resultLoading('Error 1', 'danger');
                }
            })
        }

        function editSupportUs(_id){
            let supUs = null;
            supportUs.forEach(item => {
                if(item.id == _id)
                    supUs = item;
            });

            if(supUs != null) {
                newSupportUsPic = '';
                $('#editSupportUsId').val(supUs.id);
                $('#newSupportUsName').val(supUs.name);
                $('#newSupportUsLink').val(supUs.link);
                $('#newSupportUsPic').val('');
                $('#newSupportUsImg').attr('href', supUs.pic);

                $('#newSupportUs').modal({backdrop: 'static', keyboard: false});
            }
        }
    </script>
@endsection
