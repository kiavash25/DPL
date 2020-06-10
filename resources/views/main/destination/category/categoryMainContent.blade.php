<link rel="stylesheet" href="{{asset('css/common/ownSlider.css')}}">
<link rel="stylesheet" href="{{asset('css/pages/categoryMainContent.css')}}">


<div class="row aboutPackageDiv">
    <?php
    if($content->video != null){
        $descNum = 6;
        $vidNum = 6;
    }
    else{
        $descNum = 12;
        $vidNum = 0;
    }
    ?>

    <div class="col-md-{{$descNum}}">
{{--        <div class="aboutHeader">--}}
{{--            {{__('About')}} {{$content->name}}--}}
{{--        </div>--}}
        <div class="aboutText">
            {!! $content->description !!}
        </div>
    </div>
    <div class="col-md-{{$vidNum}}">
        @if($content->video)
            <div class="aboutHeader" style="margin-top: 10px">
                {{__('Video')}}
            </div>
            <video poster="placeholder.png" controls style="width: 100%;">
                <source src="{{$content->video}}#t=1">
            </video>
        @endif
    </div>
</div>

<div class="row">
    <div id="stickyTitles">

        <?php
        $first = true;
        ?>
        @foreach($content->titles as $key => $item)
            @if($item->text != null)
                @if($first)
                    <a class="activeTitle" href="javascript:void(0)" onclick="showDescription(this, {{$item->id}})">{{$item->name}}</a>
                    <?php
                    $first = false;
                    ?>
                @else
                    <a href="javascript:void(0)" onclick="showDescription(this, {{$item->id}})">{{$item->name}}</a>
                @endif
            @endif
        @endforeach

    </div>
    <div id="titleDescriptions" class="content"></div>
</div>

<hr>
@if(isset($content->destinations) && count($content->destinations) > 0)
    <div class="row" style="position: relative; padding: 0px 20px">
        <h2>{{__('Destinations')}}</h2>
        <div id="mainSliderDiv" class="mainSliderDiv">
            <div id="sliderContentDiv" class="sliderContentDiv">
                @foreach($content->destinations as $item)
                    <div class="destinationPackages packages">
                        <div class="packageImgDiv">
                            <img src="{{$item->minPic}}" class="packageImg">
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
                                    {{__('See Destination')}}
                                </a>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="destinationSideButtonSlider packageSideButtonDiv" style="position: absolute; width: 100%; top: 50%">
            <div id="nextDestinationSlider" class="sliderButton nextSlider" style="top: 43%; right: 35px; box-shadow: 0 0 10px 3px black" onclick="nextDestinationSlider(-1)">
                <div class="slider arrow right"></div>
            </div>
            <div id="prevDestinationSlider" class="sliderButton prevSlider" style="top: 43%; left: -10px; box-shadow: 0 0 10px 3px black" onclick="nextDestinationSlider(1)">
                <div class="slider arrow left"></div>
            </div>
        </div>
    </div>
@endif

<div class="row" style="margin-bottom: 100px; margin-top: 40px; width: 100%;">
    <div class="col-md-12">
        <div id="map" class="map"></div>
    </div>
</div>

<script>
    let destinationSwiper = {!! $content->destinations !!};
    let destinationPackagesMarg = 0;
    let destinationPackagesMargLeft = 0;

    function resizeDestinationSlide(){
        let windowWidth = $('#mainSliderDiv').width();
        let count = destinationSwiper.length;
        let countInWin = Math.floor(windowWidth / 195);

        let marg = Math.floor((windowWidth % 195) / countInWin)/2;
        destinationPackagesMarg = marg;
        $('.destinationPackages').css('margin', '0px ' + marg + 'px');

        let width = (marg * 2 * count) + (195 * count);

        if(countInWin >= count)
            $('.destinationSideButtonSlider').hide();
        else
            $('.destinationSideButtonSlider').show();

        destinationPackagesMargLeft = 0;
        nextDestinationSlider(0);
    }
    resizeDestinationSlide();

    function nextDestinationSlider(_kind){
        let mainSliderDiv = $('#mainSliderDiv').width();
        let sliderContentDiv = $('#sliderContentDiv').width();

        let left = (195 + (2 * destinationPackagesMarg)) * _kind;
        destinationPackagesMargLeft += left;

        $('#nextDestinationSlider').show();
        $('#prevDestinationSlider').show();

        if(destinationPackagesMargLeft >= 0)
            $('#prevDestinationSlider').hide();
        else if(Math.abs(destinationPackagesMargLeft) + mainSliderDiv +(195 + (2 * destinationPackagesMarg)) >= sliderContentDiv )
            $('#nextDestinationSlider').hide();


        if((Math.abs(destinationPackagesMargLeft) + mainSliderDiv <= sliderContentDiv + 50) && destinationPackagesMargLeft <= 0)
            $('#sliderContentDiv').css('margin-left', destinationPackagesMargLeft);
        else
            destinationPackagesMargLeft -= left;
    }



    $(window).resize(resizeDestinationSlide);

    function createSwiperForDestination(){
        destinationSwiper = new Swiper('#destinationSwiper', {
            loop: true,
            slidesPerView: 6,
            navigation: {
                nextEl: '.nextSlider',
                prevEl: '.prevSlider',
            },
            breakpoints : {
                400:{
                    slidesPerView: 1,
                },
                600:{
                    slidesPerView: 2,
                },
                800:{
                    slidesPerView: 3,
                },
                1000:{
                    slidesPerView: 4,
                },
                1500:{
                    slidesPerView: 5,
                },
            }
        });
    }

    let destinations = {!! $content->destinations !!};
    let mapIcon = '{{$content->icon}}';
    let marker = [];
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: {{$content->mapCenter['lat']}}, lng: {{$content->mapCenter['lng']}}},
            zoom: 5
        });

        if(mapIcon){
            mapIcon = {
                url: mapIcon, // url
                scaledSize: new google.maps.Size(30, 30), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(15, 30) // anchor
            };
        };

        for(let i = 0; i < destinations.length; i++){
            let m = new google.maps.Marker({
                position: {
                    lat: parseFloat( destinations[i]['lat'] ),
                    lng: parseFloat( destinations[i]['lng'] )
                },
                title: destinations[i]['name'],
                icon: mapIcon,
                map: map,
            });
            m.addListener('click', function () {
                window.open(destinations[i]['url']);
            })
            marker.push(m);
        }
    }

</script>
