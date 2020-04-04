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
</style>

<div class="sidePicContent">
    <h1 class="sidePicHeader">
        {{$content->name}}
    </h1>
    <h4 class="sidePicCountry">
        {{$content->category->name}}
    </h4>
    <div class="sidePicTagContent">
        @foreach($content->tags as $item)
            <a href="{{route('show.list', ['kind' => 'tags', 'value1' => $item])}}" class="tagContent">
                {{$item}}
            </a>
        @endforeach
    </div>
</div>
