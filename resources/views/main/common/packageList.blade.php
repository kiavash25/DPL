<style>

    .aboutHeader{
        font-size: 25px;
        font-weight: bold;
    }

    .mainContentPackages{
        display: flex;
        flex-wrap: wrap;
    }
    .packages{
        width: 250px;
        height: 250px;
        background: #d6d6d6;
        margin: 10px 0px;
        border-radius: 8px;
        cursor: pointer;
        position: relative;
        transition: .5s;
    }
    .packageImgDiv{
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        border-radius: 8px;
    }
    .packageImg{
        height: 100%;
    }
    .packageContentDiv{
        width: 100%;
        position: absolute;
        bottom: 0px;
        z-index: 1;
        background-color: #ebeef2;
        border-radius: 0px 0px 8px 8px;
        padding: 10px;
        transition: 0.5s;
        height: 20%;
    }
    .packageName{
        font-size: 20px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .packageDate{
        display: flex;
        width: 60px;
        height: 60px;
        position: absolute;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #1f75b9;
        border-radius: 50%;
        color: white;
        top: -15px;
        right: -15px;
        z-index: 1;
    }
    .packageActivity{
        color: white;
        position: absolute;
        background-color: #1f75b9;
        padding: 3px 10px;
        top: -20px;
        left: 0px;
        border-radius: 4px;
    }
    .packageDescription{
        overflow: hidden;
        height: 0%;
        font-size: 12px;
        text-align: justify;
        margin-top: 8px;
    }
    .packageButtonDiv{
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 0%;
        transition: .2s;
        overflow: hidden;
    }
    .packageButton{
        display: flex;
        width: 90%;
        text-align: center;
        justify-content: center;
        align-items: center;
        border: solid 1px #1f75b9;
        color: #1f75b9 !important;
        overflow: hidden;
        padding: 3px;
        transition: .3s;
    }
    .packages:hover .packageContentDiv{
        height: 100%;
    }
    .packages:hover .packageDescription{
        height: 64%;
    }
    .packages:hover .packageButtonDiv{
        height: 20%;
    }

    .packageButton:hover{
        background: #1f75b9;
        color: white !important;
    }
    .packageSwiper{
        width: 100%;
    }
</style>

@if(count($content->packages) > 0)
<div style="width: 100%; margin-top: 45px">
    <a href="{{$content->packageListUrl}}">
        <div class="aboutHeader">
            @if(Request::is('destination/*'))
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
                                        See Package
                                    </a>
                                </div>

                                <div class="packageActivity">
                                    {{$item->mainActivity->name}}
                                </div>
                            </div>

                            <div class="packageDate">
                                <div style="color: white">{{$item->sD}}</div>
                                <div style="color: white">{{$item->sM}}</div>
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
            $('.packages').css('margin', '10px 10px');
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
