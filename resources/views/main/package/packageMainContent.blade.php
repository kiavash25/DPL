<link rel="stylesheet" href="{{asset('css/common/sliderPacks.css')}}">
<link rel="stylesheet" href="{{asset('css/common/ownSlider.css')}}">
<link rel="stylesheet" href="{{asset('css/pages/packageMainContent.css')}}">

<style>
    .stickyTitles {
        overflow: auto;
        white-space: nowrap;
        display: flex;
        background-color: #30759d;
        margin-top: 25px;
        width: 100%;
    }

    .stickyTitles a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        margin-right: auto;
        margin-left: auto;
    }

    .stickyTitles a:hover {
        background-color: #b2d0f7;
        color: white;
    }

    .stickyTitles a.activeTitle {
        background-color: #fcb316;
        color: white;
    }

    .content {
        padding: 16px;
        width: 100%;
        overflow: hidden;
    }

    .sticky {
        position: fixed;
        top: 0px;
        width: 100%;
        margin-top: 0px !important;
        left: 0;
        display: flex;
        z-index: 99;
    }

    .sticky + .content {
        padding-top: 60px;
    }
</style>

<div class="aboutPackageDiv">
    <div class="aboutText">
        {!! $content->description !!}
    </div>
</div>

<div class="mapAndActivityDiv" >
    <div class="row">
        <?php
            if(count($content->sideInfos) > 0)
                $mapNum = 6;
            else
                $mapNum = 12;
        ?>

        <div class="col-lg-{{12 - $mapNum}}">
            <div class="activitiesDiv" style="display: {{count($content->sideInfos) > 0 ? 'block' : 'none'}}">
                <div class="aboutHeader">
                    {{__('Infos')}}:
                </div>
                @foreach($content->sideInfos as $sideInfo)
                    <div class="activityRow" style="display: flex">
                        <img src="{{$sideInfo->icon}}" style="width: 50px; height: 50px;">
                        <div style="margin-left: 10px; width: calc(100% - 70px);">{{$sideInfo->text}}</div>
                    </div>
                @endforeach
            </div>

        </div>
        <div class="col-lg-{{$mapNum}}">
            <div id="map" class="map"></div>
        </div>
    </div>

    @if($hasMoreInfo > 0)
    <div class="row">
        <div class="aboutHeader" style="width: 100%; margin-top: 30px;">
            {{__('Neutral Details')}}
        </div>
        <div class="stickyTitles" style=" margin-top: 0px;">
            <?php
            $first = true;
            ?>
            @foreach($moreInfoNeutral as $key => $item)
                @if($item->text != null)
                    <a id="title_{{$item->id}}" class="natTabs {{$first ? 'activeTitle' : ''}}" href="javascript:void(0)" onclick="showDescription(this, {{$item->id}},'nat')">{{$item->name}}</a>
                    <?php
                        $first = false;
                    ?>
                @endif
            @endforeach
        </div>
        <div class="content titleDescriptions"></div>
    </div>

    <div class="row">
        <div class="aboutHeader" style="width: 100%; margin-top: 30px;">
            {{__('Culventure Details')}}
        </div>
        <div class="stickyTitles" style="margin-top: 0px;">
            <?php
            $first = true;
            ?>
            @foreach($moreInfoCallVenture as $key => $item)
                @if($item->text != null)
                    <a id="title_{{$item->id}}" class="callTabs {{$first ? 'activeTitle' : ''}}" href="javascript:void(0)" onclick="showDescription(this, {{$item->id}}, 'call')">{{$item->name}}</a>
                    <?php
                        $first = false;
                    ?>
                @endif
            @endforeach
        </div>
        <div class="content titleDescriptions"></div>
    </div>
    @endif

{{--    @include('deleted.packageMoreInfo')--}}

    @if(count($content->thumbnails) > 0 )
        <div class="row" style="margin-top: 35px;">
            <div class="aboutHeader">
                {{__('Virtual sense')}}
            </div>

            <div class="col-md-12 thumbnailSection">
                @foreach($content->thumbnails as $item)
                    <div class="thumbnailDiv" onclick="openThumbnailPic({{$item->id}})">
                        <img src="{{$item->thumbnail}}" class="resizeImage thumbnailPic" onload="resizeThisImg(this)">
                        <div class="matteBack"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@if(count($content->actPackage) > 0)
    <div style="width: 100%; margin-top: 45px">
        <div class="aboutHeader">
            {{__('Other Packages In this activity')}}
        </div>
        <div class="mainContentPackages">
            <div class="swiper-container packageSwiper" >

                <div class="swiper-wrapper" style="padding: 10px;">
                    @foreach($content->actPackage as $item)
                        <div class="swiper-slide swiperSlidePackage contentHtmlCenter">
                            <div class=" packages">
                                <div class="packageImgDiv">
                                    <img src="{{$item->pic}}" class="packageImg">
                                </div>
                                <div class="packageContentDiv">
                                    <div class="packageName">
                                        {{$item->name}}
                                    </div>
                                    <div class="packageDescription">
                                        {{$item->description}}
                                    </div>
                                    <div class="packageButtonDiv">
                                        <a href="{{$item->url}}" class="packageButton">
                                            {{__('See Package')}}
                                        </a>
                                    </div>

                                    <div class="packageActivity">
                                        {{$item->mainActivity->name}}
                                    </div>
                                </div>

                                <div class="packageDate">
                                    @if($item->sD == 'Call')
                                        <div style="color: white; text-align: center; font-size: 13px;">{{__('Call Us')}}</div>
                                    @else
                                        <div style="color: white">{{$item->sD}}</div>
                                        <div style="color: white">{{$item->sM}}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="nextPackage" class="sliderButton nextSlider" style="top: 45%; right: 10px; box-shadow: 0 0 10px 3px black">
                    <div class="slider arrow right"></div>
                </div>
                <div id="prevPackage" class="sliderButton prevSlider" style="top: 45%; left: 10px; box-shadow: 0 0 10px 3px black">
                    <div class="slider arrow left"></div>
                </div>
            </div>
        </div>
    </div>
@endif

{{--@include('main.common.packageList')--}}

<script>
    let packageSwiper = new Swiper('.packageSwiper', {
        slidesPerGroup: 1,
        spaceBetween: 5,
        slidesPerView: 5,
        watchOverflow: true,
        navigation: {
            nextEl: '.prevSlider',
            prevEl: '.nextSlider',
        },
        breakpoints: {
            600: {
                slidesPerView: 1,
            },
            850: {
                slidesPerView: 2,
            },
            1100: {
                slidesPerView: 3,
            },
            1400: {
                slidesPerView: 4,
            }
        },
        on: {
            init: function () {
                let slideCount = this.slides.length;
                if(slideCount <= this.params.slidesPerView){
                    $(this.el).find(this.params.navigation.nextEl).css('display', 'none');
                    $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                }
                else{
                    $(this.el).find(this.params.navigation.nextEl).css('display', 'flex');
                    $(this.el).find(this.params.navigation.prevEl).css('display', 'flex');
                }

                $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
            },
            resize: function(){
                let slideCount = this.slides.length;
                if(slideCount <= this.params.slidesPerView){
                    $(this.el).find(this.params.navigation.nextEl).css('display', 'none');
                    $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                }
                else{
                    $(this.el).find(this.params.navigation.nextEl).css('display', 'flex');
                    $(this.el).find(this.params.navigation.prevEl).css('display', 'flex');
                }
            },
            slideChange: function(){
                if(this.isBeginning)
                    $(this.el).find(this.params.navigation.prevEl).css('display', 'none');
                else
                    $(this.el).find(this.params.navigation.prevEl).css('display', 'flex');

                if(this.isEnd)
                    $(this.el).find(this.params.navigation.nextEl).css('display', 'none');
                else
                    $(this.el).find(this.params.navigation.nextEl).css('display', 'flex');
            }
        },
    });

    let thumbnails = {!! $content->thumbnails !!};

    function openThumbnailPic(_id){
        let main = [];
        let main0 = [];
        let thumb = [];
        let thumb0 = [];
        let allow = false;

        for(let i = 0; i < thumbnails.length; i++){
            if(allow){
                main.push('<div class="swiper-slide albumePic" ><img src="' + thumbnails[i]["pic"] + '" style="max-height: 100%; max-width: 100%;"></div>');
                thumb.push('<div class="swiper-slide albumePic" style="background-image:url(' + thumbnails[i]["thumbnail"] + '); cursor:pointer;"></div>');
            }
            else {
                if (thumbnails[i]['id'] == _id){
                    main.push('<div class="swiper-slide albumePic" ><img src="' + thumbnails[i]["pic"] + '"  style="max-height: 100%; max-width: 100%;"></div>');
                    thumb.push('<div class="swiper-slide albumePic" style="background-image:url(' + thumbnails[i]["thumbnail"] + '); cursor:pointer;"></div>');
                    allow = true;
                }
                else{
                    main0.push('<div class="swiper-slide albumePic" ><img src="' + thumbnails[i]["pic"] + '"  style="max-height: 100%; max-width: 100%;"></div>');
                    thumb0.push('<div class="swiper-slide albumePic" style="background-image:url(' + thumbnails[i]["thumbnail"] + '); cursor:pointer;"></div>');
                }
            }
        }

        main = main.concat(main0);
        thumb = thumb.concat(thumb0);

        createAlbum(main, thumb);
    }

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: {{$content->lat}}, lng: {{$content->lng}}},
            zoom: 10
        });
        marker = new google.maps.Marker({
            position: {
                lat: parseFloat( {{$content->lat}} ),
                lng: parseFloat( {{$content->lng}} )
            },
            map: map,
        })
    }

    function openMoreInfoDiv(_element){
        $('.moreInfoTitleTextSelected').removeClass('moreInfoTitleTextSelected');
        $('.moreInfoTextOpen').removeClass('moreInfoTextOpen');
        $('.firstMoreInfoTitle').addClass('moreInfoTitleTextSelected');
        $('.firstMoreInfoText').addClass('moreInfoTextOpen');

        if($(_element).hasClass('moreInfoHeaderOpen')){
            $('.openMoreInfoContentDiv').removeClass('openMoreInfoContentDiv');
            $('.moreInfoHeaderOpen').removeClass('moreInfoHeaderOpen');
        }
        else {
            $('.openMoreInfoContentDiv').removeClass('openMoreInfoContentDiv');
            $('.moreInfoHeaderOpen').removeClass('moreInfoHeaderOpen');
            $(_element).addClass('moreInfoHeaderOpen');
            $(_element).next().addClass('openMoreInfoContentDiv');
        }
    }

    function showMoreInfoText(_element, _id){
        $('.moreInfoTitleTextSelected').removeClass('moreInfoTitleTextSelected');
        $($(_element).children()[0]).addClass('moreInfoTitleTextSelected');

        $('.moreInfoTextOpen').removeClass('moreInfoTextOpen');
        $('#moreInfoText_' + _id).addClass('moreInfoTextOpen');
    }

    window.onscroll = function() {myFunction()};
    let navbar = $('.stickyTitles');
    let stickeNum = [];
    let descCont;
    let moreInfoNeutral = {!! $moreInfoNeutral !!};
    let moreInfoCallVenture = {!! $moreInfoCallVenture !!};

    function myFunction() {
        for(let i = 0; i < navbar.length; i++){
            let titleDescriptions = $(navbar[i]).parent().find('.titleDescriptions');
            if (window.pageYOffset >= titleDescriptions.offset().top && (window.pageYOffset <= stickeNum[i])) {
                $(navbar[i]).addClass("sticky");
                descCont = titleDescriptions.offset().top + titleDescriptions.height();
                stickeNum[i] = descCont;
            }
            else
                $(navbar[i]).removeClass("sticky");
        }
    }

    function showDescription(_element, _id, _kind){
        if(_kind == 'call') {
            $('.callTabs').removeClass('activeTitle');
            for (let i = 0; i < moreInfoCallVenture.length; i++) {
                if (moreInfoCallVenture[i].id == _id) {
                    $(_element).parent().parent().find('.titleDescriptions').html(moreInfoCallVenture[i].text);
                    break;
                }
            }
        }
        else if(_kind == 'nat'){
            $('.natTabs').removeClass('activeTitle');
            for (let i = 0; i < moreInfoNeutral.length; i++) {
                if (moreInfoNeutral[i].id == _id) {
                    $(_element).parent().parent().find('.titleDescriptions').html(moreInfoNeutral[i].text);
                    break;
                }
            }
        }

        $(_element).addClass('activeTitle');

        for(let i = 0; i < navbar.length; i++){
            let titleDescriptions = $(navbar[i]).parent().find('.titleDescriptions');
            descBottom = titleDescriptions.offset().top + titleDescriptions.height();
            stickeNum[i] = descBottom;
        }
    }

    $('.activeTitle').click();

    $(window).resize(function(){
        if($('#stickyTitles').hasClass('sticky')){
            $('#stickyTitles').removeClass('sticky');
            stickeNum = navbar.offset().top;
            $('#stickyTitles').addClass('sticky');
        }
        else
            stickeNum = navbar.offset().top;
    });
</script>



