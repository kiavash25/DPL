@extends('profile.layout.profileLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                {{__('Journal List')}}
                <a href="{{route('admin.journal.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">
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
                    <th>{{__('Category')}}</th>
                    <th>{{__('Release Date')}}</th>
                    <th>{{__('User')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($journal as $item)
                    <tr id="journal{{$item->id}}">
                        <td>
                            <a href="{{route('journal.show', ['id' => $item->id, 'slug' => $item->slug])}}" target="_blank">
                                {{$item->name}}
                            </a>
                        </td>
                        <td>
                            @if(isset($item->category->name))
                                {{$item->category->name}}
                            @endif
                        </td>
                        <td>
                            {{__($item->releaseDate)}}
                        </td>
                        <td>
                            @if(isset($item->user->name))
                                {{$item->user->name}}
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.journal.edit', ['id' => $item->id])}}">
                                <button class="btn btn-primary">{{__('Edit')}}</button>
                            </a>
                            <button class="btn btn-danger" onclick="openDeletedModal({{$item->id}}, '{{$item->name}}')">{{__('Delete')}}</button>
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
                    <h4 class="modal-title">{{__('Delete Journal')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        Are you sure you want to remove the <span id="deletedJournalName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('No')}}</button>
                    <button type="button" class="btn btn-danger" onclick="deleteJournal()" data-dismiss="modal">{{__('Yes Deleted')}}</button>
                    <input type="hidden" id="deletedJournalId">
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
            $('#deletedJournalId').val(_id);
            $('#deletedJournalName').text(_name);
            $('#deleteModal').modal('show');
        }

        function deleteJournal(){
            openLoading();
            var _id = $('#deletedJournalId').val();

            $.ajax({
                type: 'post',
                url: '{{route("admin.journal.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            resultLoading('Journal Deleted', 'success');
                            $('#journal' + _id).remove();
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
