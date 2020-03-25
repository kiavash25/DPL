<style>
    .sidePicContent{
        color: #2c3e50;
    }
    .sidePicHeader{

    }
    .sidePicCountry{

    }
    .sidePicTagContent{
        margin-bottom: 10px;
    }
    .tagContent{
        font-size: 14px;
        display: inline-block;
        line-height: 21px;
        background-color: #ebeef2;
        border-radius: 7px;
        padding: 4px 8px;
        margin-right: 8px;
        margin-bottom: 6px;
        color: #415466;
        transition: .2s;
        cursor: pointer;
    }
    .tagContent:hover{
        color: #286283;
        text-decoration: none;
        background-color: #b3d6e9;
    }

    .sidePicTextContent{
        max-height: 265px;
        overflow-y: auto;
        text-align: justify;
        padding-right: 25px;
    }
    .sideInfo{
        display: flex;
        flex-wrap: wrap;
    }
    .infoDiv{
        margin-right: 10%;
    }
    .infoName{
        font-size: 18px;
        font-weight: bold;
    }
    .infoValue{

    }
    .activityInfoDiv{
        margin-top: 25px;
    }
    .activityInfoName{
        font-size: 25px;
        font-weight: bold;
    }
    .activityInfoValue{
        padding: 0px 20px;
        font-size: 20px;
    }
    .days{
        color: #818d99;
        font-weight: bold;
    }
    .moneyAndOrderDiv{
        margin-top: 35px;
    }
    .moneyDiv{
        display: flex;
        align-items: baseline;
    }
    .orderDiv{

    }
    .orderButton{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 60px;
        margin: 8px;
        border: solid 2px;
        font-weight: bold;
        font-size: 21px;
        color: #409bd0;
        border-radius: 5px;
        cursor: pointer;
        transition: .2s;
    }
    .orderButton:hover{
        color: white;
        background: #409bd0;
    }
    .moneyValue{
        font-size: 35px;
        font-weight: bold;
        margin-left: 6px;
    }
    .moneyName{
        font-size: 18px;
        font-weight: bold;
    }
    @media (max-width: 991px){
        .sidePicTextContent{
            max-height: none;
        }
    }
</style>

<div class="sidePicContent">
    <div class="days">
        {{$content->day}} Days
    </div>
    <h1 class="sidePicHeader">
        {{$content->name}}
    </h1>
    <h4 class="sidePicCountry">
        {{$content->destination->country->name}} , {{$content->destination->city->name}}
    </h4>
    <div class="sidePicTagContent">
        @foreach($content->tags as $item)
            <div class="tagContent">
                {{$item}}
            </div>
        @endforeach
    </div>
    <div class="sideInfo">
        <div class="infoDiv">
            <div class="infoName">start Date:</div>
            <div class="infoValue">{{$content->sDate}}</div>
        </div>
        <div class="infoDiv">
            <div class="infoName">End Date:</div>
            <div class="infoValue">{{$content->eDate}}</div>
        </div>
        <div class="infoDiv">
            <div class="infoName">Season:</div>
            <div class="infoValue">{{$content->season}}</div>
        </div>
    </div>

    <div class="activityInfoDiv">
        <div class="activityInfoName">
            Activity:
        </div>
        <div class="activityInfoValue">
            <img src="{{$content->mainActivity->icon}}" alt="{{$content->mainActivity->name}}" style="width: 50px; height: 50px;">
            {{$content->mainActivity->name}}
        </div>
    </div>

    <div class="moneyAndOrderDiv">
        <div class="moneyDiv">
            <div class="moneyName">
                US
            </div>
            <div class="moneyValue">
                ${{$content->money}}
            </div>
        </div>
        <div class="orderDiv">
            <div class="orderButton">
                Book Now
            </div>
        </div>
    </div>
</div>
