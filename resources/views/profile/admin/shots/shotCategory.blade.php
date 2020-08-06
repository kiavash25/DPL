@extends('profile.layout.profileLayout')

@section('head')

    <style>
        .row{
            width: 100%;
            margin: 0px;
        }
        .modal-header{
            justify-content: space-between;
        }
        .modal-header .close{
            margin: 0px;
            padding: 0px;
        }
    </style>
@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                {{__('Shots categories')}}
            </h2>
        </div>
        <hr>

        <div class="col-md-12">
            <div class="row">

                <table id="tableNat" class="table table-striped  table-bordered">
                    <thead>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <th>{{__('language')}}</th>
                        <th>{{__('shots')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($category as $item)
                        <tr id="row_{{$item->id}}">
                            <td id="categoryName_{{$item->id}}">
                                {{$item->name}}
                            </td>
                            <td id="categoryLangs_{{$item->id}}">
                                @foreach($item->langs as $itLang)
                                    {{$itLang->name}} -
                                @endforeach
                            </td>
                            <td id="topic_{{$item->id}}">
                                {{$item->shots}}
                            </td>
                            <td>
                                <button class="btn btn-primary" onclick="editCategory({{$item->id}})">{{__('Edit')}}</button>
                                <button class="btn btn-danger" onclick="deleteCategory({{$item->id}})">{{__('Delete')}}</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row" style="justify-content: center">
                <div class="addTagIcon" style="margin-left: 30px; color: green" onclick="newCategory()">
                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                </div>
            </div>
        </div>


        <div class="modal" id="categoryModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="categoryModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="justify-content: center">
                            <input type="hidden" id="categoryInputId">
                            <div class="form-group">
                                <label for="categoryName">{{__('Name')}} (en)</label>
                                <input type="text" class="form-control" id="categoryName">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="categorySummery" style="font-weight: bold;">{{__('Other language')}}</label>
                                @foreach($langs as $lang)
                                    <div class="row form-group">
                                        <label for="lang_{{$lang->symbol}}">{{$lang->symbol}}</label>
                                        <input type="text" class="form-control langInput" langType="{{$lang->symbol}}" id="lang_{{$lang->symbol}}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center; align-items: center">
                            <button class="btn btn-success" onclick="storeCategory()">
                                {{__('Store')}}
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@section('script')
    <script>
        let langs = {!! $langs !!};
        let categories = {!! $category !!}
        function editCategory(_id){
            let cate = null;
            categories.forEach(item => {
               if(item.id == _id)
                   cate = item;
            });
            langs.forEach(item => {
                $('#lang_' + item.symbol).val('');
            });
            if(cate != null){
                for(let i = 0; i < cate.langs.length; i++)
                    $('#lang_' + cate.langs[i].lang).val(cate.langs[i].name);
            }

            $('#categoryName').val($('#categoryName_' + _id).text().trim());
            $('#categoryInputId').val(_id);
            $('#categoryModalHeader').text("{{__('Edit category')}}");
            $('#categoryModal').modal({backdrop: 'static', keyboard: false});
        }

        function newCategory(){
            $('#categoryName').val('');
            $('#categorySummery').val('');
            $('#categoryInputId').val(0);
            $('#categoryModalHeader').text("{{__('New category')}}");
            $('#categoryModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeCategory(){
            let otherLang = [];
            let id = $('#categoryInputId').val();
            let name = $('#categoryName').val();
            let otherLangDiv = $('.langInput');

            for(let i = 0; i < otherLangDiv.length; i++) {
                if($(otherLangDiv[i]).val().trim().length > 0)
                    otherLang.push({
                        name: $(otherLangDiv[i]).val(),
                        lang: $(otherLangDiv[i]).attr('langType')
                    });
            }

            if(name.trim().length > 0){
                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.shots.category.store")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                        name: name,
                        otherLang: JSON.stringify(otherLang),
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok')
                                location.reload();
                            else if(response['status'] == 'duplicate')
                                resultLoading("{{__('Category duplicate!')}}", 'danger');
                            else
                                resultLoading("{{__('Error in store')}}", 'danger');
                        }
                        catch(e){
                            console.log(e);
                            resultLoading("{{__('Error in result')}}", 'danger');
                        }
                    },
                    error: function(err){
                        resultLoading("{{__('Error in Server connection')}}", 'danger');
                    }
                })
            }
        }

        function deleteCategory(_id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.shots.category.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    if(response == 'ok')
                        $('#row_' + _id).remove();
                }
            })
        }
    </script>
@endsection

