@extends('admin.layout.adminLayout')

@section('head')
    <script src="{{asset('js/autosize.min.js')}}"></script>
    <style>
        .aboutUsDiv{
            min-height: 50vh;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            overflow: hidden;
        }
        .aboutUsText {
            width: 100%;
            color: white;
            min-height: 50vh;
            text-align: justify;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #00000042;
            font-size: 20px;
            padding: 30px;
        }
    </style>
@endsection

@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2 style="display: flex; ">
                {{__('Main page setting')}}
{{--                <a href="{{route('admin.destination.new')}}" class="addTagIcon" style="margin-left: 30px; color: green">--}}
{{--                    <i class="fas fa-plus-circle" style="cursor: pointer"></i>--}}
{{--                </a>--}}
            </h2>
        </div>
        <hr>
        <div class="col-md-12">
            <h3>
                {{__('About us')}}

            </h3>
            <div class="row">
                <div class="container-fluid">
                    <div id="aboutusBackground" class="aboutUsDiv" style="background: black; background-image: url({{ isset($aboutUs->pic) ? $aboutUs->pic : '#'}});
                                                        background-size: cover, contain;
                                                        background-repeat: no-repeat;
                                                        background-position: center;">
                        <div class="aboutUsText">
                            <div class="container">
                                <textarea name="aboutusText" id="text_aboutus" cols="30" rows="10" class="form-control"  style="color: white; background: inherit; text-align: justify; font-size: 20px">{!! isset($aboutUs->text) ? $aboutUs->text : '' !!}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="justify-content: space-evenly;">
                <button class="btn btn-success" onclick="changeHeaderText('aboutus')">{{__('Store about us text')}}</button>
                <label for="aboutusPic">
                    <div class="btn btn-warning">{{__('Change about us image')}}</div>
                </label>
                <input type="file" name="aboutusPic" id="aboutusPic" accept="image/*" style="display: none" onchange="changeAboutUsPic(this)">
            </div>
        </div>
    </div>

    <div class="modal" id="deleteModal">
        <div class="modal-dialog modal-lg" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Destination</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        Are you sure you want to remove the <span id="deletedDestinationName" style="font-weight: bold; color: red"></span>?
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" onclick="checkDestination()" data-dismiss="modal">Yes Deleted</button>
                    <input type="hidden" id="deletedDestinationId">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(window).ready(function(){
            autosize($('textarea'));
        });

        function changeAboutUsPic(_input){
            if(_input.files && _input.files[0]){
                let reader = new FileReader();
                reader.onload = function(e) {
                    openLoading();
                    let mainPic = e.target.result;
                    let data = new FormData();
                    data.append('pic', _input.files[0]);
                    data.append('header', 'aboutus');
                    data.append('_token', '{{csrf_token()}}');

                    $.ajax({
                        type: 'post',
                        url: '{{route("admin.setting.storeHeaderPicMainPage")}}',
                        data: data,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            try{
                                response = JSON.parse(response);
                                if(response['status'] == 'ok'){
                                    $('#aboutusBackground').css('background-image', 'url("' + response['url'] + '")');
                                    resultLoading("{{__('Image Change')}}", 'success');
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
                    })

                };
                reader.readAsDataURL(_input.files[0]);
            }
        }

        function changeHeaderText(_id){
            let text = $('#text_' + _id).val();
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("admin.setting.storeHeaderTextMainPage")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id,
                    text: text
                },
                success: function (response) {
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            resultLoading("{{__('Text updated')}}", 'success');
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
            })
        }
    </script>
@endsection
