<style>

    .aboutHeader{
        font-size: 25px;
        font-weight: bold;
    }
    .aboutText{
        padding: 10px 25px;
        text-align: justify;
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
        top: 0px;
        width: 100%;
        margin-top: 0px !important;
        left: 0;
        display: flex;
        z-index: 99;
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
        if($content->video != null || $content->podcast != null){
            $descNum = 6;
            $vidNum = 6;
        }
        else{
            $descNum = 12;
            $vidNum = 0;
        }
    ?>

    <div class="col-md-{{$descNum}}">
        <div class="aboutHeader">
            {{__('About')}} {{$content->name}}
        </div>
        <div class="aboutText">
            {!! $content->description !!}
        </div>
    </div>
    <div class="col-md-{{$vidNum}}">
        @if($content->video)
            <div class="aboutHeader" style="margin-top: 10px">
                {{__('Video')}}
            </div>

            @if($content->isEmbeded == 1)
                {!! $content->video !!}
            @else
                <video poster="placeholder.png" controls style="width: 100%;">
                    <source src="{{$content->video}}#t=1">
                </video>
            @endif
        @endif
    </div>
</div>
