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
</div>

@include('main.common.packageList')

