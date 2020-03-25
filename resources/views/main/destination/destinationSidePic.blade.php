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
        max-height: 400px;
        overflow-y: auto;
        text-align: justify;
        padding-right: 25px;
    }
    @media (max-width: 991px){
        .sidePicTextContent{
            max-height: none;
        }
    }
</style>

<div class="sidePicContent">
    <h1 class="sidePicHeader">
        {{$content->name}}
    </h1>
    <h4 class="sidePicCountry">
        {{$content->country->name}} , {{$content->city}}
    </h4>
    <div class="sidePicTagContent">
        @foreach($content->tags as $item)
            <div class="tagContent">
                {{$item}}
            </div>
        @endforeach
    </div>
    <div class="sidePicTextContent">
        {!! $content->description !!}
    </div>
</div>
