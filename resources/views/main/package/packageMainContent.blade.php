<style>
    .aboutPackageDiv{

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
    .activitiesDiv{

    }
    .activityHeaderRow{

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
        <div class="col-lg-6">
            <div class="activitiesDiv">
                <div class="aboutHeader">
                    Activities In Package:
                </div>
                <div class="activityRow">
                    <img src="{{$content->mainActivity->icon}}" alt="{{$content->mainActivity->name}}" style="width: 50px; height: 50px;">
                    {{$content->mainActivity->name}}
                </div>
                @foreach($content->activities as $item)
                    <div class="activityRow">
                        <img src="{{$item->icon}}" alt="{{$item->name}}" style="width: 50px; height: 50px;">
                        {{$item->name}}
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-6">
            <div id="map" class="map"></div>
        </div>
    </div>

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

