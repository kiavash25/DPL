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


        .circleIcon{
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 22px;
            cursor: pointer;
        }
    </style>
@endsection

<tr>
@section('body')
    <table class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>Category Name</th>
            <th>Map Icon</th>
            <th>Titles</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="addNewCategory">
        @foreach($category as $item)
            <tr id="category{{$item->id}}">
                <td>
                    <div id="activityName{{$item->id}}">
                        {{$item->name}}
                    </div>
                    <div id="activityNameInput{{$item->id}}" style="display: none;">
                        <input type="text" id="nameInput{{$item->id}}" class="form-control" value="{{$item->name}}">
                    </div>
                </td>
                <td onclick="openIconTable(this, {{$item->id}})" title="Edit Icon" style="cursor:pointer;">
                    <img src="{{$item->icon}}" style="width: 50px; height: 50px">
                </td>
                <td onclick="openTitleTable({{$item->id}})" title="Edit Titles" style="cursor:pointer;">
                    <ul id="ulTitle{{$item->id}}">
                        @foreach($item->title as $title)
                           <li id="title{{$title->id}}">
                               {{$title->name}}
                           </li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <div id="editButton{{$item->id}}" style="display: none;">
                        <button class="btn btn-success" onclick="doEdit(this, {{$item->id}})">submit</button>
                        <button class="btn btn-secondary" onclick="cancelEdit(this, {{$item->id}})">cancel</button>
                    </div>
                    <div style="display: flex">
                        <button class="btn btn-primary" onclick="editCategory(this, {{$item->id}})">edit</button>
                        <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}', 'category')">delete</button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <div class="row" style="justify-content: center">
        <div class="addIcon">
            <i class="fas fa-plus-circle" style="cursor: pointer" onclick="openNewCategoryModal()"></i>
        </div>
    </div>

    <div class="modal" id="newCategoryModal">
            <div class="modal-dialog modal-lg" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Category</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="newName">Category Name</label>
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
                        <button type="button" class="btn btn-success" onclick="submitCategory()">Submit</button>
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
{{--                            <div class="col-md-12" style="justify-content: center; display: flex">--}}
{{--                                <button class="btn btn-danger" onclick="deleteIcon()">Delete Icon</button>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    <div class="modal-footer" style="display: flex; justify-content: center;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="submitEditIcon()">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="titleModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span id="nameTitleTable"></span>
                        Titles
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <table id="titleTable" class="table table-bordered"></table>
                    <div class="row" style="justify-content: center">
                        <div class="addIcon">
                            <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addNewTitle()"></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="deleteTitleDivText" class="row" style="display: none">
                        Are you sure you want to remove the <span class="deletedModalName" style="font-weight: bold; color: red"></span>?
                    </div>
                    <div id="deleteCategoryDivText" class="row" style="display: none">
                        Are you sure you want to remove the <span class="deletedModalName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button id="deleteCategoryButton" type="button" class="btn btn-danger" onclick="checkCategory()" data-dismiss="modal" style="display: none;">Yes Deleted</button>
                    <button id="deleteTitleButton" type="button" class="btn btn-danger" onclick="checkActivity()" data-dismiss="modal" style="display: none;">Yes Deleted</button>
                    <input type="hidden" id="deletedModalId">
                </div>
            </div>
        </div>
    </div>

    @endsection

@section('script')
    <script>
        var newIcon;
        var editedIcon;
        var categories = {!! $category !!};
        var newCategory = 0;

        function openNewCategoryModal(){
            $('#newName').val('');
            $('#newIcon').val('');
            $('#newCategoryModal').modal('show');
        }

        function submitCategory(_id){
            var name = $('#newName').val();
            if(name.trim().length > 1){
                openLoading();

                var data = new FormData();

                data.append('name', name);
                data.append('icon', newIcon);
                data.append('_token', '{{csrf_token()}}');

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.store")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok')
                                window.location.reload();
                            else if(response['status'] == 'nok1')
                                resultLoading('The category name is duplicate', 'danger');
                        }
                        catch (e) {
                            resultLoading('Error 2', 'danger');
                        }
                    },
                    error: function(err){
                        resultLoading('Error 2', 'danger');
                    }
                })
            }
        }

        function disableIcon(_icon){
            newIcon = _icon;
            $('#mainIcon').css('display', 'block');
            $('#mainIcon').next().css('display', 'none');
        }

        function openIconTable(_element,_id){
            var icon = $($(_element).children()[0]).attr('src');
            $('#editIconImg').attr('src', icon);
            $('#editIconId').val(_id);
            $('#editIcon').modal('show');
        }

        function editCategory(_element, _id){
            $(_element).parent().css('display', 'none');
            $(_element).parent().prev().css('display', 'flex');

            $('#activityName' + _id).css('display', 'none');
            $('#activityNameInput' + _id).css('display', 'flex');
        }

        function cancelEdit(_element, _id){
            $(_element).parent().css('display', 'none');
            $(_element).parent().next().css('display', 'flex');

            $('#activityName' + _id).css('display', 'flex');
            $('#activityNameInput' + _id).css('display', 'none');
        }

        function doEdit(_element, _id){
            var name = $('#nameInput' + _id).val();
            if(name.trim().length > 1){
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.edit")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        name: name,
                        kind: 'name',
                        id: _id
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#editButton' + _id).css('display', 'none');
                            $('#editButton' + _id).next().css('display', 'flex');

                            $('#activityName' + _id).text(name);
                            $('#activityName' + _id).css('display', 'flex');
                            $('#activityNameInput' + _id).css('display', 'none');
                        }
                        else if(response['status'] == 'nok1')
                            alert("The category name is duplicate");
                    },
                    error: function(err){
                        console.log(err)
                    }
                })
            }
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
                var data = new FormData();

                data.append('id', id);
                data.append('icon', editedIcon);
                data.append('kind', 'editIcon');
                data.append('_token', '{{csrf_token()}}');
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.edit")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok')
                                window.location.reload();
                            else
                                resultLoading('Error 1 In Upadate Icon', 'danger');
                        }
                        catch (e) {
                            resultLoading('Error 2 In Upadate Icon', 'danger');
                        }
                    },
                    error: function () {
                        resultLoading('Error 3 In Upadate Icon', 'danger');
                    }
                })
            }
        }



        var titleOpenId = 0;
        var indexOpen = 0;
        function openTitleTable(_id){
            for(var i = 0; i < categories.length; i++){
                if(categories[i]['id'] == _id){
                    $('#nameTitleTable').text(categories[i]['name']);

                    titleOpenId = _id;
                    indexOpen = i;
                    var text = '';
                    var tit = categories[i]['title'];
                    for(var j = 0; j < tit.length; j++) {
                        if(tit[j] != 0) {
                            text += '<tr id="titInTable' + tit[j]["id"] + '">\n' +
                                '<td>\n' +
                                '<input type="text" class="form-control" id="input' + tit[j]["id"] + '" value="' + tit[j]["name"] + '">\n' +
                                '</td>\n' +
                                '<td>\n' +
                                '<div style="display: flex; float: right">\n' +
                                '<button class="circleIcon" style="background: green" onclick="editTitle(' + tit[j]["id"] + ')">\n' +
                                '<i class="fas fa-edit" aria-hidden="true"></i>\n' +
                                '</button>\n' +
                                '<button class="circleIcon" style="background: red" onclick="deleteTitle(' + tit[j]["id"] + ')">\n' +
                                '<i class="fas fa-trash-alt" aria-hidden="true"></i>\n' +
                                '</button>\n' +
                                '</div>\n' +
                                '</td>\n' +
                                '</tr>';
                        }
                    }

                    $('#titleTable').html(text);
                    break;
                }
            }
            $('#titleModal').modal('show')
        }

        var newTitle = 0;
        function addNewTitle(){
            var text = '<tr id="newTitleRow' + newTitle + '">\n' +
                '<td>\n' +
                '<input type="text" class="form-control" id="newInput' + newTitle + '" placeholder="new Title">\n' +
                '</td>\n' +
                '<td>\n' +
                '<div style="display: flex; float: right">\n' +
                '<button class="circleIcon" style="background: green" onclick="submitNewTitle(' + newTitle + ')">\n' +
                '<i class="fas fa-check" aria-hidden="true"></i>\n' +
                '</button>\n' +
                '</div>\n' +
                '</td>\n' +
                '</tr>';

            newTitle++;
            $('#titleTable').append(text);
        }

        function submitNewTitle(_number){
            var _value = $('#newInput' + _number).val();
            if(titleOpenId != 0 && _value.trim().length > 1)
                sendTitleAjax(_value, 0, 'new', titleOpenId, _number);
        }

        function editTitle( _id){
            var _value = $('#input' + _id).val();
            if(titleOpenId != 0 && _value.trim().length > 1)
                sendTitleAjax(_value, _id, 'edit', titleOpenId);
        }

        function sendTitleAjax(_value, _id, _kind, _categoryId, _number){
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.title.store")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    name: _value,
                    id: _id,
                    kind: _kind,
                    categoryId: _categoryId
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok'){
                        if(_kind == 'edit'){
                            $('#title' + _id).text(_value);
                            for(var i = 0; i < categories.length; i++){
                                if(categories[i]['id'] == _categoryId){
                                    var tit = categories[i]['title'];
                                    for(var j = 0; j < tit.length; j++){
                                        if(tit[j]['id'] == _id){
                                            tit[j]['name'] = _value;
                                            break;
                                        }
                                    }
                                    break;
                                }
                            }
                            alert('title change');
                        }
                        else{
                            createNewRow(response['result'], _value, _categoryId, _number)
                        }
                    }
                    else if(response['status'] == 'nok1')
                        alert("The category name is duplicate");
                }
            })
        }


        function createNewRow(_id, _name, _categoryId, _number){
            for(var i = 0; i < categories.length; i++){
                if(categories[i]['id'] == _categoryId){
                    categories[i]['title'].push({
                        'id': _id,
                        'name' : _name
                    })
                }
            }

            var text = '<tr>\n' +
                '<td>\n' +
                '<input type="text" class="form-control" id="input' + _id + '" value="' + _name + '">\n' +
                '</td>\n' +
                '<td>\n' +
                '<div style="display: flex; float: right">\n' +
                '<button class="circleIcon" style="background: green" onclick="editTitle(' + _id + ')">\n' +
                '<i class="fas fa-edit" aria-hidden="true"></i>\n' +
                '</button>\n' +
                '<button class="circleIcon" style="background: red" onclick="deleteTitle(' + _id + ')">\n' +
                '<i class="fas fa-trash-alt" aria-hidden="true"></i>\n' +
                '</button>\n' +
                '</div>\n' +
                '</td>\n' +
                '</tr>';

            $('#ulTitle' + _categoryId).append('<li id="title' + _id + '">' + _name + '</li>');

            $('#newTitleRow' + _number).remove();
            $('#titleTable').append(text);
        }

        function deleteTitle(_id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.title.delete")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok'){
                        $("#titInTable" + _id).remove();
                        $("#title" + _id).remove();
                        for(var i = 0; i < categories.length; i++){
                            if(categories[i]['id'] == titleOpenId){
                                var tit = categories[i]['title']
                                for(var j = 0; j < tit.length; j++){
                                    if(tit[j]['id'] == _id)
                                        tit[j] = 0;
                                }
                            }
                        }
                    }
                }
            })
        }

        function deleteCategory(_id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.delete")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try {
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            resultLoading('category deleted', 'success');
                            $('#category' + _id).remove();
                        }
                        else if(response['status'] == 'nok3'){
                            resultLoading('category have destinations', 'danger');
                        }
                        else
                            resultLoading('Error 9', 'danger');
                    }
                    catch (e) {
                        resultLoading('Error 8', 'danger');
                    }
                },
                error: function(error){
                    resultLoading('Error 7', 'danger');
                    console.log(e);
                }
            })

        }

        function checkCategory(){
            let _id = $('#deletedModalId').val();
            openLoading();

            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.category.check")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try {
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            deleteCategory(_id);
                        }
                        else if(response['status'] == 'nok2'){
                            let text = '<div style="display: flex; flex-direction: column; max-height: 65vh; overflow-y: auto">';

                            if(response['main'].length != 0){
                                text += '<div style="">This Category has Destination And You cant Delete this Category:</div>';
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
                error: function(error){
                    console.log(e);
                    resultLoading('Error 3', 'danger');
                }
            })
        }

        function openDeletedModal(_id, _name, _kind){

            if(_kind == 'category'){
                $('#deleteCategoryButton').css('display', 'block');
                $('#deleteTitleButton').css('display', 'none');

                $('#deleteCategoryDivText').css('display', 'block');
                $('#deleteTitleDivText').css('display', 'none');
            }
            else{
                $('#deleteCategoryButton').css('display', 'none');
                $('#deleteTitleButton').css('display', 'block');

                $('#deleteCategoryDivText').css('display', 'none');
                $('#deleteTitleDivText').css('display', 'block');
            }

            $('#deletedModalId').val(_id);
            $('.deletedModalName').text(_name);
            $('#deleteModal').modal('show');
        }
    </script>
@endsection

