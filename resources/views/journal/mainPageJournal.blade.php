@extends('journal.layout.layoutJournal')

@section('head')
    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">
    <link rel="stylesheet" href="{{asset('css/journal/mainPageJournal.css')}}">
    <script src="{{asset('js/swiper/swiper.js')}}"></script>
@endsection


@section('body')
    <div>
        <div class="mainSlider">
            <div class="swiper-container mainSS" style="height: 100%;">
                <div class="swiper-wrapper">
                    @for($i = 0; $i < 3; $i++)
                        <div class="swiper-slide" style="overflow: hidden; width: 100%; display: flex; justify-content: center; align-items: center;">
                            <img src="{{$journals[$i]->pic}}" class="resizeImage">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div id="mainSliderContentDiv" class="mainSliderContentDiv">
            <div id="mainSliderContent" class="mainSliderContent">
                <a id="mainSliderContentCategory" href="#" class="mainSliderContentCategory">
                    {{$journals[0]->category}}
                </a>
                <a id="mainSliderContentName" href="{{$journals[0]->url}}" class="mainSliderContentName">
                    {{$journals[0]->name}}
                </a>
                <div id="mainSliderContentSummery" class="mainSliderContentSummery">
                    {{$journals[0]->summery}}
                </div>
            </div>
            <div class="swiper-pagination sliderPagination"></div>
        </div>
    </div>

    <div id="listContent" class="container-fluid mainContent">

        <div id="row1" class="row marp0">
            <div class="col-lg-6 padl0 margb30">
                <div class="blogDiv height500">
                    <img src="{{asset('images/test/iran2.jpg')}}" class="resizeImage">
                    <div class="blogDivContentDiv">
                        <div class="mainSliderContent">
                            <div href="#" class="blogContentCategory">
                                STYLE
                            </div>
                            <div href="#" class="blogContentName">
                                Strategic Design for Brands
                            </div>
                            <div class="blogContentSummery">
                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 padr0">
                <div class="row marp0">
                    <div class="col-lg-6 margb30 padl0">
                        <div class="blogDiv height235">
                            <img src="{{asset('images/test/iran4.jpg')}}" class="resizeImage">
                            <div class="blogDivContentDiv">
                                <div class="mainSliderContent">
                                    <div href="#" class="blogContentCategory">
                                        STYLE
                                    </div>
                                    <div href="#" class="blogContentName">
                                        Strategic Design for Brands
                                    </div>
                                    <div class="blogContentSummery">
                                        Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 margb30 padr0">
                        <div class="blogDiv height235">
                            <img src="{{asset('images/test/iran4.jpg')}}" class="resizeImage">
                            <div class="blogDivContentDiv">
                                <div class="mainSliderContent">
                                    <div href="#" class="blogContentCategory">
                                        STYLE
                                    </div>
                                    <div href="#" class="blogContentName">
                                        Strategic Design for Brands
                                    </div>
                                    <div class="blogContentSummery">
                                        Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row marp0 margb30">
                    <div class="blogDiv height235">
                        <img src="{{asset('images/test/iran5.jpg')}}" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <div class="mainSliderContent">
                                <div href="#" class="blogContentCategory">
                                    STYLE
                                </div>
                                <div href="#" class="blogContentName">
                                    Strategic Design for Brands
                                </div>
                                <div class="blogContentSummery">
                                    Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="row2" class="row marp0">
            <div class="col-lg-4 padl0 margb30">
                <div class="blogDiv height500">
                    <img src="{{asset('images/test/iran6.jpg')}}" class="resizeImage">
                    <div class="blogDivContentDiv">
                        <div class="mainSliderContent">
                            <div href="#" class="blogContentCategory">
                                STYLE
                            </div>
                            <div href="#" class="blogContentName">
                                Strategic Design for Brands
                            </div>
                            <div class="blogContentSummery">
                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 padc0 margb30">
                <div class="blogDiv height500">
                    <img src="{{asset('images/test/iran5.jpg')}}" class="resizeImage">
                    <div class="blogDivContentDiv">
                        <div class="mainSliderContent">
                            <div href="#" class="blogContentCategory">
                                STYLE
                            </div>
                            <div href="#" class="blogContentName">
                                Strategic Design for Brands
                            </div>
                            <div class="blogContentSummery">
                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 padr0 margb30">
                <div class="blogDiv height500">
                    <img src="{{asset('images/test/iran4.jpg')}}" class="resizeImage">
                    <div class="blogDivContentDiv">
                        <div class="mainSliderContent">
                            <div href="#" class="blogContentCategory">
                                STYLE
                            </div>
                            <div href="#" class="blogContentName">
                                Strategic Design for Brands
                            </div>
                            <div class="blogContentSummery">
                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="row3" class="row marp0">
            <div class="col-lg-6 padl0 margb30">
                <div class="row marp0 margb30">
                    <div class="blogDiv height235">
                        <img src="{{asset('images/test/iran2.jpg')}}" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <div class="mainSliderContent">
                                <div href="#" class="blogContentCategory">
                                    STYLE
                                </div>
                                <div href="#" class="blogContentName">
                                    Strategic Design for Brands
                                </div>
                                <div class="blogContentSummery">
                                    Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row marp0">
                    <div class="blogDiv height235">
                        <img src="{{asset('images/test/iran5.jpg')}}" class="resizeImage">
                        <div class="blogDivContentDiv">
                            <div class="mainSliderContent">
                                <div href="#" class="blogContentCategory">
                                    STYLE
                                </div>
                                <div href="#" class="blogContentName">
                                    Strategic Design for Brands
                                </div>
                                <div class="blogContentSummery">
                                    Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 padr0 margb30">
                <div class="blogDiv height500">
                    <img src="{{asset('images/test/iran2.jpg')}}" class="resizeImage">
                    <div class="blogDivContentDiv">
                        <div class="mainSliderContent">
                            <div href="#" class="blogContentCategory">
                                STYLE
                            </div>
                            <div href="#" class="blogContentName">
                                Strategic Design for Brands
                            </div>
                            <div class="blogContentSummery">
                                Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')

    <script>

        let mainSliderPics = [];

        @for($i = 0; $i < 3; $i++)
            mainSliderPics.push({
                'category' : '{{$journals[$i]->category}}',
                'name' : '{{$journals[$i]->name}}',
                'summery' : '{{$journals[$i]->summery}}',
                'url' : '{{$journals[$i]->url}}'
            });
        @endfor

        var swiper = new Swiper('.mainSS', {
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

        $(document).ready(function(){
            resizeImg('resizeImage');
            $(window).resize(function(){
                resizeImg('resizeImage');
            });
        });

        var fadeUpElements = [];
        var isRunning = false;
        var isSend = true;
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

        var blogDiv = [];
        function showBlogDiv(_element){
            if($(_element).hasClass('blogDiv'))
                blogDiv.push(_element);
            else {
                var children = $(_element).children();
                if (children.length > 0) {
                    for (var i = 0; i < children.length; i++)
                        showBlogDiv($(children[i]));
                }
            }
        }

        for(var j = 1; j < 4; j++)
            showBlogDiv($('#row' + j));

        var scrollRunning = false;
        $('.mainBase').on('scroll', function(e){
            if(blogDiv[0] && !scrollRunning){
                var offsetTop = $(blogDiv[0]).offset().top -  $(window).height() + 200;
                if(offsetTop < 0){
                    scrollRunning = true;
                    fadeUpElements.push(blogDiv[0]);
                    blogDiv.shift();
                    scrollRunning = false;

                    if(!isRunning)
                        fadeUpElement(fadeUpElements[0]);
                }
            }
        });

        $('.blogDivContentDiv').on('mouseenter', function(){
            $(this).prev().css('transform', 'scale(1.2)');
            $(this).css('padding-bottom', '15px');
        });
        $('.blogDivContentDiv').on('mouseleave', function(){
            $(this).prev().css('transform', 'scale(1)');
            $(this).css('padding-bottom', '0px');
        });
    </script>
@endsection

