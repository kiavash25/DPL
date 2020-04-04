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
            <th>Category Name</th>
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
        var newActivity = 0;

        function addNewCategory(){
            var text = '<tr id="newActivity' + newActivity + '">\n' +
                '                    <td>\n' +
                '                        <div id="newActivityNameInput' + newActivity + '" style="display: flex;">\n' +
                '                            <input type="text" id="newNameInput' + newActivity + '" class="form-control">\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                    <td>\n' +
                '                        <div style="display: flex;">\n' +
                '                            <button class="btn btn-success" onclick="submitNew(' + newActivity + ')">submit</button>\n' +
                '                            <button class="btn btn-secondary" onclick="cancelNew(' + newActivity + ')">cancel</button>\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                </tr>';

            $('#addNewCategory').append(text);
            newActivity++;
        }

        function cancelNew(_id){
            $('#newActivity' + _id).remove();
        }

        function submitNew(_id){
            var name = $('#newNameInput' + _id).val();
            if(name.trim().length > 1){
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.journal.category.store")}}',
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
                '                    <td>\n' +
                '                        <div id="activityName' + _id + '">' + _name + '</div>\n' +
                '                        <div id="activityNameInput' + _id + '" style="display: none;">\n' +
                '                            <input type="text" id="nameInput' + _id + '" class="form-control" value="' + _name + '">\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                    <td>\n' +
                '                        <div id="editButton' + _id + '" style="display: none;">\n' +
                '                            <button class="btn btn-success" onclick="doEdit(this, ' + _id + ')">submit</button>\n' +
                '                            <button class="btn btn-secondary" onclick="cancelEdit(this, ' + _id + ')">cancel</button>\n' +
                '                        </div>\n' +
                '                        <div style="display: flex">\n' +
                '                            <button class="btn btn-primary" onclick="editCategory(this, ' + _id + ')">edit</button>\n' +
                '                            <button class="btn btn-danger" onclick="openDeletedModal(' + _id + ', \'' + _name + '\')">delete</button>\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                </tr>';

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
                    url: '{{route("admin.journal.category.edit")}}',
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
    </script>
@endsection

