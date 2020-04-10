@extends('admin.layout.adminLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                Destination List
                <a href="{{route('admin.destination.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">
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
                    <th>category</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($destination as $item)
                    <tr id="destination{{$item->id}}">
                        <td>{{$item->name}}</td>
                        <td>
                            @if(isset($item->category->name))
                                {{$item->category->name}}
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.destination.edit', ['id' => $item->id])}}">
                                <button class="btn btn-primary">Edit</button>
                            </a>
                            <a href="{{route('admin.destination.description', ['id' => $item->id])}}">
                                <button class="btn btn-warning">descriptions</button>
                            </a>
                            <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}')">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Destination</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        Are you sure you want to remove the <span id="deletedDestinationName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" onclick="checkDestination()" data-dismiss="modal">Yes Deleted</button>
                    <input type="hidden" id="deletedDestinationId">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready( function () {
            $('#table_id').DataTable( {
                "scrollY":        "70vh",
                "scrollCollapse": true,
                "paging":         false
            });
        } );

        function openDeletedModal(_id, _name){
            $('#deletedDestinationId').val(_id);
            $('#deletedDestinationName').text(_name);
            $('#deleteModal').modal('show');
        }

        function checkDestination(){
            openLoading();
            var _id = $('#deletedDestinationId').val();
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.check")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id,
                },
                success: function(response){
                    try {
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            deleteDestination();
                        else if(response['status'] == 'nok2'){
                            text = '<div style="display: flex; flex-direction: column; max-height: 65vh; overflow-y: auto">';

                            if(response['main'].length != 0){
                                text += '<div style="">This Destination has Packages And You cant Delete this Destination:</div>';
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

        function deleteDestination(){
            var _id = $('#deletedDestinationId').val();

            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            resultLoading('Destination Deleted', 'success');
                            $('#destination' + _id).remove();
                        }
                        else if(response['status'] == 'mainError'){
                            resultLoading('This Destination has some Packages', 'danger');
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
