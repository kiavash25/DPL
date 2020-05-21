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
                {{__('Language')}}
            </h2>
        </div>
        <hr>

        <div class="col-md-12">
            <div class="row">

                <table id="tableNat" class="table table-striped  table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Symbol')}}</th>
                        <th>{{__('Direction')}}</th>
                        <th>{{__('State')}}</th>
                        <th>{{__('Currency')}}</th>
                        <th>{{__('Currency Symbol')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($lang as $item)
                            <tr id="lang_{{$item->id}}">
                                <td id="langName_{{$item->id}}">{{$item->name}}</td>
                                <td id="langSymbol_{{$item->id}}">{{$item->symbol}}</td>
                                <td id="langDir_{{$item->id}}">{{$item->direction}}</td>
                                <td id="langState_{{$item->id}}" data-value="{{$item->state}}">{{$item->state == 1 ? __('Show') : __('Block')}}</td>
                                <td id="currencyName_{{$item->id}}">{{$item->currencyName}}</td>
                                <td id="currencySymbol_{{$item->id}}">{{$item->currencySymbol}}</td>
                                <td>
                                    <button class="btn btn-primary" onclick="editLang({{$item->id}}, '{{$item->direction}}')">{{__('Edit')}}</button>
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
                            <label for="langName">{{__('Name')}}:</label>
                            <input type="text" class="form-control" id="langName">
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="langSymbol">{{__('Symbol')}}:</label>
                                <input type="text" class="form-control" id="langSymbol">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="langDir">{{__('Direction')}}:</label>
                                <select name="langDir" id="langDir" class="form-control">
                                    <option value="ltr">{{__('Left to Right')}}</option>
                                    <option value="rtl">{{__('Right to Left')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="langState">{{__('State')}}:</label>
                                <select name="langState" id="langState" class="form-control">
                                    <option value="1">{{__('Show')}}</option>
                                    <option value="0">{{__('Block')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="currencyName">{{__('Currency')}}:</label>
                                <input type="text" class="form-control" id="currencyName">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="currencySymbol">{{__('Currency Symbol')}}:</label>
                                <input type="text" class="form-control" id="currencySymbol">
                            </div>
                        </div>
                        <div class="row" style="display: flex; justify-content: center; align-items: center">
                            <button class="btn btn-success" onclick="storeLang()">
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
        function editLang(_id, _dir){
            $('#langName').val($('#langName_' + _id).text());
            $('#langSymbol').val($('#langSymbol_' + _id).text());
            $('#currencyName').val($('#currencyName_' + _id).text());
            $('#currencySymbol').val($('#currencySymbol_' + _id).text());
            $('#langDir').val(_dir);
            $('#langState').val($('#langState_' + _id).attr('data-value'));

            $('#langInputId').val(_id);
            $('#langModalHeader').text("{{__('Edit Language')}}");
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
            $('#currencyName').val('');
            $('#currencySymbol').val('');
            $('#langInputId').val(0);
            $('#langState').val(1);
            $('#langModalHeader').text("{{__('New Language')}}");
            $('#langModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeLang(){
            let id = $('#langInputId').val();
            let name = $('#langName').val();
            let symbol = $('#langSymbol').val();
            let currencyName = $('#currencyName').val();
            let currencySymbol = $('#currencySymbol').val();
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
                        currencyName: currencyName,
                        currencySymbol: currencySymbol,
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

