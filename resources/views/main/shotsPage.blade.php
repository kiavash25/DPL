@extends('layouts.base')

@section('head')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="{{asset('css/swiper/swiper.css')}}">
    <script src="{{asset('js/swiper/swiper.js')}}"></script>

    <link rel="stylesheet" href="{{asset('css/pages/shots.css')}}">

    <?php
        $showLang = \App\models\Language::where('symbol', app()->getLocale())->first();
    ?>

    @if(isset($showLang->direction) && $showLang->direction == 'rtl')
        <link rel="stylesheet" href="{{asset('css/rtl/rtlShots.css')}}">
    @endif
@endsection


@section('body')
    <div class="shotHeaderBase">
        <div class="container shotHeader">
            <div class="shotTitle">
                {{__('Our shots')}}
                <span id="shotCategoryHeader" style="color: white"></span>
            </div>
            <div class="shotMenu">
                <div class="dropdown">
                    <div id="selectCategoryHeader" class="dropdown-toggle" data-toggle="dropdown">
                        {{__('All category')}}
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-item" onclick="showThisCategory(0)">{{__('All category')}}</div>
                        @foreach($category as $item)
                            <div class="dropdown-item" onclick="showThisCategory({{$item->id}})">{{$item->name}}</div>
                        @endforeach
                    </div>
                </div>
            </div>
{{--            <div class="newShotButton">--}}
{{--                new Shot--}}
{{--            </div>--}}
        </div>
    </div>
    <div class="container shotBody">
        <div id="col0" class="col-md-4"></div>
        <div id="col1" class="col-md-4"></div>
        <div id="col2" class="col-md-4"></div>
    </div>


    <div class="showShotFullPage">
        <input type="hidden" id="nowIndex">
        <input type="hidden" id="nowId">
        <div class="buttonFullPage" onclick="closeFullShot()">
            <i class="fa fa-times"></i>
        </div>
        <div class="fullShotTopHeader"></div>
        <div class="fullShotHeader">
            <div class="userInfo">
                <div class="userpic">
                    <img id="userPicFullImg" src="" class="resizeImage">
                </div>
                <div id="usernameFull" class="username"></div>
            </div>
            <div class="userAction">
                <div class="actionBox likeActionBox" onclick="likeShot()">
                    <i class="far fa-thumbs-up" aria-hidden="true"></i>
                    <span id="fullLike"></span>
                </div>
{{--                <div class="actionBox" style="color: red">--}}
{{--                    {{__('Delete')}}--}}
{{--                </div>--}}
            </div>
        </div>
        <div class="fullShotBody">
            <div class="fullImgDiv">
                <div class="arrowFullShow">
                    <div class="buttonFullPage nextArrow" onclick="nextFullShot(1)">
                        <i class="fas fa-angle-right"></i>
                    </div>
                    <div class="buttonFullPage prevArrow" onclick="nextFullShot(-1)">
                        <i class="fas fa-angle-left"></i>
                    </div>
                </div>
                <img id="fullImg" src="" style="max-width: 100%; max-height: 100%">
            </div>
            <div class="fullImgInfoDiv">
                <div id="fullName" class="mainInfo"></div>
                <div id="fullText" class="fullText"></div>
                <div id="fullTag" class="mainTags"></div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script>
        let login = {{auth()->check() ? true : 0}};
        let categories = {!! $category !!};
        let shots = {!! $shots !!};
        let showShots = shots;

        function openFullShot(_index){
            $('body').css('overflow-y', 'hidden');
            showFullIndex(_index);
            $('.showShotFullPage').show();
        }

        function nextFullShot(_kind){
            let nowIndex = $('#nowIndex').val();
            let next = parseInt(nowIndex) + parseInt(_kind);

            if(next < 0)
                next = showShots.length-1;
            if(next == showShots.length)
                next = 0;

            showFullIndex(next);
        }

        function showFullIndex(_index){
            $('#userPicFullImg').attr('src', showShots[_index].userpic);
            $('#usernameFull').text('');
            $('#fullLike').text(showShots[_index].like);
            $('#fullImg').attr('src', showShots[_index].pic);
            $('#fullName').text(showShots[_index].name);
            $('#fullText').text(showShots[_index].text);
            $('#nowIndex').val(_index);
            $('#nowId').val(showShots[_index].id);

            if(showShots[_index].youLike == 1)
                $('.likeActionBox').addClass('green');
            else
                $('.likeActionBox').removeClass('green');

            let tag = '';
            for(let i = 0; i < showShots[_index].tag.length; i++)
                tag += '<div class="tag">' + showShots[_index].tag[i] + '</div>';

            $('#fullTag').html(tag);
        }

        function closeFullShot(){
            $('body').css('overflow-y', 'auto');
            $('.showShotFullPage').hide();
        }

        function showThisCategory(_value){
            showShots = [];
            shots.forEach(item => {
                if(_value == 0 || item.categoryId == _value)
                    showShots.push(item);
            });

            if(_value == 0){
                $('#selectCategoryHeader').text('{{__("All category")}}');
                $('#shotCategoryHeader').text('');
            }
            else{
                categories.forEach(item => {
                  if(item.id == _value){
                      $('#selectCategoryHeader').text(item.name);
                      $('#shotCategoryHeader').text('_' + item.name);
                  }
                })
            }

            $('#col0').html('');
            $('#col1').html('');
            $('#col2').html('');

            for(let i = 0; i < showShots.length; i++){
                let col = i%3;
                let likeClass = '';
                if(showShots[i].youLike == 1)
                    likeClass = 'green';

                let text =  '<div class="imageSection" onclick="openFullShot(' + i + ')">' +
                            '   <img src="' + showShots[i].pic500 + '" style="width: 100%">' +
                            '   <div class="imageHover">' +
                            '       <div class="shotInfo">' +
                            '           <div class="shotName">' + showShots[i].name + '</div>' +
                            '           <div class="shotLike ' + likeClass + '">' +
                                            showShots[i].like +
                            '               <i class="far fa-thumbs-up" aria-hidden="true"></i>\n' +
                            '           </div>' +
                            '       </div>' +
                            '   </div>' +
                            '</div>';
                $('#col'+col).append(text);
            }
        }

        function likeShot(){
            if(login){
                let _id = $('#nowId').val();
                let _index = $('#nowIndex').val();

                $.ajax({
                    type: 'post',
                    url: '{{route('shot.like')}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id
                    },
                    success: function(response){
                        let youLike = false;
                        if(response == 'add' || response == 'delete')
                            youLike = true;

                        if(youLike){
                            shots.forEach(item => {
                                if(item.id == _id) {
                                    if(response == 'add'){
                                        item.youLike = 1;
                                        item.like += 1;
                                    }
                                    else if(response == 'delete'){
                                        item.youLike = 0;
                                        item.like -= 1;
                                    }
                                }
                            });

                            closeFullShot();
                            openFullShot(_index);
                        }
                    }
                })
            }
            else
                location.href = '{{route('loginPage')}}';
        }

        showThisCategory(0);
    </script>

@endsection
