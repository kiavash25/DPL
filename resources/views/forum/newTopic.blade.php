@extends('forum.forumLayout')

@section('forumHeader')
    <script src="{{asset('js/ckeditor.js')}}"></script>

    <style>
        .textEditor{
            height: 30vh;
            border: solid 1px var(--ck-color-toolbar-border) !important;
            border-top: none !important;
            border-radius: 5px !important;
        }
        label{
            margin-bottom: 0;
            color: #235a79;
        }
        .submitTopicButton{
            border: none;
            background: #00bd00;
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            margin: 0px 15px;
            cursor: pointer;
        }
        .cancelTopicButton{
            color: gray;
        }
        .storeTopicFooter{
            display: flex;
            justify-content: center;
            border-top: solid #bdbdbd 1px;
            margin: 15px 0px 0px 0px;
            padding-top: 10px;
            align-items: baseline;
            width: 100%;
            flex-direction: column;
        }
        .buttonRows{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        .errors li{
            color: red;
        }
    </style>
@endsection

@section('forumBody')

    <div class="mainForumSection">
        <div class="mainForumHeaderList">
            {{$header}}
        </div>

        <div class="mainForumBodyList">
            <div class="row ">
                <div class="col-md-8 form-group">
                    <label for="title">{{__('Topic Title')}}</label>
                    <input type="text" class="form-control" id='title' maxlength="190" required />
                </div>
                <div class="col-md-4 form-group">
                    <label for="category">{{__('Category')}}</label>
                    <select class="form-control" id="category">
                        @foreach($categories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="description" class="inputLabel">{{__('Description')}}</label>
                    <div class="toolbar-container"></div>
                    <div id="description" class="textEditor" ></div>
                </div>
            </div>

            <div class="row">
                @for($i = 1; $i < 5; $i++)
                    <div class="col-md-3 form-group">
                        <label for="tag{{$i}}">{{__('Tag')}} {{$i}}</label>
                        <input type="text" class="form-control" id="tag{{$i}}">
                    </div>
                @endfor
            </div>

            <div class="row storeTopicFooter">
                <div class="errors">
                    <ul id="errors"></ul>
                </div>
                <div class="buttonRows">
                    <button class="submitTopicButton" onclick="storeTopic()">{{__('Send Topic')}}</button>
                    <a href="{{route('forum.index')}}" class="cancelTopicButton">{{__('Cancel')}}</a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('forumScript')

    <script>
        let lang = '{{app()->getLocale()}}';

        DecoupledEditor.create( document.querySelector('#description'), {
            toolbar: [ 'heading', 'bold', 'italic', 'link' ],
            language: '{{app()->getLocale()}}'
        }).then( editor => {
            const toolbarContainer = document.querySelector( '.toolbar-container');
            toolbarContainer.prepend( editor.ui.view.toolbar.element );
            window.editor = editor ;
        } )
            .catch( err => {
                console.error( err.stack );
            } );

        function storeTopic(){
            let title = $('#title').val();
            let description = window.editor.getData();
            let category = $('#category').val();
            let tags = [];
            let error = false;

            $('#errors').html('');

            if(title.trim().length < 2){
                error = true;
                $('#errors').append("<li>{{__('Title required')}}</li>");
            }
            if(description.trim().length < 2){
                error = true;
                $('#errors').append("<li>{{__('Description required')}}</li>");
            }

            if(!error){
                for(i = 1; i < 5; i++)
                    tags.push($('#tag'+i).val());

                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("forum.storeTopic")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        title: title,
                        description: description,
                        category: category,
                        lang: lang,
                        tags: JSON.stringify(tags),
                    },
                    success: function(response){
                        if(response == 'ok'){
                            resultLoading('{{__('Your topic stored')}}', 'success');
                            location.href = '{{url("topicalDiscussion/category/")}}/' + category;
                        }
                        else
                            resultLoading('{{__('There was a problem registering the topic. Please try again')}}', 'danger');
                    },
                    error: function (err) {
                        resultLoading('{{__('There was a problem registering the topic. Please try again')}}', 'danger');
                    }
                })
            }
        }
    </script>
@endsection
