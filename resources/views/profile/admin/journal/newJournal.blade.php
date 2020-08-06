@extends('profile.layout.profileLayout')

@section('head')
    <link rel="stylesheet" href="{{asset('css/admin/adminAllPages.css')}}">

    <link rel="stylesheet" href="{{asset('css/common/jquery-ui.css')}}">
    <script src="{{asset('js/jquery-ui.js')}}"></script>

    <style>
        .videoButton{
            cursor: pointer;
            padding: 10px;
            font-size: 13px;
            border-radius: 10px;
            background-color: #30759d;
            color: white;
        }
        .textEditor{
            height: 400px;
            border: solid 1px var(--ck-color-toolbar-border) !important;
            border-top: none !important;
            border-radius: 5px !important;
        }
    </style>

@endsection


@section('body')
    <div class="row whiteBase" style="margin-bottom: 100px">
        <div class="col-md-12">
            <h2>
                @if($kind == 'new')
                    {{__('Create New Journal')}}
                @else
                    {{__('Edit')}} {{$journal->name}}
                @endif
            </h2>
        </div>
        <hr>

        <div class="col-md-12">

            <input type="hidden" id="code" name="code" value="{{isset($code) ? $code : 0}}">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="inputLabel">{{__('Journal Title')}}</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="{{__('Journal Title')}}" value="{{isset($journal->name) ? $journal->name : ''}}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="releaseDateType" class="inputLabel">{{__('Release Type')}}</label>
                        <select name="releaseDateType" id="releaseDateType" class="form-control" onchange="changeRelease(this.value)">
                            <option value="draft" {{isset($journal->releaseDateType) && $journal->releaseDateType == 'draft' ? 'selected': ''}}>{{__("Draft")}}</option>
                            <option value="now" {{isset($journal->releaseDateType) && $journal->releaseDateType == 'now' ? 'selected': ''}}>{{__('Now')}}</option>
                            <option value="future" {{isset($journal->releaseDateType) && $journal->releaseDateType == 'future' ? 'selected': ''}}>{{__('select date')}}</option>
                        </select>
                    </div>
                </div>
                <div id="selectReleaseDate" class="col-md-3" style="display: {{isset($journal->releaseDateType) && $journal->releaseDateType == 'future' ? 'block': 'none'}};">
                    <label for="releaseDate" class="inputLabel">{{__('Release Date')}}</label>
                    <input type="text" id="releaseDate"  class="form-control" name="releaseDate" readonly>
                </div>
            </div>

            <div class="row marg30">
                <div class="col-xl-3">
                    <div class="row">
                        <div class="form-group">
                            <label for="categoryId" class="inputLabel">{{__('Journal Category')}}</label>
                            <select name="categoryId" id="categoryId" class="form-control">
                                <option value="0" >....</option>
                                @foreach($category as $item)
                                    <option value="{{$item->id}}" {{isset($journal->categoryId) && $item->id == $journal->categoryId ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="form-group">
                        <label for="summery" class="inputLabel">{{__('Journal Summery')}}</label>
                        <input type="text" id="summery" name="summery" class="form-control" placeholder="{{__('Journal Summery')}}" maxlength="100" value="{{isset($journal->summery) ? $journal->summery : ''}}">
                    </div>
                </div>
            </div>

            <div class="row marg30">
                <div style="width: 100%; height: 450px;">
                    <div class="toolbar-container"></div>
                    <div id="titleDesc" class="textEditor">{!! isset($journal->text) ? $journal->text : '' !!}</div>
                </div>
            </div>

            <div class="row marg30">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="seoTitle" class="inputLabel">{{__('Seo Title')}}:</label>
                        <input type="text" name="seoTitle" id="seoTitle" class="form-control" value="{{isset($journal->seoTitle) ? $journal->seoTitle : ''}}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="slug" class="inputLabel">{{__('Slug')}}:</label>
                        <input type="text" name="slug" id="slug" class="form-control" value="{{isset($journal->slug) ? $journal->slug : ''}}">
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="form-group">
                        <label for="keyword" class="inputLabel">{{__('Keyword')}}:</label>
                        <input type="text" name="keyword" id="keyword" class="form-control" value="{{isset($journal->keyword) ? $journal->keyword : ''}}">
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="form-group">
                        <label for="meta" class="inputLabel">{{__('Meta')}}:</label>
                        <textarea name="meta" id="meta" class="form-control">{!! isset($journal->meta) ? $journal->meta : '' !!}</textarea>
                    </div>
                </div>

                <div class="col-lg-12" style="display: flex; justify-content: center">
                    <button class="btn btn-primary" onclick="checkSeo()">{{__('Check Seo')}}</button>
                </div>
                <div id="seoResult" class="col-lg-12" style="background: #f7f7f7; padding: 15px; border-radius: 10px"></div>
            </div>


            <div class="row marg30">
                <div class="col-md-3 centerContent" style="flex-direction: column; justify-content: end">
                    <label class="inputLabel">
                        {{__('Main Picture')}}
                    </label>
                    <label for="mainPic" class="mainPicSection">
                        <img id="mainPicImg" src="{{isset($journal->pic) && $journal->pic != null ? $journal->pic : '#'}}" style="width: 100%; display: {{isset($journal->pic) && $journal->pic != null ? 'block' : 'none'}};" >
                        <img src="{{asset('images/mainImage/loading.gif')}}" style="width: 100%; display: none;" >
                        <i class="fas fa-plus-circle" style="cursor: pointer;  display: {{isset($journal->pic) && $journal->pic != null ? 'none' : 'block'}};"></i>
                    </label>

                    <input type="file" name="mainPic" id="mainPic" accept="image/*" style="display: none" onchange="showPics(this, 'mainPicImg', showMainPic)">
                </div>
                <div class="col-md-9">
                    <div class="form-group">
                        <label class="inputLabel">{{__('Journal Tags')}}</label>
                        <div class="row" style="width: 100%">
                            @if(isset($journal->tags) && count($journal->tags) != 0)
                                @for($i = 0; $i < count($journal->tags); $i++)
                                    <div class="col-lg-3 col-md-4">
                                        <div class="form-group" style="position: relative">
                                            <input type="text" name="tags[]" class="form-control" placeholder="{{__('Tag')}}" onkeyup="findTag(this)"onfocus="clearAllSearchResult()" onchange="closeSearch(this)" value="{{$journal->tags[$i]}}">
                                            <div class="closeTagIcon" onclick="deleteTag(this)">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>

                                        <div class="tagSearchResult"></div>
                                    </div>
                                @endfor
                            @else
                                @for($i = 0; $i < 5; $i++)
                                    <div class="col-lg-3 col-md-4">
                                        <div class="form-group" style="position: relative">
                                            <input type="text" name="tags[]" class="form-control" placeholder="{{__('Tag')}}" onkeyup="findTag(this)"onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)">
                                            <div class="closeTagIcon" onclick="deleteTag(this)">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>

                                        <div class="tagSearchResult"></div>
                                    </div>
                                @endfor
                            @endif
                            <div id="addNewTag" class="col-lg-2 col-md-2">
                                <div class="addTagIcon">
                                    <i class="fas fa-plus-circle" style="cursor: pointer" onclick="addTag()"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"></div>

            <div class="row marg30" style="display: flex; justify-content: center; flex-direction: column; align-items: center">
                <button class="btn btn-success" style="font-size: 30px; border-radius: 20px; width: 100%;; margin-top: 20px" onclick="storeData()">{{__('Submit')}}</button>
            </div>

        </div>

    </div>
@endsection


@section('script')

    <script src="{{asset('js/ckeditor.js')}}"></script>
    <script src="{{asset('js/ckeditorUpload.js')}}"></script>
    <script>
        let mainPic;
        let journalId = {{isset($journal->id) ? $journal->id : 0}};
        let code = {{isset($code) ? $code : 0}};
        let textEditor;
        let tagSelected;

        $('#category')
            .dropdown({
                clearable: true,
                placeholder: 'any'
            });

        function deleteTag(_element){
            $(_element).parent().parent().remove();
        }
        function addTag() {
            text = '<div class="col-md-3">\n' +
                '<div class="form-group" style="position: relative">\n' +
                '<input type="text" name="tags[]" class="form-control" placeholder="{{__("Tag")}}" onkeyup="findTag(this)" onfocus="clearAllSearchResult()" onfocusout="closeSearch(this)" onchange="closeSearch(this)"> \n' +
                '<div class="closeTagIcon" onclick="deleteTag(this)">\n' +
                '<i class="fas fa-times"></i>\n' +
                '</div>\n' +
                '</div>\n' +
                '<div class="tagSearchResult"></div>' +
                '</div>';

            $(text).insertBefore($('#addNewTag'));
        }

        $('#releaseDate').datepicker();
        $('#releaseDate').datepicker("option", "dateFormat", "yy-mm-dd");
        @if(isset($journal->releaseDateType) && $journal->releaseDateType == 'future')
            let releaseDate = '{{$journal->releaseDate}}'
            let exploded = releaseDate.split('-');
            $('#releaseDate').datepicker("setDate", new Date(exploded[0],exploded[1],exploded[2]) );
        @endif

        function changeRelease(_value){
            if(_value == 'future'){
                $('#selectReleaseDate').css('display', 'block');
            }
            else{
                $('#selectReleaseDate').css('display', 'none');
            }
        }

        function findTag(_element){
            var value = $(_element).val();

            if(value.trim().length != 0){
                $.ajax({
                    type: 'post',
                    url: '{{route("findTag")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        tag: value
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            tagSelected = _element;
                            var answers = '';
                            var tags = response[1];
                            for(i = 0; i < tags.length; i++)
                                answers += '<div class="tagResult" onclick="setTag(this)">' + tags[i]["tag"] + '</div>';

                            $(_element).parent().next().html(answers);
                            if(answers == '')
                                $(_element).parent().next().css('display', 'none');
                            else
                                $(_element).parent().next().css('display', 'flex');
                        }
                    }
                })
            }
            else{
                $(_element).parent().next().html('');
                $(_element).parent().next().css('display', 'none');
            }

        }

        function setTag(_element){
            var value = $(_element).text();
            $(tagSelected).val(value);

            $(tagSelected).parent().next().html('');
            $(tagSelected).parent().next().css('display', 'none');

            tagSelected = '';
        }

        function clearAllSearchResult(){
            $('.tagSearchResult').html('');
            $('.tagSearchResult').css('display', 'none');
        }

        function closeSearch(_element){
            setTimeout(function () {
                $(_element).parent().next().html('');
                $(_element).parent().next().css('display', 'none');
            }, 100)
        }

        function showMainPic(_pic){
            mainPic = _pic;
            $('#mainPicImg').css('display', 'block');
            $('#mainPicImg').next().next().css('display', 'none');
        }

        function checkSeo(){
            openLoading();

            var name = $('#name').val();
            var summery = $('#summery').val();
            var description = textEditor.getData();
            var categoryId = $('#categoryId').val();
            var keyword = $('#keyword').val();
            var meta = $('#meta').val();
            var slug = $('#slug').val();
            var seoTitle = $('#seoTitle').val();
            var releaseDate = $('#releaseDate').val();
            var releaseDateType = $('#releaseDateType').val();
            var tagsElement = $("input[name*='tags']");
            var tags = [];
            var error = '<ul>';

            for(i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val() != null && $(tagsElement[i]).val().trim().length != 0)
                    tags[tags.length] = $(tagsElement[i]).val().trim();
            }

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(categoryId == 0)
                error += '<li> Please Choose Category.</li>';

            if(keyword == 0)
                error += '<li> Please Fill Keyword.</li>';
            if(slug == 0)
                error += '<li> Please Fill Slug.</li>';
            if(seoTitle == 0)
                error += '<li> Please Fill Seo Title.</li>';
            if(meta == 0)
                error += '<li> Please Fill Meta.</li>';

            if(releaseDateType == 'future' && releaseDate == '')
                error += '<li> Please Choose Release Date.</li>';

            if(error != '<ul>'){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                var data = new FormData();

                data.append('_token', '{{csrf_token()}}');
                data.append('name', name);
                data.append('description', description);
                data.append('summery', summery);
                data.append('keyword', keyword);
                data.append('slug', slug);
                data.append('seoTitle', seoTitle);
                data.append('meta', meta);
                data.append('categoryId', categoryId);
                data.append('releaseDateType', releaseDateType);
                data.append('releaseDate', releaseDate);
                data.append('tags', JSON.stringify(tags));
                data.append('id', journalId);
                data.append('code', code);
                data.append('pic', mainPic);

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.journal.checkSeo")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                $('#seoResult').html('');

                                $('#seoResult').append(response['result'][0]);
                                $('#seoResult').append(response['result'][1]);
                                $('#seoResult').append(response['result'][2]);

                                closeAlert(0);
                            }
                            else{
                                resultLoading('Opss1 ...', 'danger');
                            }
                        }
                        catch (e) {
                            resultLoading('Opss2 ...', 'danger');
                        }
                    },
                    error: function(err){
                        resultLoading('Opss3 ...', 'danger');
                    }
                })

            }

        }

        function storeData(){

            openLoading();

            var name = $('#name').val();
            var summery = $('#summery').val();
            var description = textEditor.getData();
            var categoryId = $('#categoryId').val();
            var keyword = $('#keyword').val();
            var meta = $('#meta').val();
            var slug = $('#slug').val();
            var seoTitle = $('#seoTitle').val();
            var releaseDate = $('#releaseDate').val();
            var releaseDateType = $('#releaseDateType').val();
            var tagsElement = $("input[name*='tags']");
            var tags = [];
            var error = '<ul>';

            for(i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val() != null && $(tagsElement[i]).val().trim().length != 0)
                    tags[tags.length] = $(tagsElement[i]).val().trim();
            }

            if(name.trim().length == 0)
                error += '<li> Please Choose Name.</li>';

            if(categoryId == 0)
                error += '<li> Please Choose Category.</li>';

            if(keyword == 0)
                error += '<li> Please Fill Keyword.</li>';
            if(slug == 0)
                error += '<li> Please Fill Slug.</li>';
            if(seoTitle == 0)
                error += '<li> Please Fill Seo Title.</li>';
            if(meta == 0)
                error += '<li> Please Fill Meta.</li>';

            if(releaseDateType == 'future' && releaseDate == '')
                error += '<li> Please Choose Release Date.</li>';

            if(error != '<ul>'){
                error += '</ul>';
                resultLoading(error, 'danger');
            }
            else{
                var data = new FormData();

                data.append('_token', '{{csrf_token()}}');
                data.append('name', name);
                data.append('description', description);
                data.append('summery', summery);
                data.append('keyword', keyword);
                data.append('slug', slug);
                data.append('seoTitle', seoTitle);
                data.append('meta', meta);
                data.append('categoryId', categoryId);
                data.append('releaseDateType', releaseDateType);
                data.append('releaseDate', releaseDate);
                data.append('tags', JSON.stringify(tags));
                data.append('id', journalId);
                data.append('code', code);
                data.append('pic', mainPic);

                $.ajax({
                    type: 'post',
                    url: '{{route("admin.journal.store")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                code = 0;
                                journalId = response['result'];
                                resultLoading('Journal Stored', 'success');
                            }
                            else
                                resultLoading('Please Try Again', 'danger');
                        }
                        catch (e) {
                            resultLoading('error1', 'danger')
                        }
                    },
                    error: function(err){
                        resultLoading('Please Try Again', 'danger');
                    }
                })
            }
        }

        DecoupledEditor.create( document.querySelector( '#titleDesc'), {
            language: '{{app()->getLocale()}}'
        })
            .then( editor => {
                const toolbarContainer = document.querySelector( 'main .toolbar-container');
                toolbarContainer.prepend( editor.ui.view.toolbar.element );

                window.editor = editor;
                textEditor = editor;

                editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                    let data = {
                        id: journalId,
                        code: code
                    };
                    data = JSON.stringify(data);
                    return new MyUploadAdapter( loader, '{{route("admin.journal.storeDescriptionImg")}}', '{{csrf_token()}}', data);
                };

            } )
            .catch( err => {
                console.error( err.stack );
            } );

    </script>

@endsection

