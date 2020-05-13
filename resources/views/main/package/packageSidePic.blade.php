<style>
    .sidePicContent{
        color: #2c3e50;
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
        display: flex;
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
        color: #1f75b9;
        border-radius: 5px;
        cursor: pointer;
        transition: .2s;
    }
    .orderButtonRevers{
        background: white ;
        color: #2b393a;
    }
    .orderButton:hover{
        color: white;
        background: #1f75b9;
    }

    .orderButtonRevers:hover{
        background: #2b393a;
        color: white;
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
    @if($content->day)
        <div class="days">
            {{$content->day}} {{__('Days')}}
        </div>
    @endif
    <h1 class="sidePicHeader">
        {{$content->name}}
    </h1>
{{--    <h4 class="sidePicCountry">--}}
{{--        {{$content->destination->country->name}} , {{$content->destination->city->name}}--}}
{{--    </h4>--}}
    <div class="sidePicTagContent">
        @foreach($content->tags as $item)
            <a href="{{route('show.list', ['kind' => 'tags', 'value1' => $item])}}" class="tagContent">
                {{$item}}
            </a>
        @endforeach
    </div>
    <div class="sideInfo">
        @if($content->sDate != null)
            <div class="infoDiv">
                <div class="infoName">{{__('Start Date')}}:</div>
                <div class="infoValue">{{$content->sDate}}</div>
            </div>
        @endif
        @if($content->eDate != null)
            <div class="infoDiv">
                <div class="infoName">{{__('End Date')}}:</div>
                <div class="infoValue">{{$content->eDate}}</div>
            </div>
        @endif
        <div class="infoDiv">
            <div class="infoName">{{__('Season')}}:</div>
            <div class="infoValue">{{__($content->season)}}</div>
        </div>
        <div class="infoDiv">
            <div class="infoName">{{__('Level')}}:</div>
            <div class="infoValue">{{__($content->level)}}</div>
        </div>
        <div class="infoDiv">
            <div class="infoName">{{__('Package Code')}}:</div>
            <div class="infoValue">{{$content->code}}</div>
        </div>
    </div>

    <div class="activityInfoDiv">
        <div class="activityInfoName">
            {{__('Activity')}}:
        </div>
        <div class="activityInfoValue">
{{--            <img src="{{$content->mainActivity->icon}}" alt="{{$content->mainActivity->name}}" style="width: 50px; height: 50px;">--}}
            {{$content->mainActivity->name}}
        </div>
    </div>

    <div class="moneyAndOrderDiv">
        <div class="moneyDiv">
            <div class="moneyName">
                {{__('Euro')}}
            </div>
            <div class="moneyValue">
                €{{$content->money}}
            </div>
        </div>
        <div class="orderDiv">
            <div class="orderButton" style="width: {{isset($content->brochure) && $content->brochure != null ? '50%' : '100%'}}">
                {{__('Book Now')}}
            </div>
            <a class="orderButton orderButtonRevers" href="{{$content->brochure}}"  style="display: {{isset($content->brochure) && $content->brochure != null ? 'flex' : 'none'}}; width: 50%;">
                {{__('Brochure')}}
            </a>

        </div>
    </div>
</div>
