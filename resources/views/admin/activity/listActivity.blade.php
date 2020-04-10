@extends('admin.layout.adminLayout')

@section('head')
    <style>
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
        .closeIcon2{
            color: white;
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

        .addIcon{
            color: green;
            font-size: 50px;
        }
    </style>
@endsection

@section('body')
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th>Activity Name</th>
            <th>Activity Icon</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="tableBody">
            @foreach($activity as $item)
                <tr id="activity{{$item->id}}">
                    <td>
                        <div id="activityName{{$item->id}}">
                            {{$item->name}}
                        </div>
                        <div id="activityNameInput{{$item->id}}" style="display: none;">
                            <input type="text" id="nameInput{{$item->id}}" class="form-control" value="{{$item->name}}">
                        </div>
                    </td>
                    <td>
                        <div id="activityPic{{$item->id}}">
                            <img id="activityIcon{{$item->id}}" src="{{$item->icon}}" style="width: 50px; height: 50px;">
                        </div>
                        <div id="activityPicInput{{$item->id}}" style="display: none; cursor: pointer" onclick="showEditIcon(this, {{$item->id}})">
                            <img id="picInputShow{{$item->id}}" src="{{$item->icon}}" style="width: 50px; height: 50px; cursor: pointer">
                        </div>
                    </td>
                    <td>
                        <div style="display: none;">
                            <button class="btn btn-success" onclick="submitEdit(this, {{$item->id}})">submit</button>
                            <button class="btn btn-secondary" onclick="cancelEdit(this, {{$item->id}})">cancel</button>
                        </div>
                        <div style="display: flex">
                            <button class="btn btn-primary" onclick="editActivity(this, {{$item->id}})">edit</button>
                            <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}')">delete</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="row" style="justify-content: center">
        <div class="addIcon">
            <i class="fas fa-plus-circle" style="cursor: pointer" onclick="$('#newActivity').modal('show')"></i>
        </div>
    </div>

    <div class="modal" id="newActivity">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Activity</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="newName">Activity Name</label>
                                <input type="text" class="form-control" name="newName" id="newName">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label >Icon (Optional)</label>
                            <label for="newIcon" class="mainPicSection">
                                <img src="#" id="mainIcon" style="display: none; width: auto">
                                <i class="fas fa-plus-circle" style="cursor: pointer;"></i>
                            </label>

                            <input type="file" id="newIcon" style="display: none;" onchange="showPics(this, 'mainIcon', disableIcon)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="submitActivity()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="editIcon">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Icon</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label >Last Icon</label>
                            <label class="mainPicSection">
                                <img id="editIconImg" src="" style="width: 200px; height: 200px">
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label >New Icon</label>
                            <label for="newIconEdit" class="mainPicSection">
                                <img src="#" id="mainIconEdit" style="display: none; width: 200px; height: 200px;">
                                <i class="fas fa-plus-circle" style="cursor: pointer;"></i>
                            </label>
                            <input type="file" id="newIconEdit" style="display: none;" onchange="showPics(this, 'mainIconEdit', disableEditIcon)">
                            <input type="hidden" id="editIconId" >
                        </div>
                        <div class="col-md-12" style="justify-content: center; display: flex">
                            <button class="btn btn-danger" onclick="deleteIcon()">Delete Icon</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" onclick="closeEditIconModal()">Close</button>
                    <button type="button" class="btn btn-success" onclick="submitEditIcon()">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Activity</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        Are you sure you want to remove the <span id="deletedActivityName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" onclick="checkActivity()" data-dismiss="modal">Yes Deleted</button>
                    <input type="hidden" id="deletedActivityId">
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>

        var newIcon;
        var activity = {!! $activity !!};
        var editedIcon = 0;

        function submitActivity(){
            var name = $('#newName').val();

            if(name.trim().length != 0){
                openLoading();

                var data = new FormData();

                data.append('name', name);
                data.append('icon', newIcon);
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.store")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        var nnIcon = newIcon;
                        hideModal();
                        try {
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                var _id = response['id'];
                                var reader = new FileReader();
                                console.log(newIcon)
                                console.log(reader);
                                reader.onload = function(e) {
                                    var pic = e.target.result;
                                    text = '<tr id="activity' + _id + '">\n' +
                                        '                    <td>\n' +
                                        '                        <div id="activityName' + _id + '">' + name + '</div>\n' +
                                        '                        <div id="activityNameInput' + _id + '" style="display: none;">\n' +
                                        '                            <input type="text" id="nameInput' + _id + '" class="form-control" value="' + name + '">\n' +
                                        '                        </div>\n' +
                                        '                    </td>\n' +
                                        '                    <td>\n' +
                                        '                        <div id="activityPic' + _id + '">\n' +
                                        '                            <img id="activityIcon' + _id + '" src="' + pic + '" style="width: 50px; height: 50px;">\n' +
                                        '                        </div>\n' +
                                        '                        <div id="activityPicInput' + _id + '" style="display: none; cursor: pointer" onclick="showEditIcon(this, ' + _id + ')">\n' +
                                        '                            <img id="picInputShow' + _id + '" src="' + pic + '" style="width: 50px; height: 50px; cursor: pointer">\n' +
                                        '                        </div>\n' +
                                        '                    </td>\n' +
                                        '                    <td>\n' +
                                        '                        <div style="display: none;">\n' +
                                        '                            <button class="btn btn-success" onclick="submitEdit(this, ' + _id + ')">submit</button>\n' +
                                        '                            <button class="btn btn-secondary" onclick="cancelEdit(this, ' + _id + ')">cancel</button>\n' +
                                        '                        </div>\n' +
                                        '                        <div style="display: flex">\n' +
                                        '                            <button class="btn btn-primary" onclick="editActivity(this, ' + _id + ')">edit</button>\n' +
                                        '                            <button class="btn btn-danger" onclick="openDeletedModal(' + _id + ', \'' + name + '\')">delete</button>\n' +
                                        '                        </div>\n' +
                                        '                    </td>\n' +
                                        '                </tr>';

                                    $('#tableBody').append(text);
                                    resultLoading('Activity Stored', 'success');
                                };
                                reader.readAsDataURL(nnIcon);
                            }
                            else if(response['status'] == 'nok1'){
                                resultLoading('Your name is repeated', 'danger');
                            }
                        }
                        catch (e) {
                            if(response['status'] == 'ok'){
                                text = '<tr id="activity' + _id + '">\n' +
                                    '                    <td>\n' +
                                    '                        <div id="activityName' + _id + '">' + name + '</div>\n' +
                                    '                        <div id="activityNameInput' + _id + '" style="display: none;">\n' +
                                    '                            <input type="text" id="nameInput' + _id + '" class="form-control" value="' + name + '">\n' +
                                    '                        </div>\n' +
                                    '                    </td>\n' +
                                    '                    <td>\n' +
                                    '                        <div id="activityPic' + _id + '">\n' +
                                    '                            <img id="activityIcon' + _id + '" src="#" style="width: 50px; height: 50px;">\n' +
                                    '                        </div>\n' +
                                    '                        <div id="activityPicInput' + _id + '" style="display: none; cursor: pointer" onclick="showEditIcon(this, ' + _id + ')">\n' +
                                    '                            <img id="picInputShow' + _id + '" src="#" style="width: 50px; height: 50px; cursor: pointer">\n' +
                                    '                        </div>\n' +
                                    '                    </td>\n' +
                                    '                    <td>\n' +
                                    '                        <div style="display: none;">\n' +
                                    '                            <button class="btn btn-success" onclick="submitEdit(this, ' + _id + ')">submit</button>\n' +
                                    '                            <button class="btn btn-secondary" onclick="cancelEdit(this, ' + _id + ')">cancel</button>\n' +
                                    '                        </div>\n' +
                                    '                        <div style="display: flex">\n' +
                                    '                            <button class="btn btn-primary" onclick="editActivity(this, ' + _id + ')">edit</button>\n' +
                                    '                            <button class="btn btn-danger" onclick="openDeletedModal(' + _id + ', \'' + name + '\')">delete</button>\n' +
                                    '                        </div>\n' +
                                    '                    </td>\n' +
                                    '                </tr>';

                                $('#tableBody').append(text);
                                resultLoading('Activity Stored', 'success');
                            }
                            else
                                resultLoading('Problem in server', 'danger');
                        }
                    },
                    error: function(response){
                        hideModal();
                        resultLoading('Problem in server', 'danger');
                    }
                })
            }
        }

        function hideModal(){
            newIcon = null;

            $('#newActivity').modal('hide');
            $('#newName').val('');
            $('#mainIcon').css('display', 'none');
            $('#mainIcon').next().css('display', 'block');
        }

        function disableIcon(_icon){
            newIcon = _icon;
            $('#mainIcon').css('display', 'block');
            $('#mainIcon').next().css('display', 'none');
        }


        function editActivity(_element, _id){
            $(_element).parent().css('display', 'none');
            $(_element).parent().prev().css('display', 'flex');

            $('#activityName' + _id).css('display', 'none');
            $('#activityNameInput' + _id).css('display', 'block');
            $('#activityPic' + _id).css('display', 'none');
            $('#activityPicInput' + _id).css('display', 'flex');
        }

        function cancelEdit(_element, _id){
            $(_element).parent().css('display', 'none');
            $(_element).parent().next().css('display', 'flex');

            $('#activityName' + _id).css('display', 'block');
            $('#activityNameInput' + _id).css('display', 'none');
            $('#activityPic' + _id).css('display', 'block');
            $('#activityPicInput' + _id).css('display', 'none');
        }

        function disableEditIcon(_icon){
            editedIcon = _icon;
            $('#mainIconEdit').css('display', 'block');
            $('#mainIconEdit').next().css('display', 'none');
        }

        function submitEditIcon(){
            var id = $('#editIconId').val()
            if(id.trim().length > 0 && editedIcon != 0){
                openLoading();
                closeEditIconModal();

                var data = new FormData();

                data.append('id', id);
                data.append('icon', editedIcon);
                data.append('kind', 'editIcon');
                data.append('_token', '{{csrf_token()}}');
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.doEdit")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                resultLoading('Activity Stored', 'success');
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    var pic = e.target.result;
                                    $('#activityIcon' + id).attr('src', pic);
                                    $('#picInputShow' + id).attr('src', pic);
                                    editedIcon = 0;
                                };
                                reader.readAsDataURL(editedIcon);
                            }
                        }
                        catch (e) {
                            resultLoading('!!!!', 'danger');
                        }
                    },
                    error: function () {
                        resultLoading('!!!!', 'danger');
                    }
                })
            }
        }

        function deleteIcon(){
            var id = $('#editIconId').val();
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.activity.doEdit")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: id,
                    kind: 'deleteIcon'
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            resultLoading('Icon Deleted', 'success');
                            closeEditIconModal();
                            $('#activityIcon' + id).attr('src', '');
                            $('#picInputShow' + id).attr('src', '');
                        }
                        else{
                            resultLoading('!!!!', 'danger');
                        }
                    }
                    catch (e) {
                        console.log(e);
                        resultLoading('!!!!', 'danger');
                    }
                },
                error: function(){
                    resultLoading('!!!!', 'danger');
                }
            })
        }

        function showEditIcon(_element, _id){
            var icon = $($(_element).prev().children()[0]).attr('src');
            $('#editIconImg').attr('src', icon);
            $('#editIconId').val(_id);
            $('#editIcon').modal('show');
        }

        function closeEditIconModal(){
            $('#editIcon').modal('hide');
            $('#editIconImg').attr('src', '');
            $('#editIconId').val(0);

            $('#mainIconEdit').css('display', 'none');
            $('#mainIconEdit').next().css('display', 'block');

            $('#newIconEdit').val('');
            $('#newIconEdit').files = null;
        }

        function submitEdit(_element, _id){
            var name = $('#nameInput' + _id).val();
            if(name.trim().length != 0){
                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.activity.doEdit")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id,
                        name: name,
                        kind: 'editName'
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                resultLoading('Name Edited', 'success');
                                $('#activityName' + _id).text(name);
                            }
                            else if(response['status'] == 'repeated')
                                resultLoading('Your name is repeated', 'danger');
                            else
                                resultLoading('!!!!', 'danger');
                        }
                        catch (e) {
                            console.log(e);
                            resultLoading('!!!!', 'danger');
                        }
                    },
                    error: function(){
                        resultLoading('!!!!', 'danger');
                    }
                })
            }
        }

        function openDeletedModal(_id, _name){
            $('#deletedActivityId').val(_id);
            $('#deletedActivityName').text(_name);
            $('#deleteModal').modal('show');
        }

        function checkActivity(){
            openLoading();
            var _id = $('#deletedActivityId').val();
            $.ajax({
                type: 'post',
                url: '{{route("admin.activity.check")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id,
                },
                success: function(response){
                    try {
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            deleteActivity();
                        else if(response['status'] == 'nok2'){
                            text = '<div style="display: flex; flex-direction: column; max-height: 65vh; overflow-y: auto">';

                            if(response['main'].length == 0){
                                text += '<div>This Activity use for Side Activity of These Packages And When you delete this activity, these activity are deleted:</div>';
                                for(i = 0; i < response['side'].length; i++)
                                    text += '<a href="' + response["side"][i]["url"] + '" target="_blank">' + response["side"][i]["name"] + '</a>';

                                text += '</div>';
                                resultLoading(text, 'warning', deleteActivity);
                            }
                            else{
                                text += '<div style="">This Activity use for MainActivity of These Packages And You cant Delete this Activity:</div>';
                                for(i = 0; i < response['main'].length; i++)
                                    text += '<a href="' + response["main"][i]["url"] + '" target="_blank" style="width: 100%;">' + response["main"][i]["name"] + '</a>';
                                text += '</div>';
                                resultLoading(text, 'danger');
                            }
                        }
                        else
                            resultLoading('Error 1', 'danger');
                    }
                    catch (e) {
                        console.log(e);
                        resultLoading('Error 2', 'danger');
                    }
                },
                error: function(){
                    resultLoading('Error 3', 'danger');
                }
            })
        }

        function deleteActivity(){
            var _id = $('#deletedActivityId').val();
            $.ajax({
                type: 'post',
                url: '{{route("admin.activity.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            resultLoading('Activity Deleted', 'success');
                            $('#activity' + _id).remove();
                        }
                        else if(response['status'] == 'mainError'){
                            resultLoading('This Activity used in mainActivity of some Packages', 'danger');
                        }
                        else{
                            resultLoading('Error 4', 'danger');
                        }
                    }
                    catch (e) {
                        resultLoading('Error 5', 'danger');
                    }
                },
                error: function(){
                    resultLoading('Error 6', 'danger');
                }
            })
        }
    </script>
@endsection

