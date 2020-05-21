@extends('admin.layout.adminLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                {{__('Package List')}}
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
                    <th>{{__('Name')}}</th>
                    <th>{{__('Draft/Show')}}</th>
                    <th>{{__('Destination')}}</th>
                    <th>{{__('Code')}}</th>
                    <th>{{__('Start date')}}</th>
                    <th>{{__('Activity')}}</th>
                    <th>{{__('Popular (max first)')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($packages as $item)
                    <tr id="package{{$item->id}}"  style="background: {{$item->showPack == 1 ? '' : '#ffe6e6'}}">
                        <td>{{$item->name}}</td>
                        <td>{{$item->showPack == 1 ? __('Show') : __('Draft')}}</td>
                        <td>{{$item->destination->name}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->sDate}}</td>
                        <td>{{$item->activity->name}}</td>
                        <td>
                            <div class="form-group">
                                <select id="popular_{{$item->id}}" class="form-control" onchange="changePopularNum(this.value, {{$item->id}})">
                                    <option value="null" {{$item->popularNum == null ? 'selected' : ''}}></option>
                                    @for($i = 1; $i < 9; $i++)
                                        <option value="{{$i}}" {{$item->popularNum == $i ? 'selected' : ''}}>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </td>
                        <td>
                            <a href="{{route('admin.package.moreInfoText', ['id' => $item->id])}}">
                                <button class="btn btn-warning">{{__('More Info')}}</button>
                            </a>
                            <a href="{{route('admin.package.edit', ['id' => $item->id])}}">
                                <button class="btn btn-primary">{{__('Edit')}}</button>
                            </a>
                            <button class="btn btn-danger" onclick="deletePackageModal({{$item->id}}, '{{$item->name}}')">{{__('Delete')}}</button>
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
                    <h4 class="modal-title">{{__('Delete Package')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{__('Are you sure you want to remove the')}} <span id="deletedPackageName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
                    <button type="button" class="btn btn-danger" onclick="deletePackage()" data-dismiss="modal">{{__('Yes Deleted')}}</button>
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
