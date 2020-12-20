<style>
    .recentlyPic{
        transition: .4s;
    }
    a:hover{
        text-decoration: none;
    }
    input:focus{
        border: none;
        border-radius: 30px;
        outline: none;
    }
    .searchContent{
        border: solid lightgray 1px;
        border-radius: 30px;
        display: flex;
        justify-content: space-between;
        width: 80%;
    }
    .searchInputDiv{
        width: 70%;
    }
    .searchInput{
        border: none;
        padding: 15px;
        font-size: 15px;
        border-radius: 30px;
        width: 100%;
    }
    .searchButton{
        background: #1f75b9;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 45px;
        border-radius: 35px;
        cursor: pointer;
    }

    .sideCateHeader{
        font-size: 18px;
        color: #616161;
    }
    .sideRecentlyDiv{

    }
    .recentDiv{
        display: flex;
        margin: 30px 0px;
        margin-top: 15px;
    }
    .recentImgDiv{
        width: 90px;
        height: 90px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .recentContentDiv{
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        margin-left: 10px;
        width: calc(100% - 90px);
    }
    .recentName{
        color: black;
        font-size: 18px;

    }
    .recentCategory{
        color: #757575;
        font-size: 12px;
    }
    .recentDate{
        color: #757575;
        font-size: 13px;
    }
    .sideCateDiv{
        display: flex;
        flex-direction: column;
        padding-top: 10px;
    }
    .sideCat{
        margin: 6px 0px;
        border-bottom: solid lightgray 1px;
        padding: 5px;
        color: #333333;
    }
    @media (max-width: 991px) {
        .searchContent{
            margin-top: 50px;
        }
    }

</style>

<div id="sideContentDiv" class="col-lg-3" style="visibility: hidden; padding: 0px">
    <div>
        <div class="row" style="justify-content: center">
            <div class="searchContent">
                <div class="searchInputDiv">
                    <input type="text" class="searchInput" id="sideSearchInput" placeholder="{{__('Enter to search...')}}">
                </div>
                <div class="searchButton" onclick="sideSearch()">
                    <i class="fas fa-search" style="color: white; font-family: 'Font Awesome 5 Free' !important;"></i>
                </div>
            </div>
        </div>

        <div class="row" style="justify-content: center; margin-top: 30px">
            <div style="width: 80%">
                <div class="sideCateHeader">
                    {{__('Categories')}}
                </div>
                <div class="sideCateDiv">
                    @foreach($allCategory as $item)
                        <a href="{{route('journal.list', ['kind' => 'category', 'value' => $item->name])}}" class="sideCat">
                            {{$item->name}}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if(isset($journal) && isset($journal->tag) && count($journal->tag) > 0)
            <div class="row" style="justify-content: center; margin-top: 30px">
                <div style="width: 80%">
                    <div class="sideCateHeader">
                        {{__('Tags')}}
                    </div>
                    <div class="sideCateDiv">
                        @foreach($journal->tag as $item)
                            <a href="{{route('journal.list', ['kind' => 'search', 'value' => $item])}}" class="sideCat">
                                {{$item}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="row" style="justify-content: center; margin-top: 30px">
            <div style="width: 80%">
                <div class="sideCateHeader">
                    {{__('Recently Journal')}}
                </div>
                <div class="sideRecentlyDiv">
                    @foreach($recentlyJournal as $item)
                        <div class="recentDiv">
                            <a href="{{$item->url}}" class="recentImgDiv">
                                <img src="{{$item->pic}}" alt="{{$item->name}}" class="resizeImage recentlyPic" style="width: 100%;" onload="fitThisImg(this)">
                            </a>
                            <div class="recentContentDiv">
                                <a href="{{$item->url}}" class="recentName">
                                    {{$item->name}}
                                </a>
                                <div class="recentCategory">
                                    {{$item->category}}
                                </div>
                                <div class="recentDate">
                                    {{$item->date}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script >
    $('#sideSearchInput').on('keypress', e => {
        if(e.which == 13)
            sideSearch();
    });
    function sideSearch(){
        let val = $('#sideSearchInput').val();
        if(val.trim().length > 2){
            window.location.href = '{{url("journal/list/search")}}/' + val;
        }
    }

    $('.recentlyPic').on('mouseenter', function(){
        $(this).css('transform', 'scale(1.2)');
    });
    $('.recentlyPic').on('mouseleave', function(){
        $(this).css('transform', 'scale(1)');
    });

    $(document).ready(function(){
        setTimeout(function(){
            $('#sideContentDiv').transition({
                animation  : 'fade up',
                duration   : '1s',
            });
        }, 300);
    });
</script>
