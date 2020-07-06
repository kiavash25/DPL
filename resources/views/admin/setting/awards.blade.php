@extends('admin.layout.adminLayout')

@section('head')

    <style>
        .row{
            width: 100%;
            margin: 0px;
        }
        .picSection{
            /*display: flex;*/
            /*align-items: center;*/
            /*flex-wrap: wrap;*/
        }
        .picDiv{
            padding: 5px;
            height: 200px;
            position: relative;
            border-bottom: solid 1px gray;
        }
        .picSetting{
            position: absolute;
            width: 100%;
            top: 0px;
            right: 0px;
            height: 0;
            transition: .2s;
            background: #000000ba;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        /*.picDiv:hover .picSetting{*/
        /*    height: 100%;*/
        /*    padding: 15px;*/
        /*}*/
    </style>

@endsection

@section('body')

    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                {{__('Awards')}}
            </h2>
        </div>
        <hr>
        <div class="col-md-12">
            <div class="picSection">
                @foreach($awards as $item)
                    <div id="picDiv_{{$item->id}}" class="row picDiv">
                        <div class="col-4" style="height: 100%">
                            <img src="{{$item->pic}}" id="pic_{{$item->id}}" style="max-height: 100%; max-width: 100%;">
                        </div>
                        <div class="col-8" style="text-align: right">
                            <div class="row">
                                <span style="font-weight: bold; margin: 0px 10px">{{__('Name')}}:</span>
                                <span id="name_{{$item->id}}">{{$item->name}}</span>
                            </div>
                            <div class="row">
                                <span style="font-weight: bold; margin: 0px 10px">{{__('Link')}}:</span>
                                <a id="link_{{$item->id}}" href="{{$item->link}}" target="_blank">{{$item->link}}</a>
                            </div>
                            <div class="row">
                                <button class="btn btn-primary" onclick="openEdit({{$item->id}})">{{__('Edit')}}</button>
                                <button class="btn btn-danger" onclick="deletePic({{$item->id}})">{{__('Delete')}}</button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="col-md-12">
            <label for="addPic" class="addTagIcon" style="display: flex; justify-content: center; align-items: center;" onclick="newAward()">
                <i class="fas fa-plus-circle" style="cursor: pointer"></i>
            </label>
        </div>
    </div>

    <div class="modal" id="awardEdit">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="newName">{{__('Name')}}</label>
                            <input type="text" class="form-control" name="newName" id="newName" maxlength="255">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label for="newLink">{{__('Link')}}</label>
                            <input type="text" class="form-control" name="newLink" id="newLink">
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
                    <button type="button" class="btn btn-success" onclick="storeSlider()">{{__('Store')}}</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        let awards = {!! $awards !!}
        let newPic = null;

        function deletePic(_id){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.award.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function (response) {
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            $('#picDiv_' + _id).remove();
                            resultLoading('{{__('Deleted')}}', 'success');
                        }
                        else
                            resultLoading('Error 1', 'danger');
                    }
                    catch (e) {
                        console.error(e)
                        resultLoading('Error 2', 'danger');
                    }
                },
                error: function (e) {
                    console.error(e)
                    resultLoading('Error 3', 'danger');
                }
            })
        }

        function openEdit(_id){
            let award = null;
            awards.forEach(item => {
                if(item.id == _id)
                    award = item;
            });
            newPic = null;
            $('#editId').val(award.id);
            $('#newName').val(award.text);
            $('#newLink').val(award.link);
            $('#newImg').attr('src', award.pic);

            $('#awardEdit').modal('show')
        }

        function newAward(){
            newPic = null;
            $('#editId').val(0);
            $('#newName').val('');
            $('#newLink').val('');
            $('#newImg').attr('src', '#');

            $('#awardEdit').modal('show')
        }

        function setNewPic(_pic){
            newPic = _pic;
        }

        function storeSlider(){
            let id = $('#editId').val();
            let name = $('#newName').val();
            let link = $('#newLink').val();

            let formData = new FormData();
            formData.append('_token', '{{csrf_token()}}');
            formData.append('pic', newPic);
            formData.append('name', name);
            formData.append('link', link);
            formData.append('id', id);
            openLoading();

            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.awardStore")}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response.status == 'ok'){
                            resultLoading("{{__('Store')}}", 'success');
                            if(id == 0)
                                location.reload();
                            else{
                                awards.forEach(item => {
                                    if(item.id == id)
                                        item = response.result;
                                });
                                $('#name_' + id).text(response.result.text);
                                $('#link_' + id).text(response.result.link);
                                $('#link_' + id).attr('href', response.result.link);
                                $('#pic_' + id).attr('src', response.result.pic);
                                $('#awardEdit').modal('hide');
                            }
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
    </script>
@endsection

