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
            <th>{{__('Category Name')}}</th>
            <th>{{__('Journal count')}}</th>
            <th>{{__('View Order')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="table">
            @foreach($category as $item)
                <tr id="category_{{$item->id}}">
                    <td>
                        <div id="activityName{{$item->id}}">
                            {{$item->name}}
                        </div>
                    </td>
                    <td>
                        {{$item->journalCount}}
                    </td>
                    <td>
                        <div id="activityViewOrder{{$item->id}}">
                            {{$item->viewOrder}}
                        </div>
                    </td>
                    <td>
                        <div style="display: flex">
                            <button class="btn btn-primary" onclick="editCategory({{$item->id}})">{{__('Edit')}}</button>
                            <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}')">{{__('Delete')}}</button>
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

    <div class="modal" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeader"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="margin: 0px 30px">
                    <div class="row" style="margin-bottom: 20px">
                        <input type="hidden" id="categoryId">
                        <label for="categoryName">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="categoryName">
                    </div>
                    <div class="row">
                        <label for="categoryViewOrder">{{__('View Order')}}</label>
                        <input type="number" class="form-control" id="categoryViewOrder">
                    </div>
                    <div class="row" style="display: flex; justify-content: center; align-items: center">
                        <button class="btn btn-success" onclick="store()">
                            {{__('Store')}}
                        </button>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let category = {!! $category !!};

        function addNewCategory(){
            $('#categoryName').val('');
            $('#categoryViewOrder').val(1);
            $('#categoryId').val(0);
            $('#modalHeader').text('{{__('New Journal Category')}}');
            $('#modal').modal({backdrop: 'static', keyboard: false});
        }

        function createNew(_id, _name, _viewOrder){
            var text = '<tr id="category_' + _id + '">\n' +
                '                    <td>\n' +
                '                        <div id="activityName' + _id + '">' + _name + '</div>\n' +
                '                    </td>\n' +
                '                    <td>0</td>' +
                '                    <td>\n' +
                '                        <div id="activityViewOrder' + _id + '">' + _viewOrder + '</div>\n' +
                '                    </td>\n' +
                '                    <td>\n' +
                '                        <div style="display: flex">\n' +
                '                            <button class="btn btn-primary" onclick="editCategory(' + _id + ')">{{__("Edit")}}</button>\n' +
                '                            <button class="btn btn-danger" onclick="openDeletedModal(' + _id + ', \'' +  _name + '\')">{{__("Delete")}}</button>\n' +
                '                        </div>\n' +
                '                    </td>\n' +
                '                </tr>';
            $('#table').append(text);
        }

        function editCategory(_id){
            let orderView = parseInt($('#activityViewOrder' + _id).text());
            let name = $('#activityName' + _id).text();

            $('#categoryName').val(name);
            $('#categoryViewOrder').val(orderView);
            $('#categoryId').val(_id);
            $('#modalHeader').text('{{__('Edit')}} ' + name);
            $('#modal').modal({backdrop: 'static', keyboard: false});
        }

        function store(){
            let id = $('#categoryId').val();
            let name = $('#categoryName').val().trim();
            let viewOrder = $('#categoryViewOrder').val();

            if(name.trim().length > 1){
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.journal.category.store")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        name: name,
                        id: id,
                        viewOrder: viewOrder,
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            if(id == 0)
                                createNew(response['id'], name, viewOrder);
                            else {
                                $('#activityViewOrder' + id).text(viewOrder);
                                $('#activityName' + id).text(name);
                            }
                            $('#modal').modal('hide');
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

        function openDeletedModal(_id, _name) {
            let cat = null;
            category.forEach(item => {
                if(item.id == _id)
                    cat = item;
            });

            if(cat != null){
                openLoading();

                if(cat.journalCount > 0)
                    resultLoading('{{__('This category have journal and cant delete. first delete journals')}}');
                else{
                    $.ajax({
                       type: 'post',
                       url: '{{route('admin.journal.category.delete')}}',
                       data: {
                           _token: '{{csrf_token()}}',
                           id: _id
                       },
                        success: function (response) {
                            try{
                                response = JSON.parse(response);
                                if(response['status'] == 'ok'){
                                    $('#category_' + _id).remove();
                                    resultLoading("{{__('category deleted')}}", 'success');
                                }
                                else if(response['status'] == 'nok1')
                                    resultLoading('{{__('This category have journal and cant delete. first delete journals')}}');
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
                    });
                }
            }

        }
    </script>
@endsection

