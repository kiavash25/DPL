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
        margin-top: 10px;
    }
    .infoDiv{
        margin-right: 5px;
        margin-bottom: 4px;
        padding: 10px;
        border: solid 1px gray;
        border-radius: 10px;
    }
    .infoName{
        font-size: 13px;
        font-weight: bold;
        text-align: center;
    }
    .infoValue{
        text-align: center;
        font-size: 11px;
        color: gray;
    }
    .activityInfoDiv{
        margin-top: 25px;
    }
    .activityInfoName{
        font-size: 25px;
        font-weight: bold;
        text-align: center;
    }
    .activityInfoValue{
        padding: 0px 20px;
        font-size: 20px;
        text-align: center;
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
    .disableBookingErr{

    }
    .disableBookingErr .err{
        color: red;
        font-size: 10px;
        text-align: center;
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
    .orderButton.disable{
        pointer-events: none;
        cursor: default;
        text-decoration: none;
        opacity: .3;
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
        display: inline-block;
    }
    .moneyName{
        font-size: 18px;
        display: inline-block;
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
                <div class="infoName">{{__('Start Date')}}</div>
                <div class="infoValue">{{$content->sDate}}</div>
            </div>
        @endif
        @if($content->eDate != null)
            <div class="infoDiv">
                <div class="infoName">{{__('End Date')}}</div>
                <div class="infoValue">{{$content->eDate}}</div>
            </div>
        @endif
        <div class="infoDiv">
            <div class="infoName">{{__('Season')}}</div>
            <div class="infoValue">{{__($content->season)}}</div>
        </div>
        <div class="infoDiv">
            <div class="infoName">{{__('Level')}}</div>
            <div class="infoValue">{{__($content->level)}}</div>
        </div>
        <div class="infoDiv">
            <div class="infoName">{{__('Package Code')}}</div>
            <div class="infoValue">{{$content->code}}</div>
        </div>
    </div>
    <div class="sideInfo">
        @if($content->registerSDate != null)
            <div class="infoDiv">
                <div class="infoName">{{__('Start register date')}}</div>
                <div class="infoValue">{{$content->registerSDate}}</div>
            </div>
        @endif
        @if($content->registerEDate != null)
            <div class="infoDiv">
                <div class="infoName">{{__('End register date')}}</div>
                <div class="infoValue">{{$content->registerEDate}}</div>
            </div>
        @endif
        @if($content->capacity != 0 && $content->capacity != null)
            <div class="infoDiv">
                <div class="infoName">{{__('Capacity')}}</div>
                <div class="infoValue">{{$content->capacity}}</div>
            </div>
        @endif
    </div>

    <div class="activityInfoDiv" style="display: flex; align-items: center">
        <div>
            <div class="infoDiv">
                <div class="infoName">{{__('Activity')}}</div>
                <div class="infoValue">{{$content->mainActivity->name}}</div>
            </div>
{{--            <div class="activityInfoName">--}}
{{--                {{__('Activity')}}--}}
{{--            </div>--}}
{{--            <div class="activityInfoValue" style="padding: 0px">--}}
{{--                {{$content->mainActivity->name}}--}}
{{--            </div>--}}
        </div>
        @if($content->specialName != null)
            <div class="infoDiv">
                <div class="infoName">{{__('Special name')}}</div>
                <div class="infoValue">{{$content->specialName}}</div>
            </div>
{{--            <div style="margin: 0px 50px">--}}
{{--                <div class="activityInfoName">--}}
{{--                    {{__('Special name')}}--}}
{{--                </div>--}}
{{--                <div class="activityInfoValue" style="padding: 0px">--}}
{{--                    {{$content->specialName}}--}}
{{--                </div>--}}
{{--            </div>--}}
        @endif
    </div>

    <div class="moneyAndOrderDiv">
        <div class="moneyDiv">
            <div class="moneyName">
                {{$currencyName}}
            </div>
            <div class="moneyValue">
                {{$currencySymbol}} {{$content->money}}
            </div>
        </div>
        @if(!$content->booking)
            <div class="disableBookingErr">
                @foreach($content->bookingErr as $err)
                    <div class="err">{{$err}}</div>
                @endforeach
            </div>
        @endif
        <div class="orderDiv">
            <a href="{{route('book.package', ['packageId' => $content->id])}}" class="orderButton {{$content->booking ? '' : 'disable'}}" style="width: {{isset($content->brochure) && $content->brochure != null ? '50%' : '100%'}}">
                {{__('Book Now')}}
            </a>
            <a class="orderButton orderButtonRevers" href="{{$content->brochure}}"  style="display: {{isset($content->brochure) && $content->brochure != null ? 'flex' : 'none'}}; width: 50%;">
                {{__('Brochure')}}
            </a>
        </div>
    </div>
</div>
