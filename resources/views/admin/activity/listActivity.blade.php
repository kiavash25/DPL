@extends('admin.layout.adminLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                Activity List
                <a href="{{route('admin.activity.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">
                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                </a>
            </h2>
        </div>
        <hr>
        <div class="col-md-12">

            <table id="table_id" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>name</th>
                        <th>Parent</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activity as $item)
                    <tr id="activity_{{$item->id}}">
                        <td>{{$item->name}}</td>
                        <td></td>
                        <td>
                            <a href="{{route('admin.activity.description', ['id' => $item->id])}}">
                                <button class="btn btn-warning">Description</button>
                            </a>
                            <a href="{{route('admin.activity.edit', ['id' => $item->id])}}">
                                <button class="btn btn-primary">Edit</button>
                            </a>
                            <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}')">Delete</button>
                        </td>
                    </tr>
                    @if(count($item->sub) != 0)
                        @foreach($item->sub as $sub)
                            <tr id="activity_{{$sub->id}}">
                                <td>{{$sub->name}}</td>
                                <td>{{$item->name}}</td>
                                <td>
                                    <a href="{{route('admin.activity.description', ['id' => $sub->id])}}">
                                        <button class="btn btn-warning">Description</button>
                                    </a>
                                    <a href="{{route('admin.activity.edit', ['id' => $sub->id])}}">
                                        <button class="btn btn-primary">Edit</button>
                                    </a>
                                    <button class="btn btn-danger" onclick="openDeletedModal({{$sub->id}}, '{{$sub->name}}')">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
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
        $(document).ready( function () {
            $('#table_id').DataTable( {
                "scrollY":        "50vh",
                "scrollX": true,
                "scrollCollapse": true,
                "paging":         false
            });
        } );

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
                            $('#activity_' + _id).remove();
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
