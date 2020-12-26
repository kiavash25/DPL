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

        .tagSection{
            display: flex;
            padding-bottom: 10px;
            border-bottom: solid;
            cursor: text;
            flex-wrap: wrap;
        }

        .tagSection .tag{
            background-color: #cccccc;
            padding: 3px 10px;
            border-radius: 20px;
            margin: 2px 5px;
        }

        .tag .cancelTag{
            cursor: pointer;
        }

        .tagSection .inputTag{
            border: none;
            margin-left: 10px;
        }
        .listOfShots{
            display: flex;
            flex-wrap: wrap;
        }
        .shotImgDiv{
            margin: 10px;
            position: relative;
            height: 100px;
            overflow: hidden;
        }
        .hoverShotImg{
            position: absolute;
            top: 0px;
            right: 0px;
            width: 100%;
            height: 100%;
            background: #00000094;
            flex-direction: column;
            align-items: center;
            color: white;
            justify-content: space-around;
            display: none;
        }
        .hoverShotImg .name{
            text-align: center;
        }
        .shotImgDiv:hover .hoverShotImg{
            display: flex;
        }
        .hoverShotImg .btn{
            padding: 2px 10px;
            font-size: 12px;
        }
    </style>
@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                {{__('Our shots')}}
            </h2>
        </div>
        <hr>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="category">{{__('Category')}}</label>
                    <select id="category" class="form-control" onchange="changeShowShots(this.value)">
                        <option value="0">{{__('All')}}</option>
                        @foreach($category as $item)
                            <option value="{{$item['id']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3">
                    <div class="addTagIcon" style="margin-left: 30px; color: green" onclick="newShots()">
                        <i class="fas fa-plus-circle" style="cursor: pointer"></i>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div id="listOfShots" class="col-md-12 listOfShots">
            @foreach($shots as $item)
                <div class="shotImgDiv">
                    <img src="{{$item->pic200}}" style="max-width: 100%; max-height: 100%">
                    <div class="hoverShotImg">
                        <div class="name">{{$item->name}}</div>
                        <div class="buttons">
                            <button class="btn btn-primary" onclick="editThisShot({{$item->id}})">{{__('Edit')}}</button>
                            <button class="btn btn-danger" onclick="deleteThisShot({{$item->id}})">{{__('Delete')}}</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="modal" id="picsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row" style="justify-content: center">
                            <input type="hidden" id="shotId">
                            <div class="col-md-8 form-group">
                                <label for="categoryName">{{__('Name')}}</label>
                                <input type="text" class="form-control" id="shotName">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="categoryId">{{__('Category')}}</label>
                                <select id="categoryId" class="form-control">
                                    @foreach($category as $item)
                                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="shotText">{{__('Description')}}</label>
                                <textarea id="shotText" class="form-control" rows="3" maxlength="290"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="shotTags">{{__('Tag')}}</label>
                                <div id="tagSection" class="tagSection">
                                    <input type="text" class="inputTag" id="newTag" placeholder="Enter new tag" onchange="addNewTag(this.value)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label style="width: 100%">{{__('image')}}</label>
                                <input type="file" accept="image/*" id="shotPic" style="display: none" onchange="changeNewShotPic(this)">
                                <label for="shotPic" style="cursor: pointer">
                                    <img id="newShotPic" src="{{URL::asset('images/mainImage/Placeholder.png')}}" style=" height: 120px">
                                </label>
                            </div>
                        </div>

                        <div class="row" style="display: flex; justify-content: center; align-items: center">
                            <button class="btn btn-success" onclick="storeShot()">
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
        let tags = [];
        let shotPic = null;
        let allShots = {!! $shots !!};
        let defaultPic = "{{URL::asset('images/mainImage/Placeholder.png')}}";

        $('#tagSection').click(function() {
            $('#newTag').focus();
        });

        $('#newTag').keypress(function (e) {
            var key = e.which;
            if(key == 13)
                addNewTag($('#newTag').val());
        });

        function addNewTag(_name){
            if(_name.search(',') != -1)
                _name = _name.split(',');
            else
                _name = [_name];
            _name.map(item => {
                item = item.trim();
                if(tags.indexOf(item) == -1){
                    text = '<div class="tag">\n' +
                        '   <span>' + item + '</span>\n' +
                        '   <span class="cancelTag" onclick="cancelThisTag(this)"> × </span>\n' +
                        '</div>';
                    tags.push(item);
                    $(text).insertBefore('#newTag');
                }
            });

            $('#newTag').val('');
        }

        function cancelThisTag(_element){
            let name = $(_element).prev().text();
            let index = tags.indexOf(name);
            tags.splice(index, 1);
            $(_element).parent().remove();
        }

        function changeNewShotPic(input){
            if(input.files && input.files[0]){
                var reader = new FileReader();
                reader.onload = function(e) {
                    shotPic = input.files[0];
                    $('#newShotPic').attr('src',  e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function newShots(){
            tags = [];
            $('#tagSection').find('.tag').remove();

            $('#modalHeader').text('new shot');
            $('#shotId').val(0);
            $('#newShotPic').attr('src', defaultPic);
            $('#shotName').val('');
            $('#shotText').val('');
            $('#picsModal').modal({backdrop: 'static', keyboard: false});
        }

        function storeShot(){
            let id = $('#shotId').val();
            let name = $('#shotName').val();
            let text = $('#shotText').val();
            let categoryId = $('#categoryId').val();

            if(name.trim().length > 0 && ((id == 0 && shotPic != null) || id != 0)){
                var data = new FormData();
                data.append('_token', '{{csrf_token()}}');
                data.append('id', id);
                data.append('name', name);
                data.append('categoryId', categoryId);
                data.append('text', text);
                data.append('pic', shotPic);
                data.append('tag', JSON.stringify(tags));

                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("admin.shots.ourShot.store")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        if(response == 'ok')
                            location.reload();
                        else
                            resultLoading("{{__('Error in store')}}", 'danger');
                    },
                    error: function(err){
                        console.log(err);
                        resultLoading("{{__('Error in Server connection')}}", 'danger');
                    }
                })
            }
        }

        function changeShowShots(_value){
            let text = '';

            allShots.forEach(item => {
                if(item.categoryId == _value || _value == 0) {
                    text += '<div class="shotImgDiv">\n' +
                        '   <img src="' + item["pic200"] + '" style="max-width: 100%; max-height: 100%">\n' +
                        '   <div class="hoverShotImg">\n' +
                        '       <div class="name">' + item["name"] + '</div>\n' +
                        '       <div class="buttons">\n' +
                        '           <button class="btn btn-primary" onclick="editThisShot(' + item["id"] + ')">{{__("Edit")}}</button>\n' +
                        '           <button class="btn btn-danger" onclick="deleteThisShot(' + item["id"] + ')">{{__("Delete")}}</button>\n' +
                        '       </div>\n' +
                        '   </div>\n' +
                        '</div>';
                }
            });

            $('#listOfShots').html(text);
        }

        function editThisShot(_id){
            let shot = null;
            allShots.forEach(item => {
               if(item.id == _id)
                   shot = item;
            });

            if(shot != null) {
                tags = [];
                $('#tagSection').find('.tag').remove();

                $('#shotId').val(_id);
                $('#shotName').val(shot.name);
                $('#shotText').val(shot.text);
                $('#categoryId').val(shot.categoryId);
                $('#newShotPic').attr('src', shot.pic200);
                $('#modalHeader').text('edit shot');
                for (let i = 0; i < shot.tag.length; i++)
                    addNewTag(shot.tag[i]);

                $('#picsModal').modal({backdrop: 'static', keyboard: false});
            }

        }

        function deleteThisShot(_id){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.shots.ourShot.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    if(response == 'ok')
                        location.reload();
                    else
                        resultLoading("{{__('Error in delete')}}", 'danger');
                },
                error: function(err){
                    console.log(err);
                    resultLoading("{{__('Error in Server connection')}}", 'danger');
                }
            })
        }
    </script>
@endsection

