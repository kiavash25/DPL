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
                Language
            </h2>
        </div>
        <hr>

        <div class="col-md-12">
            <div class="row">

                <table id="tableNat" class="table table-striped  table-bordered">
                    <thead>
                    <tr>
                        <th>name</th>
                        <th>symbol</th>
                        <th>direction</th>
                        <th>state</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($lang as $item)
                            <tr id="lang_{{$item->id}}">
                                <td id="langName_{{$item->id}}">{{$item->name}}</td>
                                <td id="langSymbol_{{$item->id}}">{{$item->symbol}}</td>
                                <td id="langDir_{{$item->id}}">{{$item->direction}}</td>
                                <td id="langState_{{$item->id}}">{{$item->state == 1 ? 'show' : 'block'}}</td>
                                <td>
                                    <button class="btn btn-primary" onclick="editLang({{$item->id}}, '{{$item->direction}}')">Edit</button>
{{--                                    <button class="btn btn-danger" onclick="deleteLang({{$item->id}})">Delete</button>--}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row" style="justify-content: center">
                <div class="addTagIcon" style="margin-left: 30px; color: green" onclick="newLang()">
                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                </div>
            </div>
        </div>


        <div class="modal" id="langModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="langModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="justify-content: center">
                            <input type="hidden" id="langInputId">
                            <label for="langName">Name:</label>
                            <input type="text" class="form-control" id="langName">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="langSymbol">Symbol:</label>
                                <input type="text" class="form-control" id="langSymbol">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="langDir">Direction:</label>
                                <select name="langDir" id="langDir" class="form-control">
                                    <option value="ltr">Left to Right</option>
                                    <option value="rtl">Right to Left</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="langState">State:</label>
                                <select name="langState" id="langState" class="form-control">
                                    <option value="1">Show</option>
                                    <option value="0">Block</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center; align-items: center">
                            <button class="btn btn-success" onclick="storeLang()">
                                Store
                            </button>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@section('script')
    <script !src="">
        function editLang(_id, _dir){
            $('#langName').val($('#langName_' + _id).text());
            $('#langSymbol').val($('#langSymbol_' + _id).text());
            $('#langDir').val(_dir);

            if($('#langState_' + _id).text() == 'show')
                $('#langState').val(1);
            else
                $('#langState').val(0);

            $('#langInputId').val(_id);
            $('#langModalHeader').text('Edit Title');
            $('#langModal').modal({backdrop: 'static', keyboard: false});
        }

        function deleteLang(_id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.lang.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        $('#lang_' + _id).remove();
                }
            })
        }

        function newLang(){
            $('#langName').val('');
            $('#langSymbol').val('');
            $('#langInputId').val(0);
            $('#langState').val(1);
            $('#langModalHeader').text('New Title');
            $('#langModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeLang(){
            let id = $('#langInputId').val();
            let name = $('#langName').val();
            let symbol = $('#langSymbol').val();
            let state = $('#langState').val();
            let dir = $('#langDir').val();
            if(name.trim().length > 0 && symbol.trim().length > 0){
                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.setting.lang.store")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                        name: name,
                        symbol: symbol,
                        state: state,
                        dir: dir
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                // resultLoading('Language Stored', 'success');
                                location.reload();
                            }
                            else if(response['status'] == 'nok1')
                                resultLoading('Language Duplicate!', 'danger');
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


    </script>
@endsection

