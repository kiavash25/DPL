<style>

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
    #stickyTitles {
        overflow: auto;
        white-space: nowrap;
        display: flex;
        background-color: #30759d;
        margin-top: 25px;
        width: 100%;
    }

    #stickyTitles a {
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

    #stickyTitles a:hover {
        background-color: #b2d0f7;
        color: white;
    }

    #stickyTitles a.activeTitle {
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
        top: 0;
        width: 100%;
        margin-top: 0px !important;
        left: 0;
        display: flex;
    }

    .sticky + .content {
        padding-top: 60px;
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
                    <a id="title_{{$item->id}}" class="activeTitle" href="javascript:void(0)" onclick="showDescription(this, {{$item->id}})">{{$item->name}}</a>
                    <?php
                    $first = false;
                    ?>
                @else
                    <a id="title_{{$item->id}}" href="javascript:void(0)" onclick="showDescription(this, {{$item->id}})">{{$item->name}}</a>
                @endif
            @endif
        @endforeach

    </div>
    <div id="titleDescriptions" class="content"></div>
</div>

<hr>
<div class="pcHide">
    @include('main.common.packageList')
</div>
<div class="row" style="margin-bottom: 100px; margin-top: 40px; width: 100%;">
    <div class="col-md-12">
        <div id="map" class="map"></div>
    </div>
</div>

<script>
    window.onscroll = function() {myFunction()};
    let navbar = $('#stickyTitles');
    let sticky = navbar.offset().top;
    let stickeNum = 0;
    let descCont;
    let descriptionTitles = {!! $content->titles !!};
    let nowUrl = location.href;
    let selectedTitle = nowUrl.split('#')[1];
    selectedTitle = decodeURI(selectedTitle);

    function myFunction() {
        if(stickeNum == 0)
            stickeNum = navbar.offset().top;

        if (window.pageYOffset >= stickeNum && (window.pageYOffset <= descCont)) {
            navbar.addClass("sticky")
        } else {
            navbar.removeClass("sticky");
        }
    }

    let titlesDesc = {!! $content->titles !!}
        let firstTimeScrollTodesc = true;

    function showDescription(_element, _id){
        $('.activeTitle').removeClass('activeTitle');
        $(_element).addClass('activeTitle');

        if(!firstTimeScrollTodesc) {
            $('html, body').animate({
                scrollTop: $('#titleDescriptions').offset().top - 100
            }, 800);
        }
        firstTimeScrollTodesc = false;

        for(let i = 0; i < titlesDesc.length; i++){
            if(titlesDesc[i]['id'] == _id){
                $('#titleDescriptions').html(titlesDesc[i]['text']);
                break;
            }
        }

        descCont = $('#titleDescriptions').offset().top + $('#titleDescriptions').height();
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

    $(window).ready(function(){
        for(let i = 0; i < descriptionTitles.length; i++){
            if(descriptionTitles[i]['name'] == selectedTitle){
                let id = descriptionTitles[i]['id'];
                showDescription($('#title_' + id), id);
                break;
            }
        }
    });


    let mapPackages = {!! $content->mapPackages !!};
    let marker = [];
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: {{$content->latCenter}}, lng: {{$content->lngCenter}}},
            zoom: 5
        });

        for(let i = 0; i < mapPackages.length; i++){
            let m = new google.maps.Marker({
                position: {
                    lat: parseFloat( mapPackages[i]['lat'] ),
                    lng: parseFloat( mapPackages[i]['lng'] )
                },
                title: mapPackages[i]['name'],
                map: map,
            });
            m.addListener('click', function () {
                window.open(mapPackages[i]['url']);
            });
            marker.push(m);
        }
    }
</script>
