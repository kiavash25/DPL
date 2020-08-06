@extends('forum.forumLayout')

@section('forumHeader')
    <script src="{{asset('js/ckeditor.js')}}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
@endsection

@section('forumBody')
    <?php
        $dirs = 'ltr';
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
        if(isset($showLang->direction) && $showLang->direction == 'rtl')
            $dirs = 'rtl';
    ?>

    <div class="mainForumSection">
        <div class="mainForumBodyList">

            <div style="position: relative;">
                <h2 class="topicTitle">
                    {{$topic->title}}
                </h2>
                <div class="topicUser">
                    <div class="userPic">
                        <img src="{{$topic->userPic}}" class="resizeImage" style="width: 100%">
                    </div>
                    <div class="userInfo">
                        <div class="username">
                            {{$topic->username}}
                        </div>
                        <div class="time">
                            {{$topic->time}}
{{--                            <span class="seenTopic">--}}
{{--                                {{$topic->seen}}--}}
{{--                                <i class="fas fa-eye"></i>--}}
{{--                            </span>--}}
                        </div>
                    </div>
                </div>
                <div class="topicTextSection">
                    <div class="text">
                        {!! $topic->text !!}
                    </div>
                    <div class="footerTextTopic mainTopic">
                        <div class="tagSection">
                            @foreach($topic->tag as $tag)
                                <label class="forumTag">#{{$tag}}</label>
                            @endforeach
                        </div>
                        <div class="topicOptions">
                            <a href="#youAnsSection" class="topicLike mtooltip">
                                <i class="fa fa-reply" aria-hidden="true"></i>
                                <span class="tooltiptext">{{__('Reply')}}</span>
                            </a>
{{--                            <div class="dropdown topicLike mtooltip">--}}
{{--                                <div class="dropdown-toggle"  data-toggle="dropdown" >--}}
{{--                                    <i class="fas fa-share-alt"></i>--}}
{{--                                </div>--}}
{{--                                <div class="dropdown-menu">--}}
{{--                                    <a class="dropdown-item" href="#">--}}
{{--                                        <i class="fab fa-whatsapp" aria-hidden="true"></i>--}}
{{--                                        Whats app--}}
{{--                                    </a>--}}
{{--                                    <a class="dropdown-item" href="#">--}}
{{--                                        <i class="fab fa-telegram" aria-hidden="true"></i>--}}
{{--                                        Telegram--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                                <span class="tooltiptext">{{__('Share')}}</span>--}}
{{--                            </div>--}}
                            <div class="topicLike dislikeSection mtooltip {{$topic->youLike == -1 ? 'chosenLike' : ''}}" onclick="likeTopicRely({{$topic->id}}, 'topic', -1, this)">
                                <span class="dislikeCount">{{$topic->dislike}}</span>
                                <i class="far fa-thumbs-down" aria-hidden="true"></i>
                                <span class="tooltiptext">{{__('DisLike')}}</span>
                            </div>
                            <div class="topicLike likeSection mtooltip {{$topic->youLike == 1 ? 'chosenLike' : ''}}" onclick="likeTopicRely({{$topic->id}}, 'topic', 1, this)">
                                <span class="likeCount">{{$topic->like}}</span>
                                <i class="far fa-thumbs-up" aria-hidden="true"></i>
                                <span class="tooltiptext">{{__('Like')}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dropdown {{$dirs == 'rtl' ? 'dropright float-left' : 'dropleft float-right'}} reportSection">
                    <div class="dropdown-toggle "  data-toggle="dropdown">
                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    </div>
                    <div class="dropdown-menu">
{{--                        <div class="dropdown-item">--}}
{{--                            {{__('Report this title')}}--}}
{{--                        </div>--}}
                        @if(isset($topic->canDelete) && $topic->canDelete)
                            <div class="dropdown-item" style="color: red" onclick="deleteTopicModal({{$topic->id}})">
                                {{__('Delete this title')}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="topicAnswersSection">
                <div class="answerNumber" style="border-bottom: solid #bdbdbd 1px;">
                    {{$topic->repliesCount}} {{__('Answers')}}
                </div>

                <div class="ansSection">
                    @foreach($topic->replies as $replies)
                        <div class="ansRow {{isset($replies->bestAns) && $replies->bestAns ? 'bestAnswer' : ''}}">
                            <div class="topicUser">
                                <div class="userPic">
                                    <img src="{{$replies->userPic}}" class="resizeImage" style="width: 100%">
                                </div>
                                <div class="userInfo">
                                    <div class="username">
                                        {{$replies->username}}
                                        <label class="bestAnswerLabel">{{__('Best Answer')}}</label>
                                    </div>
                                    <div class="time">
                                        {{$replies->time}}
                                    </div>
                                </div>
                            </div>
                            <div class="topicTextSection">
                                <div class="text">
                                    {!! $replies->text !!}
                                </div>
                                <div class="footerTextTopic">
                                    <div class="topicOptions">
{{--                                        <div class="dropdown topicLike mtooltip">--}}
{{--                                            <div class="dropdown-toggle "  data-toggle="dropdown">--}}
{{--                                                <i class="fas fa-share-alt"></i>--}}
{{--                                            </div>--}}
{{--                                            <div class="dropdown-menu">--}}
{{--                                                <a class="dropdown-item" href="#">--}}
{{--                                                    <i class="fab fa-whatsapp" aria-hidden="true"></i>--}}
{{--                                                    Whats app--}}
{{--                                                </a>--}}
{{--                                                <a class="dropdown-item" href="#">--}}
{{--                                                    <i class="fab fa-telegram" aria-hidden="true"></i>--}}
{{--                                                    Telegram--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                            <span class="tooltiptext">{{__('Share')}}</span>--}}
{{--                                        </div>--}}
                                        <div class="topicLike dislikeSection mtooltip {{$replies->youLike == -1 ? 'chosenLike': ''}}" onclick="likeTopicRely({{$replies->id}}, 'reply', -1, this)">
                                            <span class="dislikeCount">{{$replies->dislike}}</span>
                                            <i class="far fa-thumbs-down" aria-hidden="true"></i>
                                            <span class="tooltiptext">{{__('DisLike')}}</span>
                                        </div>
                                        <div class="topicLike likeSection mtooltip {{$replies->youLike == 1 ? 'chosenLike': ''}}" onclick="likeTopicRely({{$replies->id}}, 'reply', 1, this)">
                                            <span class="likeCount">{{$replies->like}}</span>
                                            <i class="far fa-thumbs-up" aria-hidden="true"></i>
                                            <span class="tooltiptext">{{__('Like')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown {{$dirs == 'rtl' ? 'dropright float-left' : 'dropleft float-right'}}  reportSection">
                                <div class="dropdown-toggle "  data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                </div>
                                <div class="dropdown-menu">
{{--                                    <div class="dropdown-item">--}}
{{--                                        {{__('Report this answer')}}--}}
{{--                                    </div>--}}
                                    @if(isset($topic->yourTopic) && $topic->yourTopic)
                                        <div class="dropdown-item" style="color: green" onclick="setBestAns({{$replies->id}})">
                                            {{__('Choose for best answer')}}
                                        </div>
                                    @endif
                                    @if(isset($replies->canDelete) && $replies->canDelete)
                                        <div class="dropdown-item" style="color: red" onclick="deleteReplyModal({{$replies->id}})">
                                            {{__('Delete this answer')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

{{--                <div class="mainForumPagination" style="padding-top: 15px; border-top: solid #bdbdbd 1px;">--}}
{{--                    <ul class="pagination">--}}
{{--                        <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="#">1</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="#">2</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="#">3</a></li>--}}
{{--                        <li class="page-item"><a class="page-link" href="#">Next</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
            </div>

            <div id="youAnsSection" class="topicYourAns">
                <div class="answerNumber" style="margin-bottom: 10px">
                    {{__('Your Answer')}}
                </div>
                @if(auth()->check())
                    <div class="toolbar-container"></div>
                    <div id="youAns" class="textEditor" ></div>
                    <div class="sendAnsRow">
                        <button onclick="sendAnswers()">
                            {{__('Post your answer')}}
                        </button>
                    </div>
                @else
                    <div class="sendAnsRow" style="padding: 0; justify-content: center;">
                        <a href="{{route('loginPage')}}">
                            {{__('Login / Register')}}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" id="deletedId" value="0">

    <div class="modal" id="deleteReplyModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Delete reply')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{__('error.deleteTopicReply')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                    <button type="button" class="btn btn-danger" onclick="doDeleteReply()">{{__('Delete')}}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal" id="deleteTopicModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{__('Delete topic')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{__('error.deleteTopic')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                    <button type="button" class="btn btn-danger" onclick="doDeleteTopic()">{{__('Delete')}}</button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('forumScript')

    <script>
        let topic = {!! $topic !!};

        DecoupledEditor.create( document.querySelector('#youAns'), {
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

        function sendAnswers(){
            let ans = window.editor.getData();
            if(ans.trim().length > 0){
                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("forum.storeTopicAns")}}',
                    data: {
                        _token: "{{csrf_token()}}",
                        text: ans,
                        topicId: topic.id
                    },
                    success: function(response){
                        if(response == 'ok'){
                            resultLoading('{{__('Your reply was sent successfully')}}', 'success');
                            location.reload();
                        }
                        else
                            resultLoading('{{__('There was a problem registering the answer. Please try again')}}', 'danger');
                    },
                    error: function (err) {
                        resultLoading('{{__('There was a problem registering the answer. Please try again')}}', 'danger');
                    }
                })
            }
        }

        function setBestAns(_id){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("forum.setBestAnswerTopic")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    ansId: _id
                },
                success: function(response){
                    if(response == 'ok')
                        location.reload();
                    else
                        resultLoading('{{__('Please try again')}}', 'danger');
                },
                error: function(err){
                    resultLoading('{{__('Please try again')}}', 'danger');
                }
            })
        }

        function likeTopicRely(_id, _kind, _like, _elems){
            $.ajax({
                type: 'post',
                url: '{{route("forum.likeForum")}}',
                data: {
                    _token : '{{csrf_token()}}',
                    id   : _id,
                    kind : _kind,
                    like : _like
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response.status == 'ok'){
                        $(_elems).parent().find('.chosenLike').removeClass('chosenLike');
                        $(_elems).addClass('chosenLike');
                        $(_elems).parent().find('.dislikeCount').text(response.dislike);
                        $(_elems).parent().find('.likeCount').text(response.like);
                    }
                }
            })
        }

        function deleteReplyModal(_id){
            $('#deletedId').val(_id);
            $('#deleteReplyModal').modal('show');
        }
        function doDeleteReply(){
            let id = $('#deletedId').val();

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("forum.deleteReply")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id
                },
                success: function(response){
                    if(response == 'ok')
                        location.reload();
                    else
                        resultLoading('{{__('Please try again')}}', 'danger');
                },
                error: function(err){
                    resultLoading('{{__('Please try again')}}', 'danger');
                }
            })
        }

        function deleteTopicModal(_id){
            $('#deletedId').val(_id);
            $('#deleteTopicModal').modal('show');
        }
        function doDeleteTopic(){
            let id = $('#deletedId').val();

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("forum.deleteTopic")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id
                },
                success: function(response){
                    if(response == 'ok')
                        location.reload();
                    else
                        resultLoading('{{__('Please try again')}}', 'danger');
                },
                error: function(err){
                    resultLoading('{{__('Please try again')}}', 'danger');
                }
            })
        }
    </script>
@endsection
