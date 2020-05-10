@extends('admin.layout.adminLayout')

@section('head')

    <style>
        .picSection{
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .picDiv{
            padding: 5px;
            height: 200px;
            position: relative;
        }
        .picSetting{
            position: absolute;
            width: 100%;
            top: 0px;
            right: 0px;
            height: 0;
            transition: .2s;
            background: #000000ba;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .picDiv:hover .picSetting{
            height: 100%;
            padding: 15px;
        }
    </style>

@endsection

@section('body')

    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                Main Page Slider
            </h2>
        </div>
        <hr>
        <div class="col-md-12">
            <div class="picSection">
                @foreach($pics as $item)
                    <div id="picDiv_{{$item->showNumber}}" class="picDiv">
                        <div class="picSetting">
                            <select class="form-control" onchange="changeNumber(this.value, {{$item->id}})">
                                @for($i = 1; $i <= $pics[0]['showNumber']; $i++)
                                    <option value="{{$i}}" {{$i == $item->showNumber ? 'selected' : ''}}>{{$i}}</option>
                                @endfor
                            </select>
                            <button class="btn btn-danger" onclick="deletePic({{$item->id}})">Delete</button>
                        </div>
                        <img src="{{$item->pic}}" id="pic_{{$item->id}}" style="height: 100%">
                    </div>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="col-md-12">
            <label for="addPic" class="addTagIcon" style="display: flex; justify-content: center; align-items: center;">
                <i class="fas fa-plus-circle" style="cursor: pointer"></i>
            </label>
        </div>
    </div>

    <form id="newPicForm" action="{{route('admin.setting.mainPageSliderStore')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input id="addPic" type="file" name="pic" style="display: none" onchange="$('#newPicForm').submit()">
    </form>

@endsection

@section('script')
    <script !src="">
        function changeNumber(_value, _id){
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.mainPageSliderChangeNumber")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id,
                    newNumber: _value
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] = 'ok'){
                            $nPic = $('#picDiv_' + _value).html();
                            $rPic = $('#picDiv_' + response['prev']).html();

                            $('#picDiv_' + _value).html($rPic);
                            $('#picDiv_' + response['prev']).html($nPic);
                        }
                        else
                            console.log(response['status'])
                    }
                    catch (e) {

                    }
                },
                error: function(err){
                    console.log(err)
                }
            })
        }

        function deletePic(_id){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.mainPageSlider.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function (response) {
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            $('#picDiv_' + _id).remove();
                            resultLoading('Deleted', 'success');
                        }
                        else
                            resultLoading('Error 1', 'danger');
                    }
                    catch (e) {
                        console.error(e)
                        resultLoading('Error 2', 'danger');
                    }
                },
                error: function (e) {
                    console.error(e)
                    resultLoading('Error 3', 'danger');
                }
            })
        }
    </script>
@endsection

