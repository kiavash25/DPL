<style>
    .row{
        width: 100%;
        margin: 0;
    }
    .aboutHeader{
        font-size: 25px;
        font-weight: bold;
    }
    .aboutText{
        padding: 10px 25px;
        text-align: justify;
    }
    .mapAndActivityDiv{
        margin-top: 30px;
    }
    .activityRow{
        border-bottom: solid 1px lightgray;
        margin: 15px 25px;
        padding-bottom: 15px;
    }
    .map{
        width: 100%;
        height: 50vh;
    }
    .thumbnailSection{
        display: flex;
        flex-wrap: wrap;
        margin-top: 30px;
    }
    .thumbnailDiv{
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        cursor: pointer;
        transition: .2s;
        position: relative;
    }
    .thumbnailDiv:hover .thumbnailPic{
        transform: scale(1.1);
    }
    .thumbnailDiv:hover .matteBack{
        background: none;
    }
    .matteBack{
        position: absolute;
        top: 0px;
        right: 0px;
        width: 100%;
        height: 100%;
        background-color: #00000035;
        transition: .2s;
    }
    .thumbnailPic{
        width: 100%;
        transition: .2s;
    }
    .MoreInfoBase{
        width: 100%;
        display: flex;
        border: solid #1f75b9 5px;
        border-radius: 0;
        cursor: pointer;
        flex-direction: column;
    }
    .moreInfoHeader{
        width: 100%;
        padding: 15px;
        font-size: 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: .2s;
    }
    .moreInfoHeaderOpen{
        background: #1f75b9;
        color: white;
    }
    .moreInfoContentDiv{
        transition: .3s;
        height: 0;
        width: 100%;
        overflow: hidden;
    }
    .openMoreInfoContentDiv{
        height: auto;
    }
    .moreInfoContentHeaderDiv{
        width: 100%;
        display: flex;
        justify-content: space-around;
    }
    .moreInfoTitles{
        width: 100%;
        text-align: center;
        font-size: 18px;
        border: solid 2px #1f75b9;
        position: relative;
        border-right: 0;
    }
    .moreInfoText{
        display: none;
        padding: 30px;
    }
    .moreInfoTextOpen{
        display: block;
    }
    .moreInfoTitleTextNoneSelected{
        padding: 12px;
    }
    .moreInfoTitleTextSelected{
        display: flex;
        justify-content: center;
        align-items: center;
        background: white;
        color: #1f75b9;
        position: relative;
        height: 105%;
        width: 100%;
    }


    .image{
        display: table;
        clear: both;
        text-align: center;
        margin: 1em auto;
    }
    .image-style-align-right{
        float: right;
        margin-left: 15px;
        max-width: 50%;
    }
    .image-style-align-left{
        float: left;
        margin-right: 15px;
        max-width: 50%;
    }
    .image>img{
        display: block;
        margin: 0 auto;
        max-width: 100%;
        min-width: 50px;
    }
</style>


<div class="aboutPackageDiv">
    <div class="aboutHeader">
        About {{$content->name}}
    </div>
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
{{--            <div class="activitiesDiv">--}}
{{--                <div class="aboutHeader">--}}
{{--                    Activities In Package:--}}
{{--                </div>--}}
{{--                <div class="activityRow">--}}
{{--                    <img src="{{$content->mainActivity->icon}}" alt="{{$content->mainActivity->name}}" style="width: 50px; height: 50px;">--}}
{{--                    {{$content->mainActivity->name}}--}}
{{--                </div>--}}
{{--                @foreach($content->activities as $item)--}}
{{--                    <div class="activityRow">--}}
{{--                        <img src="{{$item->icon}}" alt="{{$item->name}}" style="width: 50px; height: 50px;">--}}
{{--                        {{$item->name}}--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}

            <div class="activitiesDiv" style="display: {{count($content->sideInfos) > 0 ? 'block' : 'none'}}">
                <div class="aboutHeader">
                    Infos:
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
        <div class="row" style="margin-top: 30px;">
            <div class="aboutHeader" style="width: 100%">
                More Info:
            </div>
            <div class="MoreInfoBase" style="border-bottom: 0; border-radius: 10px 10px 0px 0px">
                <div class="moreInfoHeader" onclick="openMoreInfoDiv(this)">
                    <div class="arrow down"></div>
                    Neutral Details
                </div>
                <div class="moreInfoContentDiv">
                    <div class="row">
                        <div class="moreInfoContentHeaderDiv">
                            @foreach($moreInfoNeutral as $key => $item)
                                @if(isset($item->text) && $item->text != null)
                                    <div class="moreInfoTitles" onclick="showMoreInfoText(this, {{$item->id}})">
                                        <div class="moreInfoTitleTextNoneSelected {{$key == 0 ? 'moreInfoTitleTextSelected firstMoreInfoTitle' : ''}}">
                                            {{$item->name}}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        @foreach($moreInfoNeutral as $key => $item)
                            @if(isset($item->text) && $item->text != null)
                                <div id="moreInfoText_{{$item->id}}" class="moreInfoText {{$key == 0 ? 'moreInfoTextOpen firstMoreInfoText' : ''}}">
                                    {!! $item->text !!}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="MoreInfoBase" style="border-radius: 0px 0px 10px 10px">
                <div class="moreInfoHeader" onclick="openMoreInfoDiv(this)">
                    <div class="arrow down"></div>
                    Callventure Details
                </div>

                <div class="moreInfoContentDiv">
                    <div class="row">
                        <div class="moreInfoContentHeaderDiv">
                            @foreach($moreInfoCallVenture as $key => $item)
                                @if(isset($item->text) && $item->text != null)
                                    <div class="moreInfoTitles" onclick="showMoreInfoText(this, {{$item->id}})">
                                        <div class="moreInfoTitleTextNoneSelected {{$key == 0 ? 'moreInfoTitleTextSelected firstMoreInfoTitle' : ''}}">
                                            {{$item->name}}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        @foreach($moreInfoCallVenture as $key => $item)
                            @if(isset($item->text) && $item->text != null)
                                <div id="moreInfoText_{{$item->id}}" class="moreInfoText {{$key == 0 ? 'moreInfoTextOpen firstMoreInfoText' : ''}}">
                                    {!! $item->text !!}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>

            <script !src="">
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
            </script>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 thumbnailSection">
            @foreach($content->thumbnails as $item)
                <div class="thumbnailDiv" onclick="openThumbnailPic({{$item->id}})">
                    <img src="{{$item->thumbnail}}" class="resizeImage thumbnailPic">
                    <div class="matteBack"></div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@include('main.common.packageList')

<script !src="">
    let thumbnails = {!! $content->thumbnails !!}

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

</script>

