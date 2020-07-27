@extends('profile.layout.profileLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/dataTable/jquery.dataTables.min.css')}}">
    <script src="{{asset('js/dataTable/jquery.dataTables.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
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
                {{__('Admin List')}}
                <a href="{{route('register')}}" class="addTagIcon" style="margin-left: 30px; color: green">
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
                    <th>{{__('Email')}}</th>
                    <th>{{__('Access Levels')}}</th>
                    <th>{{__('Language Access')}}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($adminUser as $user)
                    <tr id="user_{{$user->id}}">
                        <td>{{$user->name}}</td>
                        <td>
                            {{$user->email}}
                        </td>
                        <td>
                            <button class="btn btn-primary" onclick="openAccessLevelList({{$user->id}})">{{__('Access level list')}}</button>
                        </td>
                        <td>
                            <button class="btn btn-primary" onclick="openLanguageList({{$user->id}})">{{__('Language access list')}}</button>
                        </td>
                        <td>
                            <button class="btn btn-warning" onclick="openAdminAccess({{$user->id}})">{{__('Disable access')}}</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="accessListModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Access level list')}} <span class="userAccessLevelName"></span></h4>
                    <button type="button" class="close" onclick="$('#accessListModal').modal('hide')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row modalBody">
                        <div class="form-group">
                            <input type="hidden" id="userAccessLevelId">
                            <label for="accessLevelList" class="inputLabel" style=" width: 100%; text-align: initial;">{{__('Access levels')}} <span class="userAccessLevelName" style="color: green"></span></label>
                            <select id="accessLevelList" class="ui fluid search dropdown" multiple="">
                                @foreach($aclList as $item)
                                    <option value="{{$item}}">{{__($item)}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" onclick="$('#accessListModal').modal('hide')">{{__('Cancel')}}</button>
                    <button type="button" class="btn btn-success" onclick="storeAccessLevel()" data-dismiss="modal">{{__('Submit')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="languageListModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Language access list')}} <span class="userAccessLevelName"></span></h4>
                    <button type="button" class="close" onclick="$('#languageListModal').modal('hide')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row modalBody">
                        <div class="form-group">
                            <input type="hidden" id="userLanguageAccessId">
                            <label for="languageAccessList" class="inputLabel" style=" width: 100%; text-align: initial;">{{__('Language')}} <span class="userAccessLevelName" style="color: green"></span></label>
                            <select id="languageAccessList" class="ui fluid search dropdown" multiple="">
                                <option value="en">English</option>
                                @foreach($languageList as $item)
                                    <option value="{{$item->symbol}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" onclick="$('#languageListModal').modal('hide')">{{__('Cancel')}}</button>
                    <button type="button" class="btn btn-success" onclick="storeLanguageAccess()" data-dismiss="modal">{{__('Submit')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="adminAccessModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Disable admin access')}} <span class="userAccessLevelName"></span></h4>
                    <button type="button" class="close" onclick="$('#adminAccessModal').modal('hide')">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row modalBody">
                        {{__('Do you want to disable admin access for the ')}}  <span class="userAccessLevelName" style="color: red;"></span> ?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <input type="hidden" id="adminAccessUserId">
                    <button type="button" class="btn btn-secondary" onclick="$('#adminAccessModal').modal('hide')">{{__('Cancel')}}</button>
                    <button type="button" class="btn btn-danger" onclick="storeDisableAccess()" data-dismiss="modal">{{__('Yes')}}</button>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        let users = {!! $adminUser !!};
        let dropdownAccessLevel;

        $(window).ready( function () {
            $('#table_id').DataTable( {
                "scrollY":        "70vh",
                "scrollCollapse": true,
                "paging":         false
            });
        } );

        function openAccessLevelList(_id){
            let user;
            users.forEach((item) => {
               if(item.id == _id)
                   user = item;
            });
            $('#userAccessLevelId').val(user.id);
            $('.userAccessLevelName').text(user.name);
            $('#accessLevelList').dropdown('clear');
            $('#accessLevelList').dropdown('set selected', user.aclName);

            $('#accessListModal').modal('show');
            $('#accessListModal').modal({backdrop: 'static', keyboard: false});
        }

        function openLanguageList(_id){
            let user;
            users.forEach((item) => {
               if(item.id == _id)
                   user = item;
            });

            $('#userLanguageAccessId').val(user.id);
            $('.userAccessLevelName').text(user.name);
            $('#languageAccessList').dropdown('clear');
            $('#languageAccessList').dropdown('set selected', user.language);

            $('#languageListModal').modal('show');
            $('#languageListModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeAccessLevel(){
            let acl = $('#accessLevelList').val();
            let userId = $('#userAccessLevelId').val();

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.userAccess.acl.store")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    userId: userId,
                    acl: acl
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            resultLoading("{{__('Access level update')}}", 'success');
                            users.forEach((item) => {
                                if(item.id == userId)
                                    item.aclName = response['result'];
                            });
                        }
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

            $('#accessListModal').modal('hide');
        }

        function storeLanguageAccess(){
            let language = $('#languageAccessList').val();
            let userId = $('#userLanguageAccessId').val();

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.userAccess.language.store")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    userId: userId,
                    language: language
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            resultLoading("{{__('Admin language update')}}", 'success');
                            users.forEach((item) => {
                                if(item.id == userId)
                                    item.language = response['result'];
                            });
                        }
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

            $('#accessListModal').modal('hide');
        }

        function openAdminAccess(_id){
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

        function storeDisableAccess(){
            let id = $('#adminAccessUserId').val();
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.userAccess.acl.disableAccess")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: id,
                },
                success: function (response) {
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#user_'+id).remove();
                            resultLoading("{{__('Admin access disabled')}}", 'success');
                        }
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
            $('#adminAccessModal').modal('hide');
        }
    </script>
@endsection
