@extends('admin.layout.adminLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                Package List
                <a href="{{route('admin.package.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">
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
                    <th>Draft/Show</th>
                    <th>Destination</th>
                    <th>Season</th>
                    <th>Start Date</th>
                    <th>Activity</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($packages as $item)
                    <tr id="package{{$item->id}}"  style="background: {{$item->showPack == 1 ? '' : '#ffe6e6'}}">
                        <td>{{$item->name}}</td>
                        <td>{{$item->showPack == 1 ? 'Show' : 'Draft'}}</td>
                        <td>{{$item->destination->name}}</td>
                        <td>{{$item->season}}</td>
                        <td>{{$item->sDate}}</td>
                        <td>{{$item->activity->name}}</td>
                        <td>
                            <a href="{{route('admin.package.edit', ['id' => $item->id])}}">
                                <button class="btn btn-primary">Edit</button>
                            </a>
                            <button class="btn btn-danger" onclick="deletePackageModal({{$item->id}}, '{{$item->name}}')">Delete</button>
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
                    <h4 class="modal-title">Delete Packages</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        Are you sure you want to remove the <span id="deletedPackageName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" onclick="deletePackage()" data-dismiss="modal">Yes Deleted</button>
                    <input type="hidden" id="deletedPackageId">
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

        function deletePackageModal(_id, _name){
            $('#deletedPackageName').text(_name);
            $('#deletedPackageId').val(_id);
            $('#deleteModal').modal('show');
        }

        function deletePackage(){
            id = $('#deletedPackageId').val();
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.package.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#package' + id).remove();
                            resultLoading('Package deleted', 'success');
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
    </script>
@endsection
