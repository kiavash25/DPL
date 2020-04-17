@extends('journal.layout.layoutJournal')

@section('head')
    <link rel="stylesheet" href="{{asset('css/journal/mainPageJournal.css')}}">
    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">
    <script src="{{asset('js/swiper/swiper.js')}}"></script>
@endsection


@section('body')
    <div>
        <div class="mainSlider">
            <div class="swiper-container mainSS" style="height: 100%;">
                <div class="swiper-wrapper">
                    @foreach($mainSliderJournal as $item)
                        <div class="swiper-slide" style="overflow: hidden; width: 100%; display: flex; justify-content: center; align-items: center;">
                            <img src="{{$item->pic}}" class="resizeImage">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="mainSliderContentDiv" class="mainSliderContentDiv">
            <div id="mainSliderContent" class="mainSliderContent">
                <a id="mainSliderContentCategory" href="#" class="mainSliderContentCategory">
                    {{$mainSliderJournal[0]->category}}
                </a>
                <a id="mainSliderContentName" href="{{$mainSliderJournal[0]->url}}" class="mainSliderContentName">
                    {{$mainSliderJournal[0]->name}}
                </a>
                <div id="mainSliderContentSummery" class="mainSliderContentSummery">
                    {{$mainSliderJournal[0]->summery}}
                </div>
            </div>
            <div class="swiper-pagination sliderPagination"></div>
        </div>
    </div>

    <div id="listContent" class="container-fluid mainContent">

{{--        <div id="row1" class="row marp0">--}}
{{--            <div class="col-lg-6 padl0 margb30">--}}
{{--                <div class="blogDiv height500">--}}
{{--                    <img src="{{asset('images/test/iran2.jpg')}}" class="resizeImage">--}}
{{--                    <div class="blogDivContentDiv">--}}
{{--                        <div class="mainSliderContent">--}}
{{--                            <div href="#" class="blogContentCategory">--}}
{{--                                STYLE--}}
{{--                            </div>--}}
{{--                            <div href="#" class="blogContentName">--}}
{{--                                Strategic Design for Brands--}}
{{--                            </div>--}}
{{--                            <div class="blogContentSummery">--}}
{{--                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="col-lg-6 padr0">--}}
{{--                <div class="row marp0">--}}
{{--                    <div class="col-lg-6 margb30 padl0">--}}
{{--                        <div class="blogDiv height235">--}}
{{--                            <img src="{{asset('images/test/iran4.jpg')}}" class="resizeImage">--}}
{{--                            <div class="blogDivContentDiv">--}}
{{--                                <div class="mainSliderContent">--}}
{{--                                    <div href="#" class="blogContentCategory">--}}
{{--                                        STYLE--}}
{{--                                    </div>--}}
{{--                                    <div href="#" class="blogContentName">--}}
{{--                                        Strategic Design for Brands--}}
{{--                                    </div>--}}
{{--                                    <div class="blogContentSummery">--}}
{{--                                        Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-6 margb30 padr0">--}}
{{--                        <div class="blogDiv height235">--}}
{{--                            <img src="{{asset('images/test/iran4.jpg')}}" class="resizeImage">--}}
{{--                            <div class="blogDivContentDiv">--}}
{{--                                <div class="mainSliderContent">--}}
{{--                                    <div href="#" class="blogContentCategory">--}}
{{--                                        STYLE--}}
{{--                                    </div>--}}
{{--                                    <div href="#" class="blogContentName">--}}
{{--                                        Strategic Design for Brands--}}
{{--                                    </div>--}}
{{--                                    <div class="blogContentSummery">--}}
{{--                                        Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="row marp0 margb30">--}}
{{--                    <div class="blogDiv height235">--}}
{{--                        <img src="{{asset('images/test/iran5.jpg')}}" class="resizeImage">--}}
{{--                        <div class="blogDivContentDiv">--}}
{{--                            <div class="mainSliderContent">--}}
{{--                                <div href="#" class="blogContentCategory">--}}
{{--                                    STYLE--}}
{{--                                </div>--}}
{{--                                <div href="#" class="blogContentName">--}}
{{--                                    Strategic Design for Brands--}}
{{--                                </div>--}}
{{--                                <div class="blogContentSummery">--}}
{{--                                    Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div id="sample31" style="display: none;">
            <div id="row##number##" class="row marp0">
                <div class="col-lg-6 padl0 margb30">
                    <div class="row marp0 margb30">
                        <div class="blogDiv height235">
                            <img src="##image1##" class="resizeImage">
                            <div class="blogDivContentDiv">
                                <a href="##url1##" class="mainSliderContent">
                                    <div  class="blogContentCategory">##category1##</div>
                                    <div class="blogContentName">##name1##</div>
                                    <div class="blogContentSummery">##summery1##</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row marp0">
                        <div class="blogDiv height235">
                            <img src="##image2##" class="resizeImage">
                            <div class="blogDivContentDiv">
                                <a href="##url2##" class="mainSliderContent">
                                    <div class="blogContentCategory">##category2##</div>
                                    <div class="blogContentName">##name2##</div>
                                    <div class="blogContentSummery">##summery2##</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 padr0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image3##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url3##" class="mainSliderContent">
                                <div class="blogContentCategory">##category3##</div>
                                <div href="#" class="blogContentName">##name3##</div>
                                <div class="blogContentSummery">##summery3##</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sample32" style="display: none;">
            <div id="row##number##" class="row marp0">
                <div class="col-lg-4 padl0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image1##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url1##" class="mainSliderContent">
                                <div class="blogContentCategory">
                                    ##category1##
                                </div>
                                <div class="blogContentName">
                                    ##name1##
                                </div>
                                <div class="blogContentSummery">
                                    ##summery1##
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 padc0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image2##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url2##" class="mainSliderContent">
                                <div class="blogContentCategory">
                                    ##category2##
                                </div>
                                <div href="#" class="blogContentName">
                                    ##name2##
                                </div>
                                <div class="blogContentSummery">
                                    ##summery2##
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 padr0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image3##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url3##" class="mainSliderContent">
                                <div class="blogContentCategory">
                                    ##category3##
                                </div>
                                <div href="#" class="blogContentName">
                                    ##name3##
                                </div>
                                <div class="blogContentSummery">
                                    ##summery3##
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sample33" style="display: none;">

            <div id="row##number##" class="row marp0">
                <div class="col-lg-6 padl0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image1##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url1##" class="mainSliderContent">
                                <div class="blogContentCategory">##category1##</div>
                                <div class="blogContentName">##name1##</div>
                                <div class="blogContentSummery">##summery1##</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 padr0 margb30">
                    <div class="row marp0 margb30">
                        <div class="blogDiv height235">
                            <img src="##image2##" class="resizeImage">
                            <div class="blogDivContentDiv">
                                <a href="##url2##" class="mainSliderContent">
                                    <div class="blogContentCategory">##category2##</div>
                                    <div class="blogContentName">##name2##</div>
                                    <div class="blogContentSummery">##summery2##</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row marp0">
                        <div class="blogDiv height235">
                            <img src="##image3##" class="resizeImage">
                            <div class="blogDivContentDiv">
                                <a href="##url3##" class="mainSliderContent">
                                    <div class="blogContentCategory">##category3##</div>
                                    <div href="#" class="blogContentName">##name3##</div>
                                    <div class="blogContentSummery">##summery3##</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="sample21" style="display: none;">
            <div id="row##number##" class="row marp0">
                <div class="col-lg-6 padl0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image1##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url1##" class="mainSliderContent">
                                <div class="blogContentCategory">
                                    ##category1##
                                </div>
                                <div class="blogContentName">
                                    ##name1##
                                </div>
                                <div class="blogContentSummery">
                                    ##summery1##
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 padc0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image2##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url2##" class="mainSliderContent">
                                <div class="blogContentCategory">
                                    ##category2##
                                </div>
                                <div href="#" class="blogContentName">
                                    ##name2##
                                </div>
                                <div class="blogContentSummery">
                                    ##summery2##
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="sample11" style="display: none;">
            <div id="row##number##" class="row marp0">
                <div class="col-lg-12 padl0 margb30">
                    <div class="blogDiv height500">
                        <img src="##image1##" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <a href="##url1##" class="mainSliderContent">
                                <div class="blogContentCategory">
                                    ##category1##
                                </div>
                                <div class="blogContentName">
                                    ##name1##
                                </div>
                                <div class="blogContentSummery">
                                    ##summery1##
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection



@section('script')

    <script>
        let skip = 0;
        let numOfRows = 0;
        let loadedRows = 0;
        let sample3 = [];
        let sample2;
        let sample1;
        let blogDiv = [];
        let mainSliderPics = [];
        let fadeUpElements = [];
        let isSend = true;
        let getNewRows = true;
        let isRunning = false;
        let scrollRunning = false;

        let firstJournal = {!! $journals !!};

        for(let i = 1; i < 4; i++){
            sample3[i-1] = $('#sample3' + i).html();
            $('#sample3' + i).remove();
        }

        sample2 = $('#sample21').html();
        $('#sample21').remove();

        sample1 = $('#sample11').html();
        $('#sample11').remove();

        @foreach($mainSliderJournal as $item)
            mainSliderPics.push({
                'category' : '{{$item->category}}',
                'name' : '{{$item->name}}',
                'summery' : '{{$item->summery}}',
                'url' : '{{$item->url}}'
            });
        @endforeach

        let swiper = new Swiper('.mainSS', {
            loop: true,
            effect: 'fade',
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
        swiper.on('slideChange', function(){
            $('#mainSliderContent').css('display', 'none');
            $('#mainSliderContentCategory').text(mainSliderPics[swiper.realIndex]['category']);
            $('#mainSliderContentName').text(mainSliderPics[swiper.realIndex]['name']);
            $('#mainSliderContentName').attr('href', mainSliderPics[swiper.realIndex]['url']);
            $('#mainSliderContentSummery').text(mainSliderPics[swiper.realIndex]['summery']);

            $('#mainSliderContent').fadeToggle(1000);
        });

        function fadeUpElement(_element){
            isRunning = true;
            $(_element).transition({
                animation  : 'fade up',
                duration   : '0.6s',
                onComplete : function() {
                    fadeUpElements.shift();
                    if(!isSend && fadeUpElements.length > 0)
                        fadeUpElement(fadeUpElements[0]);
                    else if(fadeUpElements.length < 1)
                        isRunning = false;

                    resizeImg('resizeImage');
                }
            });
            setTimeout(function(){
                if(fadeUpElements[1]) {
                    isSend = true;
                    fadeUpElement(fadeUpElements[1]);
                }
                else
                    isSend = false;
            },320);
        }

        function showBlogDiv(_element){
            if($(_element).hasClass('blogDiv'))
                blogDiv.push(_element);
            else {
                let children = $(_element).children();
                if (children.length > 0) {
                    for (let i = 0; i < children.length; i++)
                        showBlogDiv($(children[i]));
                }
            }
        }

        $('.mainBase').on('scroll', function(e){
            if(blogDiv[0] && !scrollRunning){
                let offsetTop = $(blogDiv[0]).offset().top -  $(window).height() + 200;
                if(offsetTop < 0){
                    scrollRunning = true;
                    fadeUpElements.push(blogDiv[0]);
                    blogDiv.shift();
                    scrollRunning = false;

                    if(!isRunning)
                        fadeUpElement(fadeUpElements[0]);
                }

                if(!getNewRows && $('#row' + loadedRows).offset().top -  $(window).height() < 0){
                    getNewRows = true;
                    loadNewDate();
                }
            }
        });

        function loadNewDate(){
            $.ajax({
                type: 'post',
                url: '{{route("journal.getElems")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    skip: skip,
                    take: 3
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response['status'] == 'ok'){
                        createRows(response['result']);
                    }
                }
            })
        }

        function createRows(_values){
            if(_values.length == 3){
                let text = sample3[numOfRows%3];

                re = new RegExp('##number##', "g");
                text = text.replace(re, numOfRows);

                for(let i = 0; i < _values.length; i++){
                    re = new RegExp('##image' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['pic']);

                    re = new RegExp('##url' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['url']);

                    re = new RegExp('##category' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['category']);

                    re = new RegExp('##name' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['name']);

                    re = new RegExp('##summery' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['summery']);
                }

                $('#listContent').append(text);
                showBlogDiv($('#row' + numOfRows));
                getNewRows = false;
            }
            else if(_values.length == 2){
                let text = sample2;

                re = new RegExp('##number##', "g");
                text = text.replace(re, numOfRows);

                for(let i = 0; i < _values.length; i++){
                    re = new RegExp('##image' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['pic']);

                    re = new RegExp('##url' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['url']);

                    re = new RegExp('##category' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['category']);

                    re = new RegExp('##name' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['name']);

                    re = new RegExp('##summery' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['summery']);
                }

                $('#listContent').append(text);
                showBlogDiv($('#row' + numOfRows));
                getNewRows = true;
            }
            else if(_values.length == 1){
                let text = sample1;
                re = new RegExp('##number##', "g");
                text = text.replace(re, numOfRows);

                for(let i = 0; i < _values.length; i++){
                    re = new RegExp('##image' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['pic']);

                    re = new RegExp('##url' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['url']);

                    re = new RegExp('##category' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['category']);

                    re = new RegExp('##name' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['name']);

                    re = new RegExp('##summery' + (i+1) + '##', "g");
                    text = text.replace(re, _values[i]['summery']);
                }

                $('#listContent').append(text);
                showBlogDiv($('#row' + numOfRows));
                getNewRows = true;
            }
            else
                getNewRows = true;

            numOfRows++;
            skip += _values.length;

            $('.blogDivContentDiv').on('mouseenter', function(){
                $(this).prev().css('transform', 'scale(1.2)');
                $(this).css('padding-bottom', '15px');
            });
            $('.blogDivContentDiv').on('mouseleave', function(){
                $(this).prev().css('transform', 'scale(1)');
                $(this).css('padding-bottom', '0px');
            });
        }


        createRows(firstJournal);

    </script>
@endsection

