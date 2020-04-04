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
            <th>Titles</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="addNewCategory">
        @foreach($category as $item)
            <tr id="activity{{$item->id}}">
                <td>
                    <div id="activityName{{$item->id}}">
                        {{$item->name}}
                    </div>
                    <div id="activityNameInput{{$item->id}}" style="display: none;">
                        <input type="text" id="nameInput{{$item->id}}" class="form-control" value="{{$item->name}}">
                    </div>
                </td>
                <td onclick="openTitleTable({{$item->id}})">
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
                        <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}')">delete</button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <div class="row" style="justify-content: center">
        <div class="addIcon">
            <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addNewCategory()"></i>
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
        var categories = {!! $category !!};
        var newCategory = 0;

        function addNewCategory(){
            var text = '<tr id="newCategory' + newCategory + '">\n' +
                '                    <td>\n' +
                '                        <div id="newCategoryNameInput' + newCategory + '" style="display: flex;">\n' +
                '                            <input type="text" id="newNameInput' + newCategory + '" class="form-control">\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                    <td>\n' +
                '                        <div style="display: flex;">\n' +
                '                            <button class="btn btn-success" onclick="submitNew(' + newCategory + ')">submit</button>\n' +
                '                            <button class="btn btn-secondary" onclick="cancelNew(' + newCategory + ')">cancel</button>\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                </tr>';

            $('#addNewCategory').append(text);
            newCategory++;
        }

        function cancelNew(_id){
            $('#newCategory' + _id).remove();
        }

        function submitNew(_id){
            var name = $('#newNameInput' + _id).val();
            if(name.trim().length > 1){
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.destination.category.store")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        name: name
                    },
                    success: function(response){
                        response = JSON.parse(response)
                        if(response['status'] == 'ok'){
                            id = response['result'];
                            cancelNew(_id);
                            createNew(id, name);
                        }
                        else if(response['status'] == 'nok1')
                            alert("The category name is duplicate");
                    },
                    error: function(err){

                    }
                })
            }
        }

        function createNew(_id, _name){
            var text = '<tr id="activity' + _id + '">\n' +
                '                <td>\n' +
                '                    <div id="activityName' + _id + '">' + _name + '</div>\n' +
                '                    <div id="activityNameInput' + _id + '" style="display: none;">\n' +
                '                        <input type="text" id="nameInput' + _id + '" class="form-control" value="' + _name + '">\n' +
                '                    </div>\n' +
                '                </td>\n' +
                '                <td onclick="openTitleTable(' + _id + ')">\n' +
                '                    <ul id="ulTitle' + _id + '"></ul>\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <div id="editButton' + _id + '" style="display: none;">\n' +
                '                        <button class="btn btn-success" onclick="doEdit(this, ' + _id + ')">submit</button>\n' +
                '                        <button class="btn btn-secondary" onclick="cancelEdit(this, ' + _id + ')">cancel</button>\n' +
                '                    </div>\n' +
                '                    <div style="display: flex">\n' +
                '                        <button class="btn btn-primary" onclick="editCategory(this, ' + _id + ')">edit</button>\n' +
                '                        <button class="btn btn-danger" onclick="openDeletedModal(' + _id + ', \'' + _name + '\')">delete</button>\n' +
                '                    </div>\n' +
                '                </td>\n' +
                '            </tr>';

            categories.push({
                'id' : _id,
                'name' : _name,
                'title' : []
            })

            $('#addNewCategory').append(text);
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
    </script>
@endsection

