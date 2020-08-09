@extends('profile.layout.profileLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                {{__('Nature friend list')}}
                <a href="{{route('admin.natureFriend.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">
                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                </a>
            </h2>
        </div>
        <hr>
        <div class="col-md-12">

            <table id="table_id" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($nats as $item)
                    <tr id="package{{$item->id}}">
                        <td>{{$item->name}}</td>
                        <td>
                            <a href="{{route('admin.natureFriend.edit', ['id' => $item->id])}}">
                                <button class="btn btn-primary">{{__('Edit')}}</button>
                            </a>
                            <button class="btn btn-danger" onclick="deleteNatureFriendModal({{$item->id}}, '{{$item->name}}')">{{__('Delete')}}</button>
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
                    <h4 class="modal-title">{{__('Delete nature friend')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{__('Are you sure you want to remove the')}} <span id="deletedNatureName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
                    <button type="button" class="btn btn-danger" onclick="deleteNatureFriend()" data-dismiss="modal">{{__('Yes Deleted')}}</button>
                    <input type="hidden" id="deleteNatureFriendId">
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

        function deleteNatureFriendModal(_id, _name){
            $('#deletedNatureName').text(_name);
            $('#deleteNatureFriendId').val(_id);
            $('#deleteModal').modal('show');
        }

        function deleteNatureFriend(){
            id = $('#deleteNatureFriendId').val();
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.natureFriend.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#package' + id).remove();
                            resultLoading('Nature friend deleted', 'success');
                        }
                    }
                    catch (e) {
                        resultLoading('Error 1', 'danger');
                    }
                },
                error: function(err){
                    resultLoading('Error 2', 'danger');
                }
            })
        }

        function changePopularNum(_value, _id){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.package.popularStore")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id,
                    value: _value
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            resultLoading("{{__('Popular order view change')}}", 'success');
                            if(response['lastId'] != 0)
                                $('#popular_' + response['lastId']).val(response['lastValue']);
                        }
                        else if(response['status'] == 'nok1'){
                            $('#popular_' + _id).val('null');
                            resultLoading("{{__('This package is not displayed due to lack of display or date of holding')}}", 'danger');
                        }
                        else
                            resultLoading("{{__('Error in store')}}", 'danger');
                    }
                    catch (e) {
                        console.log(e);
                        resultLoading("{{__('Error in result')}}", 'danger');
                    }
                },
                error: function(err){
                    console.log(err);
                    resultLoading("{{__('Error in Server connection')}}", 'danger');
                }
            })
        }
    </script>
@endsection
