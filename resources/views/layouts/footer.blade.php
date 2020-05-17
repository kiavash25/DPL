<link rel="stylesheet" href="{{asset('css/footer.css')}}">

<footer>
    <div class="container footer">
        <div class="row footerContent pcFooter">
            <div class="col-3 contentRow">
                <div class="footerContentHeader">
                    {{__('Destinations')}}
                </div>
                @foreach($destCategory as $item)
                    <a href="{{route('show.category', ['slug' => $item->slug ])}}" class="footerContentNormal">
                        {{$item->name}}
                    </a>
                @endforeach
            </div>
            <div class="col-3 contentRow">
                <div class="footerContentHeader" style="text-align: {{count($activitiesList) > 5 ? 'center' : ''}}">
                    {{__('Activities')}}
                </div>
                <div class="row">
                    @foreach($activitiesList as $item)
                        <div class="col-md-{{count($activitiesList) > 5 ? '6' : 12}}" style="text-align: {{count($activitiesList) > 5 ? 'center' : ''}}">
                            <a href="{{route('show.activity', ['slug' => $item->slug])}}" class="footerContentNormal">
                                {{$item->name}}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-3 contentRow">
                <div class="footerContentHeader">
                    {{__('Fest & Events')}}
                </div>
            </div>
            <div class="col-3 contentRow">
                <a href="{{route('journal.index')}}" class="footerContentHeader">
                    {{__('Journal')}}
                </a>
                <a href="{{route('aboutUs')}}" class="footerContentHeader">
                    {{__('About us')}}
                </a>
                <a href="#" class="footerContentHeader">
                    {{__('Contact us')}}
                </a>
            </div>
        </div>

        <div class="mobileFooter">
            <button class="accordion">{{__('Destinations')}}</button>
            <div class="panel">
                @foreach($destCategory as $item)
                    <a href="{{route('show.list', ['kind' => 'destination', 'value1' => $item->name ])}}" class="inPanel">
                        {{$item->name}}
                    </a>
                @endforeach
            </div>

            <button class="accordion">{{__('Activities')}}</button>
            <div class="panel">
                @foreach($activitiesList as $item)
                    <a href="{{url('list/activity/'. $item->name)}}" class="inPanel">
                        {{$item->name}}
                    </a>
                @endforeach
            </div>

            <button class="accordion">{{__('Fest & Events')}}</button>

            <a href="{{route('journal.index')}}">
                <button class="accordion">{{__('Journal')}}</button>
            </a>

            <a href="{{route('aboutUs')}}">
                <button class="accordion">{{__('About us')}}</button>
            </a>

            <a href="#">
                <button class="accordion">{{__('Contact us')}}</button>
            </a>
        </div>

        <div class="socialContent">
            <a href="#" class="footerSocialIcons">
                <img src="{{asset('images/mainImage/facebook.png')}}" style="width: 100%; height: 100%">
            </a>
            <a href="#" class="footerSocialIcons">
                <img src="{{asset('images/mainImage/insta.png')}}" style="width: 100%; height: 100%">
            </a>
            <a href="#" class="footerSocialIcons">
                <img src="{{asset('images/mainImage/twitter.png')}}" style="width: 100%; height: 100%">
            </a>
            <a href="#" class="footerSocialIcons">
                <img src="{{asset('images/mainImage/teleg.png')}}" style="width: 100%; height: 100%">
            </a>
        </div>

        <div style="border-top: 1px solid #c7d0d9; text-align: center; padding: 30px 0px; font-size: 11px">
            Copyright © DPL. All rights reserved
        </div>
    </div>
</footer>
