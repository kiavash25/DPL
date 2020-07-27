@extends('profile.layout.profileLayout')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/admin/adminAllPages.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('semanticUi/semantic.css')}}">
    <script src="{{asset('semanticUi/semantic.min.js')}}"></script>

    <script src="{{asset('js/ckeditor.js')}}"></script>
    <script src="{{asset('js/ckeditorUpload.js')}}"></script>

    <style>
        .textEditor{
            height: 70vh;
            border: solid 1px var(--ck-color-toolbar-border) !important;
            border-top: none !important;
            border-radius: 5px !important;
        }

        .tab {
            float: left;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            width: 200px;
            /*height: 300px;*/
        }

        /* Style the buttons inside the tab */
        .tab button {
            display: block;
            background-color: inherit;
            color: black;
            padding: 22px 16px;
            width: 100%;
            border: none;
            outline: none;
            text-align: left;
            cursor: pointer;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current "tab button" class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            float: left;
            padding: 10px 12px;
            border: 1px solid #ccc;
            width: calc(100% - 201px);
            border-left: none;
            min-height: 70vh;
        }
    </style>
@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                {{$dest->name}} {{__('Descriptions')}}
            </h2>
        </div>
        <hr>

        <div class="col-md-12">

            <div class="tab">
                @foreach($category->titles as $key => $item)
                    <button class="tablinks {{$key == 0 ? 'active' : ''}}" onclick="openCity(event, 'titleTab{{$item->id}}')">{{$item->name}}</button>
                @endforeach
            </div>

            @foreach($category->titles as $key => $item)
                <div id="titleTab{{$item->id}}" class="tabcontent" style="display: {{$key == 0 ? 'block' : 'none'}}">
                    <h3>{{$item->name}} {{__('title')}}
                        <button class="btn btn-success" style="font-size: 13px" onclick="storeDescription({{$item->id}})">
                            {{__('Submit This Description')}}
                        </button>
                    </h3>
                    <div class="toolbar-container{{$key}}"></div>
                    <div id="titleDesc{{$key}}" class="textEditor">
                        {!! $item->text !!}
                    </div>

                    <div style="display: flex; justify-content: center;">
                        <button class="btn btn-success" style="font-size: 23px" onclick="storeDescription({{$item->id}})">
                            {{__('Submit This Description')}}
                        </button>
                    </div>
                </div>

            @endforeach

        </div>

    </div>
@endsection


@section('script')

    <script>

        let destId = {{$dest->id}};
        let titles = {!! $category->titles !!};
        let texts = [];
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        for(let i = 0; i < titles.length; i++){
            DecoupledEditor.create( document.querySelector( '#titleDesc' + i),{
                language: '{{app()->getLocale()}}'
            })
                .then( editor => {
                    const toolbarContainer = document.querySelector( 'main .toolbar-container' + i );
                    toolbarContainer.prepend( editor.ui.view.toolbar.element );

                    window.editor = editor;
                    texts[titles[i]['id']] = editor;

                    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                        let data = [destId, titles[i]['id']];
                        data = JSON.stringify(data);
                        return new MyUploadAdapter( loader, '{{route("admin.destination.storeDescriptionImg")}}', '{{csrf_token()}}', data);
                    };

                } )
                .catch( err => {
                    console.error( err.stack );
                } );
        }

        function storeDescription(_id){
            openLoading();

            let value = texts[_id].getData();
            $.ajax({
                type: 'post',
                url: '{{route("admin.destination.storeDescription")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    destId: destId,
                    value: value,
                    titleId: _id
                },
                success: function (response) {
                    try{
                        response =  JSON.parse(response);
                        if(response['status'] == 'ok'){
                            resultLoading('Description Stored', 'success');
                        }
                        else
                            resultLoading('Please try again', 'danger');
                    }
                    catch (e) {
                        resultLoading('Please try again', 'danger');
                    }
                },
                error: function(err){
                    resultLoading('Please try again', 'danger');
                }
            })
        }
    </script>

@endsection

