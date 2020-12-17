<link rel="stylesheet" href="{{asset('css/common/sliderPacks.css')}}">

@if(count($content->packages) > 0)
<div style="width: 100%; margin-top: 45px">
    <a href="{{$content->packageListUrl}}">
        <div class="aboutHeader">
            @if(Request::is('destination/*') || Request::is('activity/*'))
                Packages
            @else
                Other Packages In {{$content->destination->name}}
            @endif
        </div>
    </a>
    <div class="mainContentPackages">
        <div class="swiper-container packageSwiper" >

            <div class="swiper-wrapper" style="padding: 10px 0px">
                @foreach($content->packages as $item)
                    <div class="swiper-slide swiperSlidePackage contentHtmlCenter">
                        <div class=" packages">
                            <div class="packageImgDiv">
                                <img src="{{$item->pic}}" alt="{{$item->name}}" class="packageImg">
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

            <div id="nextPackage" class="sliderButton nextSlider" style="top: 33%; right: 10px; box-shadow: 0 0 10px 3px black">
                <div class="slider arrow right"></div>
            </div>
            <div id="prevPackage" class="sliderButton prevSlider" style="top: 33%; left: 10px; box-shadow: 0 0 10px 3px black">
                <div class="slider arrow left"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var packageCount = {{count($content->packages)}};

    var packageSwiper = 0;

    function resizePackageSwiper(){
        var windowW = $(window).width();
        if((windowW > 1400 && packageCount <= 5) || (windowW > 1100 && packageCount <= 4) ||
            (windowW > 840 && packageCount <= 3) || (windowW > 585 && packageCount <= 2) ||
            (windowW <= 585 && packageCount <= 1)){
            $('#nextPackage').hide();
            $('#prevPackage').hide();
            $('.swiperSlidePackage').css('width', 'auto');
            $('.swiperSlidePackage').css('height', 'auto');
            $('.swiperSlidePackage').parent().css('overflow-x', 'auto');
            $('.swiperSlidePackage').parent().css('height', '275px');
            $('.packages').css('margin', '10px 20px');
            if(packageSwiper != 0)
                packageSwiper.destroy(true, true);
            packageSwiper = 0;
        }
        else{
            packageSwiper = new Swiper('.packageSwiper', {
                loop: true,
                navigation: {
                    nextEl: '#nextPackage',
                    prevEl: '#prevPackage',
                },
                breakpoints:{
                    585:{
                        slidesPerView: 1,
                    },
                    840:{
                        slidesPerView: 2,
                    },
                    1100:{
                        slidesPerView: 3,
                    },
                    1400:{
                        slidesPerView: 4,
                    },
                    14000:{
                        slidesPerView: 5,
                    }
                }
            });
        }
    }

    resizePackageSwiper();
</script>
@endif

