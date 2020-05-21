@extends('admin.layout.adminLayout')

@section('head')

    <style>
        .row{
            width: 100%;
            margin: 0px;
        }
    </style>
@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                {{__('Package More Info Titles')}}
            </h2>
        </div>
        <hr>

        <div class="col-md-12">
            <div class="row">
                <h3>{{__('CallVenture Category')}}</h3>
                <table id="tableCall" class="table table-striped  table-bordered">
                    @foreach($moreInfoCallVenture as $more)
                        <tr id="moreInfoRow_{{$more->id}}">
                            <input type="hidden" id="moreInfoCategory_{{$more->id}}" value="{{$more->category}}">
                            <td id="moreInfoTitle_{{$more->id}}">{{$more->name}}</td>
                            <td>
                                <button class="btn btn-primary" onclick="editMoreInfo({{$more->id}}, 'callVenture')">{{__('Edit')}}</button>
                                <button class="btn btn-danger" onclick="deleteMoreInfo({{$more->id}})">{{__('Delete')}}</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <hr>
            <div class="row">

                <h3>{{__('Neutral Category')}}</h3>
                <table id="tableNat" class="table table-striped  table-bordered">
                    @foreach($moreInfoNature as $more)
                        <tr id="moreInfoRow_{{$more->id}}">
                            <input type="hidden" id="moreInfoCategory_{{$more->id}}" value="{{$more->category}}">
                            <td id="moreInfoTitle_{{$more->id}}">{{$more->name}}</td>
                            <td>
                                <button class="btn btn-primary" onclick="editMoreInfo({{$more->id}}, 'neutralDetail')">{{__('Edit')}}</button>
                                <button class="btn btn-danger" onclick="deleteMoreInfo({{$more->id}})">{{__('Delete')}}</button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="row" style="justify-content: center">
                <div class="addTagIcon" style="margin-left: 30px; color: green" onclick="newMoreInfo()">
                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                </div>
            </div>
        </div>


        <div class="modal" id="moreInfoModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="moreInfoModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="justify-content: center">
                            <input type="hidden" id="moreInfoInputId">
                            <input type="text" class="form-control" id="moreInfoName">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="selectCategory">{{__('Select Category')}}:</label>
                                <select id="selectCategory" class="form-control">
                                    <option value="neutralDetail">{{__('Neutral Category')}}</option>
                                    <option value="callventureDetail">{{__('CallVenture Category')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center; align-items: center">
                            <button class="btn btn-success" onclick="storeMoreInfo()">
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

    </div>

@endsection


@section('script')
    <script !src="">
        function editMoreInfo(_id, _kind){
            $('#moreInfoName').val($('#moreInfoTitle_' + _id).text());
            if(_kind == 'neutralDetail')
                $('#selectCategory').val('neutralDetail');
            else
                $('#selectCategory').val('callventureDetail');

            $('#moreInfoInputId').val(_id);
            $('#moreInfoModalHeader').text('{{__('Edit Title')}}');
            $('#moreInfoModal').modal({backdrop: 'static', keyboard: false});
        }
        function deleteMoreInfo(_id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.package.moreInfoTitle.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        $('#moreInfoRow_' + _id).remove();
                }
            })
        }

        function newMoreInfo(){
            $('#moreInfoName').val('');
            $('#moreInfoInputId').val(0);
            $('#moreInfoModalHeader').text('{{__('New Title')}}');
            $('#moreInfoModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeMoreInfo(){
            let id = $('#moreInfoInputId').val();
            let name = $('#moreInfoName').val();
            let category = $('#selectCategory').val();
            if(name.trim().length > 0){
                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.package.moreInfoTitle.store")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                        name: name,
                        category: category
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                resultLoading('{{__('Title Stored')}}', 'success');
                                if(id == 0)
                                    createNewRow(response['id'], response['name'], response['category']);
                                else {
                                    if(response['category'] == $('#moreInfoCategory_' + id).val()) {
                                        $('#moreInfoTitle_' + id).text(name);
                                        $('#moreInfoCategory_' + id).val(response['category']);
                                    }
                                    else{
                                        $('#moreInfoRow_' + id).remove();
                                        createNewRow(response['id'], response['name'], response['category']);
                                    }
                                }
                            }
                            else if(response['status'] == 'nok1')
                                resultLoading('{{__('Title Duplicate!')}}', 'danger');
                            else
                                resultLoading('Error1', 'danger')
                        }
                        catch(e){
                            console.log(e);
                            resultLoading('Parsing Error', 'danger')
                        }
                    },
                    error: function(err){
                        resultLoading('Server Error', 'danger')
                    }
                })
            }
        }

        function createNewRow(_id, _name, _category){
            let text ='<tr id="moreInfoRow_' + _id + '">\n' +
                ' <td id="moreInfoTitle_' + _id + '">' + _name + '</td>\n' +
                ' <td>\n' +
                ' <button class="btn btn-primary" onclick="editMoreInfo(' + _id + ', \'' + _category + '\')">{{__("Edit")}}</button>\n' +
                ' <button class="btn btn-danger" onclick="deleteMoreInfo(' + _id + ')">{{__("Delete")}}</button>\n' +
                '</td>\n' +
                '</tr>';

            if(_category == 'callventureDetail')
                $('#tableCall').append(text);
            else
                $('#tableNat').append(text);
        }

    </script>
@endsection

