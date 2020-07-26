@if($hasMoreInfo > 0)
    <div class="row" style="margin-top: 30px;">
        <div class="aboutHeader" style="width: 100%">
            {{__('More Info')}}:
        </div>
        <div class="MoreInfoBase" style="border-bottom: 0; border-radius: 10px 10px 0px 0px">
            <div class="moreInfoHeader" onclick="openMoreInfoDiv(this)">
                <div class="arrow down"></div>
                {{__('Neutral Details')}}
            </div>
            <div class="moreInfoContentDiv">
                <div class="row">
                    <div class="moreInfoContentHeaderDiv">
                        <?php
                        $firsTitle = 0;
                        ?>
                        @foreach($moreInfoNeutral as $item)
                            @if(isset($item->text) && $item->text != null)
                                <div class="moreInfoTitles" onclick="showMoreInfoText(this, {{$item->id}})">
                                    <div class="moreInfoTitleTextNoneSelected {{$firsTitle == 0 ? 'moreInfoTitleTextSelected firstMoreInfoTitle' : ''}}">
                                        {{$item->name}}
                                    </div>
                                </div>
                                <?php
                                $firsTitle++;
                                ?>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row moreInfoTextDiv">
                    <?php
                    $firsText = 0;
                    ?>
                    @foreach($moreInfoNeutral as $item)
                        @if(isset($item->text) && $item->text != null)
                            <div id="moreInfoText_{{$item->id}}" class="moreInfoText {{$firsText == 0 ? 'moreInfoTextOpen firstMoreInfoText' : ''}}">
                                {!! $item->text !!}
                            </div>
                            <?php
                            $firsText++;
                            ?>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="MoreInfoBase" style="border-radius: 0px 0px 10px 10px">
            <div class="moreInfoHeader" onclick="openMoreInfoDiv(this)">
                <div class="arrow down"></div>
                {{__('Culventure Details')}}
            </div>
            <div class="moreInfoContentDiv">
                <div class="row">
                    <div class="moreInfoContentHeaderDiv">
                        <?php
                        $firsTitle = 0;
                        ?>
                        @foreach($moreInfoCallVenture as $item)
                            @if(isset($item->text) && $item->text != null)
                                <div class="moreInfoTitles" onclick="showMoreInfoText(this, {{$item->id}})">
                                    <div class="moreInfoTitleTextNoneSelected {{$firsTitle == 0 ? 'moreInfoTitleTextSelected firstMoreInfoTitle' : ''}}">
                                        {{$item->name}}
                                    </div>
                                </div>
                                <?php
                                $firsTitle++;
                                ?>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row moreInfoTextDiv">
                    <?php
                    $firsText = 0;
                    ?>
                    @foreach($moreInfoCallVenture as $item)
                        @if(isset($item->text) && $item->text != null)
                            <div id="moreInfoText_{{$item->id}}" class="moreInfoText {{$firsText == 0 ? 'moreInfoTextOpen firstMoreInfoText' : ''}}">
                                {!! $item->text !!}
                            </div>
                            <?php
                            $firsText++;
                            ?>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
