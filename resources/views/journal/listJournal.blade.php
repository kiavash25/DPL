@extends('journal.layout.layoutJournal')

@section('head')
    <link rel="stylesheet" href="{{asset('css/journal/listJournal.css')}}">
@endsection


@section('body')
    <article style="margin-top: 30px">
        <div class="row">
            <div id="mainContentDiv" class="col-lg-9" style="visibility: hidden;">
                <div id="row##number##" class="row listElemsDiv" style="visibility: hidden;">
                    <div class="listElemsPicDiv col-md-4">
                        <a href="##url##" class="imageHref">
                            <img src="##pic##" class="resizeImage listPics" style="width: 100%;">
                        </a>
                        <div class="listElemsSideData">
                            <a href="##categoryUrl##" class="listElemsSideDataCategory">##category##</a>
                            <div class="listElemsSideDataCategory">##date##</div>
                            <div class="listElemsSideDataCategory" >##username##</div>
                        </div>
                    </div>
                    <div class="listElemsContentDiv col-md-8">
                        <div class="listContentHeight">
                            <div>
                                <a href="##url##" class="listElemsContentName">
                                    ##name##
                                </a>
                            </div>
                            <div class="listElemsContentSummery">
                                ##summery##
                            </div>
                            <a href="##url##" class="listElemsContentButton">
                                READ MORE
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @include('journal.layout.sideContentJournal')
        </div>
    </article>

@endsection



@section('script')

    <script>
        let listValue = '{{$listValue}}';
        let listKind = '{{$listKind}}';

        let listElemSample = $('#mainContentDiv').html();
        let numOfElems = 1;
        let take = 5;
        let skip = 0;
        let tryToGet = 4;

        let firstTime = true;
        let onShow = false;
        let getData = false;
        let showElems = [];

        $('#mainContentDiv').html('');

        function checkListElemsHeight(){
            let checkHeight = $('.listContentHeight');
            for(let item of checkHeight){
                let hei = $(item).height() + 40;
                $(item).parent().prev().css('height', hei);
            }
            resizeImg('resizeImage');
        }

        function getDataAjax() {
            getData = true;
            $.ajax({
                type: 'post',
                url: "{{route('journal.getElems')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    take: take,
                    skip: skip,
                    kind: listKind,
                    value: listValue
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            createElemntsDivs(response['result']);
                    }
                    catch (e) {
                        console.log(e);
                    }
                },
                error: function(error){
                    tryToGet--;
                    if(tryToGet > 0)
                        getDataAjax();
                }
            })
        }
        getDataAjax();

        function createElemntsDivs(_value){

            for(let i = 0; i < _value.length; i++){
                let text = listElemSample;
                let fk = Object.keys(_value[i]);

                let t = '##number##';
                let re = new RegExp(t, "g");
                text = text.replace(re, numOfElems);

                for (let x of fk) {
                    let t = '##' + x + '##';
                    let re = new RegExp(t, "g");
                    let val = _value[i][x];

                    if(val != null && val != 'null')
                        text = text.replace(re, val);
                    else
                        text = text.replace(re, '');
                }

                $('#mainContentDiv').append(text);
                showElems.push('row' + numOfElems);
                numOfElems++;
            }

            skip += _value.length;

            checkListElemsHeight();
            $('.listPics').on('mouseenter', function(){
                $(this).css('transform', 'scale(1.2)');
            });
            $('.listPics').on('mouseleave', function(){
                $(this).css('transform', 'scale(1)');
            });

            if(_value.length == take)
                getData = false;

            if(firstTime) {
                firstTime = false;
                showElements($('#' + showElems[0]));
            }
        }

        function showElements(_element){
            onShow = true;
            $(_element).transition({
                animation  : 'fade up',
                duration   : '.5s',
                onComplete: function () {
                    showElems.shift();
                    checkListElemsHeight();

                    let listElemsDivToShow = $('#' + showElems[0]);
                    if(listElemsDivToShow.length > 0 && $(listElemsDivToShow).offset().top - $(window).height() + 100 < 0)
                        showElements(listElemsDivToShow);
                    else
                        onShow = false;
                }
            });
        }

        $('.mainBase').on('scroll', function(){
            if($('#mainContentDiv').offset().top + $('#mainContentDiv').height() - $(window).height() < 300 && !getData) {
                getData = true;
                getDataAjax();
            }

            if(showElems[0] && !onShow){
                let listElemsDivToShow = $('#' + showElems[0]);
                if(listElemsDivToShow.length > 0 && $(listElemsDivToShow).offset().top - $(window).height() + 100 < 0){
                    onShow = true;
                    showElements(listElemsDivToShow);
                }
            }

        });

        $(window).on('resize', function(){
            checkListElemsHeight();
        });
    </script>
@endsection

