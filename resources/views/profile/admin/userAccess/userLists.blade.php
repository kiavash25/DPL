@extends('profile.layout.profileLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">

    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>

    <style>
        .modalBody{
            width: 100%;
            margin: 0;
        }
    </style>

@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                {{__('User List')}}
            </h2>
        </div>
        <hr>
        <div class="col-md-12">

            <table id="table_id" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Email')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr id="user_{{$user->id}}">
                        <td>{{$user->name}}</td>
                        <td>
                            {{$user->email}}
                        </td>
                        <td>
                            <button class="btn btn-warning" onclick="storeAccess({{$user->id}})">{{__('Enable admin access')}}</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('script')
    <script>
        let users = {!! $users !!};

        $(window).ready( function () {
            $('#table_id').DataTable( {
                "scrollY":        "70vh",
                "scrollCollapse": true,
                "paging":         false
            });
        } );

        function openAdminAccess(){
            let user;
            users.forEach((item) => {
                if(item.id == _id)
                    user = item;
            });

            $('#adminAccessUserId').val(user.id);
            $('.userAccessLevelName').text(user.name);

            $('#adminAccessModal').modal('show');
            $('#adminAccessModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeAccess(_id){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.userAccess.acl.changeAdminAccess")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id,
                    access: 1,
                },
                success: function (response) {
                    try{
                        if(response.status == 'ok'){
                            $(`#user_${_id}`).remove();
                            resultLoading("{{__('Admin access Enable')}}", 'success');
                        }
                        else {
                            console.log(response.status);
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
            $('#adminAccessModal').modal('hide');
        }
    </script>
@endsection
