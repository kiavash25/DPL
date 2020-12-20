<?php

namespace App\Http\Controllers;

use App\models\Booking;
use App\models\Activity;
use App\models\ActivityPic;
use App\models\ActivityTitle;
use App\models\Award;
use App\models\City;
use App\models\Continents;
use App\models\Countries;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\DestinationCategoryPic;
use App\models\DestinationCategoryTitle;
use App\models\DestinationCategoryTitleText;
use App\models\DestinationPic;
use App\models\DestinationTagRelation;
use App\models\Journal;
use App\models\JournalCategory;
use App\models\MainPageSetting;
use App\models\MainPageSlider;
use App\models\NatureFriend;
use App\models\NatureFriendPic;
use App\models\Package;
use App\models\PackageActivityRelations;
use App\models\PackageMoreInfo;
use App\models\PackageMoreInfoRelation;
use App\models\PackagePic;
use App\models\PackageSideInfo;
use App\models\PackageTagRelation;
use App\models\PackageThumbnailsPic;
use App\models\Subscribe;
use App\models\SupportUs;
use App\models\Tags;
use Carbon\Carbon;
use DemeterChain\Main;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MainController extends Controller
{

    public function mainPage()
    {
        $today = Carbon::now()->format('Y-m-d');

        $mainPageSlider = MainPageSlider::where('lang', app()->getLocale())->orderByDesc('showNumber')->get();
        if(count($mainPageSlider) == 0)
            $mainPageSlider = MainPageSlider::where('lang', 'en')->orderByDesc('showNumber')->get();

        foreach ($mainPageSlider as $item)
            $item->pic = asset('images/MainSliderPics/' . $item->pic);

        $destinationCategoryMain = DestinationCategory::where('lang', app()->getLocale())->get();
        foreach ($destinationCategoryMain as $categ) {
            $categ->destination = Destination::where('categoryId', $categ->id)->where('lang', app()->getLocale())->orderBy('name')->get(['id', 'name', 'slug', 'categoryId', 'pic', 'description', 'langSource']);
            foreach ($categ->destination as $dest){
                if($dest->langSource == 0)
                    $dest->pic = asset('uploaded/destination/' . $dest->id . '/' . $dest->pic);
                else
                    $dest->pic = asset('uploaded/destination/' . $dest->langSource . '/' . Destination::find($dest->langSource)->pic);
                $dest->url = route('show.destination', ['slug' => $dest->slug]);
                $dest->description = strip_tags($dest->description);
            }
        }

        $recentlyPackage = Package::where('showPack', 1)->where('lang', app()->getLocale())->where('popularNum', '>', 0)
                                    ->where(function ($query) {
                                        $today = Carbon::now()->format('Y-m-d');
                                        $query->where('eDate', '>', $today)
                                            ->orWhereNull('sDate');
                                    })
                                    ->orderByDesc('popularNum')->take(8)->get();
        foreach ($recentlyPackage as $item) {
            $item = getMinPackage($item, 'list');
            $item->money = number_format($item->money);
        }

        $mainSliderJournal = Journal::where('releaseDate' , '!=', 'draft')->where('lang', app()->getLocale())->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'pic'])->orderByDesc('releaseDate')->take(4)->get();
        foreach ($mainSliderJournal as $item) {
            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
            $item->category = JournalCategory::find($item->categoryId);
            if($item->category != null)
                $item->category = $item->category->name;
        }

        $mapDestination = DestinationCategory::where('lang', app()->getLocale())->get();
        foreach ($mapDestination as $cate){
            if($cate->langSource == 0) {
                if ($cate->icon != null)
                    $cate->icon = asset('uploaded/destination/category/' . $cate->id . '/' . $cate->icon);
                else
                    $cate->icon = null;
            }
            else{
                $sourceIcon = DestinationCategory::find($cate->langSource);
                if($sourceIcon != null && $sourceIcon->icon != null)
                    $cate->icon = asset('uploaded/destination/category/' . $sourceIcon->id . '/' . $sourceIcon->icon);
                else
                    $cate->icon = null;
            }

            $cate->destinations = Destination::where('lang', app()->getLocale())->where('categoryId', $cate->id)->select(['id', 'name', 'slug', 'lat', 'lng'])->get();
            foreach ($cate->destinations as $item)
                $item->url = route('show.destination', ['slug' => $item->slug]);
        }

        $aboutUs = MainPageSetting::where('header', 'aboutus')->where('lang', app()->getLocale())->first();
        if($aboutUs != null)
            $aboutUs->pic = asset('uploaded/mainPage/' . $aboutUs->pic);

        $center = MainPageSetting::where('header', '!=', 'aboutus')->where('lang', app()->getLocale())->get();
        foreach ($center as $item)
            $item->pic = asset('uploaded/mainPage/' . $item->pic);

        $supportUs = SupportUs::all();
        foreach ($supportUs as $item) {
            $item->pic = asset('uploaded/mainPage/' . $item->pic);
            if($item->link == null)
                $item->link = '#';
        }

        $awards = Award::where('lang', app()->getLocale())->get();
        foreach ($awards as $item) {
            $item->pic = asset('images/awards/' . $item->pic);
            if($item->link == null)
                $item->link = '#';
        }

        return \view('main.mainPage', compact(['destinationCategoryMain', 'supportUs',
                                                    'recentlyPackage', 'mainPageSlider',
                                                    'mapDestination', 'mainSliderJournal',
                                                    'aboutUs', 'center', 'awards']));
    }

    public function showActivity($slug)
    {
        $kind = 'activity';
        $content = Activity::where('slug', $slug)->where('lang', app()->getLocale())->first();
        if($content == null)
            return redirect(url('/'));

        $content->titles = ActivityTitle::where('activityId', $content->id)->get();

        $slip = [];
        if($content->langSource == 0)
            $picId =  $content->id;
        else
            $picId = $content->langSource;

        $loc = 'uploaded/activity/' . $picId;
        $sliderPics = ActivityPic::where('activityId', $picId)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'alt' =>  $item->alt,
                'id' => $item->id
            ]);
        }
        $content->slidePic = $slip;

        if($content->parent != 0)
            $mainActivityId = [$content->id];
        else
            $mainActivityId = Activity::where('parent', $content->id)->pluck('id')->toArray();

        $content->mapPackages = Package::where('showPack', 1)
            ->whereIn('mainActivityId', $mainActivityId)
            ->where('lang', app()->getLocale())
            ->where(function ($query) {
                $today = Carbon::now()->format('Y-m-d');
                $query->where('eDate', '>', $today)
                    ->orWhereNull('sDate');
            })->get();

        $content->latCenter = 0;
        $content->lngCenter = 0;
        $content->mapCount = 0;
        foreach ($content->mapPackages as $item){
            $content->latCenter += (float)$item->lat;
            $content->lngCenter += (float)$item->lng;
            $content->mapCount++;

            $item->url = route('show.package', ['slug' => $item->slug]);
        }
        if($content->mapCount > 0) {
            $content->latCenter /= $content->mapCount;
            $content->lngCenter /= $content->mapCount;
        }
        else{
            $content->latCenter = 32.427908;
            $content->lngCenter = 53.688046;
        }

        $today = Carbon::now()->format('Y-m-d');
        $packages = Package::where('showPack', 1)
            ->whereIn('mainActivityId', $mainActivityId)
            ->where('lang', app()->getLocale())
            ->where(function ($query) {
                $today = Carbon::now()->format('Y-m-d');
                $query->where('eDate', '>', $today)
                    ->orWhereNull('sDate');
            })
            ->orderBy('sDate')->take(5)->get();
        foreach ($packages as $item)
            $item = getMinPackage($item);
        $content->packages = $packages;

//        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $content->slug]);
        $content->packageListUrl = '#';

        if($content->video != null && $content->isEmbeded == 0)
            $content->video = asset('uploaded/activity/'. $content->id . '/' . $content->video);
        if($content->podcast != null)
            $content->podcast = asset('uploaded/activity/'. $content->id . '/' . $content->podcast);

        if($content->parent == 0)
            $guidance = ['value1' => $content->name, 'value1Url' => '#'];
        else {
            $parent = Activity::find($content->parent);
            $guidance = ['value1' => $parent->name, 'value1Url' => route('show.activity', ['slug' => $parent->slug]),
                        'value2' => $content->name, 'value2Url' => '#'];
        }

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function showDestination($slug)
    {
        $kind = 'destination';

        $content = Destination::where('slug', $slug)->where('lang', app()->getLocale())->first();
        if($content == null)
            return redirect(url('/'));

        $category = DestinationCategory::find($content->categoryId);
        if($category == null)
            return redirect(url('/'));

        $content->category = $category;
        $location = __DIR__.'/../../../public/uploaded/destination/category/' . $category->id . '/' . $category->icon;
        if($category->icon != null && is_file($location))
            $content->icon = asset('uploaded/destination/category/' . $category->id . '/' . $category->icon);
        else
            $content->icon = '0';

        $content->titles = DestinationCategoryTitle::where('categoryId', $content->categoryId)->get();
        foreach ($content->titles as $item){
            $item->text = DestinationCategoryTitleText::where('destId', $content->id)->where('titleId', $item->id)->first();
            if($item->text != null)
                $item->text = $item->text->text;
        }

        $tags = DestinationTagRelation::where('destId', $content->id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $content->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $content->tags = [];

        if($content->langSource == 0)
            $picFolder = $content->id;
        else
            $picFolder = $content->langSource;

        $slip = [];
        $loc = 'uploaded/destination/' . $picFolder;
        $sliderPics =  DestinationPic::where('destId', $picFolder)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'alt' =>  $item->alt,
                'id' => $item->id
            ]);
        }
        if($content->langSource == 0)
            $mainPic = $content->pic;
        else
            $mainPic = Destination::find($content->langSource)->pic;

        if ($mainPic != null) {
            array_unshift($slip, (object)[
                'pic' => getKindPic($loc, $mainPic, ''),
                'slide' => getKindPic($loc, $mainPic, 'slide'),
                'thumbnail' => getKindPic($loc, $mainPic, 'min'),
                'alt' => $content->name,
                'id' => 0
            ]);
        }

        $content->slidePic = $slip;

        $today = Carbon::now()->format('Y-m-d');
        $content->packages = Package::where('showPack', 1)
                            ->where('destId', $content->id)
                            ->where(function ($query) {
                                $today = Carbon::now()->format('Y-m-d');
                                $query->where('eDate', '>', $today)->orWhereNull('sDate');
                            })
                            ->orderBy('sDate')->take(5)->get();
        foreach ($content->packages as $item)
            $item = getMinPackage($item);

//        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $content->slug]);
        $content->packageListUrl = '#';

        if($content->video != null && $content->isEmbeded == 0)
            $content->video = asset('uploaded/destination/'. $content->id . '/' . $content->video);
        if($content->podcast != null)
            $content->podcast = asset('uploaded/destination/'. $content->id . '/' . $content->podcast);


        $guidance = ['value1' => $content->category->name, 'value1Url' => route('show.category', ['slug' => $content->category->slug]),
                    'value2' => $content->name, 'value2Url' => route('show.destination', ['slug' => $content->slug])];

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function showPackage($slug)
    {
        $kind = 'package';

        $content = Package::where('slug', $slug)->where('lang', app()->getLocale())->first();
        if($content == null)
            return redirect(url('/'));

        $destination = Destination::find($content->destId);
        if($destination == null)
            return redirect(url('/'));

        $destination->city = City::find($destination->cityId);
        $destination->category = DestinationCategory::find($destination->categoryId);

        $content->destination = $destination;

        $content->mainActivity = Activity::find($content->mainActivityId);
//        $content->mainActivity->icon = asset('uploaded/activityIcons/' . $content->mainActivity->icon);

        $activities = PackageActivityRelations::where('packageId', $content->id)->pluck('activityId')->toArray();
        $content->activities = Activity::whereIn('id', $activities)->get();
        foreach ($content->activities as $item)
            $item->icon = asset('uploaded/activityIcons/' . $item->icon);

        $content->sideInfos = PackageSideInfo::where('packageId', $content->id)->get();
        foreach ($content->sideInfos as $sideInfo)
            $sideInfo->icon = asset('uploaded/packages/' . $content->id . '/' . $sideInfo->icon);

        $tags = PackageTagRelation::where('packageId', $content->id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $content->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $content->tags = [];

        if($content->langSource == 0) {
            $picId = $content->id;
            $picName = $content->pic;
        }
        else {
            $picId = $content->langSource;
            $picName = Package::find($content->langSource)->pic;
        }
        $slip = [];
        $loc = 'uploaded/packages/' . $picId ;
        $sliderPics =  PackagePic::where('packageId', $picId)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'alt' =>  $item->alt,
                'id' => $item->id
            ]);
        }
        if($picName != null) {
            array_unshift($slip, (object)[
                'pic' => getKindPic($loc, $picName, ''),
                'slide' =>  getKindPic($loc, $picName, 'slide'),
                'thumbnail' =>  getKindPic($loc, $picName, 'min'),
                'alt' =>  $content->name,
                'id' => 0
            ]);
        }
        $content->slidePic = $slip;

        $content->thumbnails = PackageThumbnailsPic::where('packageId', $picId)->get();
        foreach ($content->thumbnails as $item){
            $item->thumbnail = asset('uploaded/packages/' . $picId . '/thumbnail_' . $item->pic);
            $item->pic = asset('uploaded/packages/' . $picId . '/' . $item->pic);
        }

        $content->packages = Package::where('id', '!=', $content->id)
                        ->where('showPack', 1)
                        ->where('lang', app()->getLocale())
                        ->where(function ($query) {
                            $today = Carbon::now()->format('Y-m-d');
                            $query->where('eDate', '>', $today)
                                ->orWhereNull('sDate');
                        })
                        ->where('destId', $content->destination->id)
                        ->orderBy('sDate')->take(5)->get();
        foreach ($content->packages as $item)
            $item = getMinPackage($item);

        $content->actPackage = Package::where('id', '!=', $content->id)
                                ->where('showPack', 1)
                                ->where('lang', app()->getLocale())
                                ->where(function ($query) {
                                    $today = Carbon::now()->format('Y-m-d');
                                    $query->where('eDate', '>', $today)
                                        ->orWhereNull('sDate');
                                })
                                ->where('mainActivityId', $content->mainActivityId)
                                ->orderBy('sDate')->take(5)->get();
        foreach ($content->actPackage as $item)
            $item = getMinPackage($item);

        if($content->brochure != null)
            $content->brochure = asset('uploaded/packages/' . $content->id . '/' . $content->brochure);

        $content->money = number_format($content->money);

        $hasMoreInfo = 0;
        $moreInfoCallVenture = PackageMoreInfo::where('category', 'callventureDetail')->where('lang', app()->getLocale())->get();
        $moreInfoNeutral = PackageMoreInfo::where('category', 'neutralDetail')->where('lang', app()->getLocale())->get();
        foreach ($moreInfoCallVenture as $item){
            $text = PackageMoreInfoRelation::where('moreInfoId', $item->id)->where('packageId', $content->id)->first();
            if($text != null) {
                $item->text = $text->text;
                $hasMoreInfo++;
            }
        }
        foreach ($moreInfoNeutral as $item){
            $text = PackageMoreInfoRelation::where('moreInfoId', $item->id)->where('packageId', $content->id)->first();
            if($text != null) {
                $item->text = $text->text;
                $hasMoreInfo++;
            }
        }

        $content->booking = true;
        $bookingErr = array();

        if($content->capacity != 0 && $content->capacity != null) {
            $registered = Booking::where('eventKind', 'package')->where('eventId', $content->id)->count();
            $content->availableCap = $content->capacity - $registered;
            if($content->availableCap < 1) {
                $content->booking = false;
                 array_push($bookingErr, __('Capacity full'));
            }
            $content->capacity = $content->availableCap;
        }
        $today = Carbon::now()->format('Y-m-d');

        if($content->registerSDate != null && $content->registerSDate > $today) {
            $content->booking = false;
            array_push($bookingErr, __('Registration time is not started.'));
        }

        if($content->registerEDate != null && $content->registerEDate < $today) {
            $content->booking = false;
            array_push($bookingErr, __('Registration time is over.'));
        }
        $content->bookingErr = $bookingErr;

//        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $destination->slug]);
        $content->packageListUrl = '#';

        $guidance = ['value1' => $destination->category->name, 'value1Url' => route('show.category', ['slug' => $destination->category->slug]),
            'value2' => $destination->name, 'value2Url' => route('show.destination', ['slug' => $destination->slug]),
            'value3' => $content->name, 'value3Url' => '#'];

        return view('main.content', compact(['content', 'kind', 'guidance', 'moreInfoCallVenture', 'moreInfoNeutral', 'hasMoreInfo']));
    }

    public function showCategory($slug)
    {
        $kind = 'category';
        $content = DestinationCategory::where('slug', $slug)->where('lang', app()->getLocale())->first();
        if($content == null)
            return redirect(url('/'));
        else{
            $slip = [];
            if($content->langSource == 0) {
                $picId = $content->id;
                $picName = $content->pic;
            }
            else {
                $picId = $content->langSource;
                $picName = DestinationCategory::find($content->langSource)->pic;
            }
            $mainLoc = __DIR__  . '/../../../public/uploaded/destination/category/' . $picId;
            $loc = 'uploaded/destination/category/' . $picId;
            $sliderPics =  DestinationCategoryPic::where('categoryId', $picId)->get();

            if(is_file($mainLoc . '/' .$content->icon))
                $content->icon = asset($loc . '/' . $content->icon);
            else
                $content->icon = null;

            foreach ($sliderPics as $item){
                array_push($slip, (object)[
                    'pic' => getKindPic($loc, $item->pic, ''),
                    'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                    'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                    'alt' =>  $item->alt,
                    'id' => $item->id
                ]);
            }
            if($picName != null) {
                array_unshift($slip, (object)[
                    'pic' => getKindPic($loc, $picName, ''),
                    'slide' =>  getKindPic($loc, $picName, 'slide'),
                    'thumbnail' =>  getKindPic($loc, $picName, 'min'),
                    'alt' => $content->name,
                    'id' => 0
                ]);
            }
            $content->slidePic = $slip;

            if(count($content->slidePic) == 0){
                $slidePic = [];
                $destIds = Destination::where('categoryId', $content->id)->select(['id', 'pic', 'name'])->inRandomOrder()->take(5)->get();
                foreach ($destIds as $de){
                    $loc = 'uploaded/destination/' . $de->id;
                    array_push($slidePic, (object)[
                        'pic' => getKindPic($loc, $de->pic, ''),
                        'slide' =>  getKindPic($loc, $de->pic, 'slide'),
                        'thumbnail' =>  getKindPic($loc, $de->pic, 'min'),
                        'alt' => $de->name,
                        'id' => $de->id
                    ]);
                }
                $content->slidePic = $slidePic;
            }

            $content->titles = DestinationCategoryTitle::where('categoryId', $content->id)->get();

            if($content->video != null && $content->isEmbeded == 0)
                $content->video = asset('uploaded/destination/category/' . $content->id . '/' . $content->video);

            if($content->podcast != null)
                $content->podcast = asset('uploaded/destination/category/' . $content->id . '/' . $content->podcast);

            $today = Carbon::now()->format('Y-m-d');

            $packages = [];
            $destinations = Destination::where('categoryId', $content->id)->get();
            $lat = 0;
            $lng = 0;
            $count = 0;
            foreach ($destinations as $item){
                if($item->langSource == 0){
                    $picId = $item->id;
                    $picName = $item->pic;
                }
                else{
                    $picId = $item->langSource;
                    $picName = Destination::find($item->langSource)->pic;
                }
                $l = 'uploaded/destination/' . $picId;
                $item->minPic = getKindPic($l, $picName, 'min');
                $item->description = strip_tags($item->description);
                $item->url = route('show.destination', ['slug' => $item->slug]);

                $p = Package::where('destId', $item->id)
                            ->where('showPack', 1)
                            ->where(function ($query) {
                                $today = Carbon::now()->format('Y-m-d');
                                $query->where('eDate', '>', $today)
                                    ->orWhereNull('sDate');
                            })->orderBy('day', 'DESC')->take(1)->get();
                if(count($p) != 0) {
                    $p = getMinPackage($p[0]);
                    array_push($packages, $p);
                }

//                average lat and lng for map center
                if($item->lat != 0 && $item->lng != 0){
                    $lat += (float)$item->lat;
                    $lng += (float)$item->lng;
                    $count++;
                }
            }
            $content->destinations = $destinations;
            $content->packages = $packages;
            if($count > 0){
                $lat /= $count;
                $lng /= $count;
                $content->mapCenter = ['lat' => $lat, 'lng' => $lng];
            }
            else
                $content->mapCenter = ['lat' => 0, 'lng' => 0];

            $kind = 'category';
            $guidance = ['value1' => $content->name, 'value1Url' => route('show.category', ['slug' => $content->slug])];

            return \view('main.content', compact(['content', 'kind', 'guidance']));
        }
    }

    public function showNatureFriend($slug)
    {
        $kind = 'natureFriend';

        $content = NatureFriend::where('slug', $slug)->where('lang', app()->getLocale())->first();
        if($content == null)
            return redirect(url('/'));

        if($content->langSource == 0)
            $picId = $content->id;
        else
            $picId = $content->langSource;

        $slip = [];
        $loc = 'uploaded/natureFriend/' . $picId;
        $sliderPics =  NatureFriendPic::where('natId', $picId)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'id' => $item->id
            ]);
        }
        if($content->langSource == 0)
            $mainPic = $content->pic;
        else
            $mainPic = NatureFriend::find($content->langSource)->pic;

        if ($mainPic != null) {
            array_unshift($slip, (object)[
                'pic' => getKindPic($loc, $mainPic, ''),
                'slide' => getKindPic($loc, $mainPic, 'slide'),
                'thumbnail' => getKindPic($loc, $mainPic, 'min'),
                'id' => 0
            ]);
        }

        $content->slidePic = $slip;

        if($content->video != null && $content->isEmbeded == 0)
            $content->video = asset('uploaded/natureFriend/'. $content->id . '/' . $content->video);
        if($content->podcast != null)
            $content->podcast = asset('uploaded/natureFriend/'. $content->id . '/' . $content->podcast);


        $guidance = ['value1' => __('Nature friend'), 'value1Url' => '#', 'value2' => __('Events'), 'value2Url' => '#',
                    'value3' => $content->name, 'value3Url' => '#'];

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function firstNatureFriend()
    {
        $kind = 'natureFriend';
        $content = NatureFriend::where('lang', app()->getLocale())->first();
        if($content == null)
            return redirect(url('/'));

        if($content->langSource == 0)
            $picId = $content->id;
        else
            $picId = $content->langSource;

        $slip = [];
        $loc = 'uploaded/natureFriend/' . $picId;
        $sliderPics =  NatureFriendPic::where('natId', $picId)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'alt' => $item->alt,
                'id' => $item->id
            ]);
        }
        if($content->langSource == 0)
            $mainPic = $content->pic;
        else
            $mainPic = NatureFriend::find($content->langSource)->pic;

        if ($mainPic != null) {
            array_unshift($slip, (object)[
                'pic' => getKindPic($loc, $mainPic, ''),
                'slide' => getKindPic($loc, $mainPic, 'slide'),
                'thumbnail' => getKindPic($loc, $mainPic, 'min'),
                'alt' => $content->name,
                'id' => 0
            ]);
        }

        $content->slidePic = $slip;

        if($content->video != null && $content->isEmbeded == 0)
            $content->video = asset('uploaded/natureFriend/'. $content->id . '/' . $content->video);
        if($content->podcast != null)
            $content->podcast = asset('uploaded/natureFriend/'. $content->id . '/' . $content->podcast);


        $guidance = ['value1' => __('Nature friend'), 'value1Url' => '#', 'value2' => __('Events'), 'value2Url' => '#',
            'value3' => $content->name, 'value3Url' => '#'];

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function beforeList(Request $request)
    {
        $getValue = '?';
//        dd($request->all());

        if(isset($request->season)){
            $getValue .= 'season=' . $request->season;
        }

        if(isset($request->activity)){
            $activit = Activity::where('name', $request->activity)->first();
            if($activit != null){
                if($getValue != '?')
                    $getValue .= '&';
                $getValue .= 'activity=' . $activit->slug;
            }
        }

        if(isset($request->destination)){
            $destId = Destination::where('name', $request->destination)->first();
            if($destId != null)
                session(['destId' => $destId->id]);
        }

        return redirect(url('list/package/filter/' . $getValue));
    }

    public function list($kind, $value1)
    {
        if($kind == 'destination'){
            $category = DestinationCategory::where('name', $value1)->where('lang', app()->getLocale())->first();
            if($category == null)
                return redirect(url('/'));

            $today = Carbon::now()->format('Y-m-d');
            $destinations = Destination::where('categoryId', $category->id)->where('lang', app()->getLocale())->get();
            foreach ($destinations as $item) {
                $item->pic = asset('uploaded/destination/' . $item->id . '/' . $item->pic);
                $item->package = Package::where('destId', $item->id)
                                        ->where('showPack', 1)
                                        ->where(function ($query) {
                                            $today = Carbon::now()->format('Y-m-d');
                                            $query->where('eDate', '>', $today)
                                                ->orWhereNull('sDate');
                                        })->count();
            }

            $title = 'Destinations :' . $category->name;
            $guidance = ['value1' => $category->name, 'value1Url' => '#'];

            return \view('main.list', compact(['kind', 'destinations', 'guidance', 'title']));
        }
        else{
            $guidance = [];
            $destination = 'all';
            $season = 'all';
            $tag = 'all';
            $activity = [];

            if(isset($_GET['activity'])){
                $activityId = Activity::where('slug', $_GET['activity'])->where('lang', app()->getLocale())->first();
                if($activityId != null && $activityId->parent == 0)
                    $activity = Activity::where('parent', $activityId->id)->where('lang', app()->getLocale())->pluck('id')->toArray();
            }

            if(isset($_GET['season']))
                $season = $_GET['season'];

            switch ($kind){
                case 'destinationPackage':
                    $destination = Destination::where('slug', $value1)->where('lang', app()->getLocale())->first();
                    if ($destination != null) {
                        $guidance = ['value1' => $destination->name, 'value1Url' => route('show.destination', ['slug' => $destination->slug]),
                                    'value2' => __('List'), 'value2Url' => ''];
                        $title = __('Package List') . ' :' . $destination->name;
                        $destination = $destination->id;
                    }
                    else
                        return redirect()->back();
                    break;
                case 'tags':
                    $tag = $value1;
                    $title = __('Tag list') . ' : ' . $value1 ;
                    $guidance = ['value1' => 'Tags', 'value1Url' => '#',
                        'value2' => $value1, 'value2Url' => '#'];
                    break;
                default:
                    $title = '';
                    if(session('destId')){
                        $destId = session('destId');
                        $destination = Destination::find($destId);
                        if ($destination != null) {
                            $ci = City::find($destination->cityId);
                            $guidance = ['value1' => 'Destination', 'value1Url' => route('show.list', ['kind' => 'destination', 'value1' => 'All']),
                                'value2' => $destination->name, 'value2Url' => route('show.destination', ['slug' => $destination->slug])];
                            $title = $destination->name . ' ' . __('Package List');
                            $destination = $destination->id;
                        }
                        session()->forget('destId');
                    }

                    if(count($guidance) == 0) {
                        $guidance = ['value1' => 'All Packages', 'value1Url' => '#'];
                        $title = 'All Package List';
                    }
                    break;
            }

            $maxCost = Package::where('lang', app()->getLocale())->orderByDesc('money')->first();
            if($maxCost != null)
                $maxCost = $maxCost->money;
            else
                $maxCost = 0;

            return \view('main.list', compact(['kind', 'activity', 'destination', 'season', 'guidance', 'title', 'tag', 'maxCost']));
        }
    }

    public function getListElems(Request $request)
    {
        $packagesId = [];

        $page = $request->page;
        $take = $request->perPage;
        $sort = $request->sort;
        $money = $request->cost;
        $kind = $request->kind;
        $activityId = $request->activityId;
        $activityIds = [];
        $isSearchInActivityId = false;
        $sqlQuery = '';

        if($activityId != null) {
            foreach ($activityId as $item) {
                if ($item != '0') {
                    $isSearchInActivityId = true;
                    array_push($activityIds, $item);
                }
            }

            if ($isSearchInActivityId) {
                $sqlQuery = 'mainActivityId In (' . implode(",", $activityIds) . ')';
            }
        }

        $sea = $request->season;
        $seasons = [];
        $isSearchInSeason = false;
        if($sea != null) {
            foreach ($sea as $item) {
                if ($item != '0') {
                    $isSearchInSeason = true;
                    array_push($seasons, $item);
                }
            }
            if($isSearchInSeason){
                if($sqlQuery != '')
                    $sqlQuery .= ' AND';

                $sqlQuery .= ' season In (';
                foreach ($seasons as $key => $item) {
                    $sqlQuery .= '"' . $item. '"';
                    if($key != count($seasons)-1)
                        $sqlQuery .= ', ';
                }
                $sqlQuery .= ')';
            }
        }

        $tag = $request->tag;
        if($tag != 'all'){
            $tag = Tags::where('tag', $tag)->first();
            $packagesId = PackageTagRelation::where('tagId', $tag->id)->pluck('packageId')->toArray();

            if(count($packagesId) == 0){
                echo json_encode(['status' => 'ok', 'result' => $packagesId]);
                return;
            }
            if($sqlQuery != '')
                $sqlQuery .= ' AND';
            $sqlQuery .= ' id IN (' . implode(",", $packagesId) . ')';
        }

        $destinationId = $request->destinationId;
        if($destinationId != 'all'){
            $destinat = Destination::find($destinationId);
            if($destinat == null){
                echo json_encode(['status' => 'ok', 'result' => []]);
                return;
            }
            else{
                if($sqlQuery != '')
                    $sqlQuery .= ' AND';
                $sqlQuery .= ' destId = ' . $destinat->id;
            }
        }

        if($sqlQuery != '')
            $sqlQuery .= ' AND';
        $sqlQuery .= ' money >= ' . $money[0] . ' AND money <= ' . $money[1];

        $today = Carbon::now()->format('Y-m-d');
        switch ($sort){
            case 'nearestDate':
                $orderBy = 'sDate';
                $orderType = 'ASC';
                break;
            case 'minConst':
                $orderBy = 'money';
                $orderType = 'ASC';
                break;
            case 'maxConst':
                $orderBy = 'money';
                $orderType = 'DESC';
                break;
            case 'minDay':
                $orderBy = 'day';
                $orderType = 'ASC';
                break;
            case 'maxDay':
                $orderBy = 'day';
                $orderType = 'DESC';
                break;
        }

        $packages = Package::where('showPack', 1)->whereRaw($sqlQuery)
            ->where(function ($query) {
                $today = Carbon::now()->format('Y-m-d');
                $query->where('eDate', '>', $today)
                        ->orWhereNull('sDate');
            })
            ->where('lang', app()->getLocale())
            ->orderBy($orderBy, $orderType)
            ->skip(($page - 1) * $take)
            ->take($take)->get();

        foreach ($packages as $item){
            $item->bad = false;
            $item = getMinPackage($item, 'list');
            $item->imgUrl = $item->pic;
            $destination = Destination::find($item->destId);
            if($destination == null)
                $item->bad = true;
            else {
                $item->url = route('show.package', ['slug' => $item->slug]);
                $item->destinationName = $destination->name;
                $item->destinationUrl = route('show.destination', ['slug'=>$destination->slug]);
            }

            if($item->sDate != null)
                $item->circleSDate = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d') . ' ' . Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
            else
                $item->circleSDate = __('Call Us');

            $item->money = number_format($item->money);
            $actv = Activity::find($item->mainActivityId);
            if($actv == null)
                $item->bad = true;
            else
                $item->activity = $actv->name;

            $item->season = __($item->season);
        }

        echo json_encode(['status' => 'ok', 'result' => $packages]);
        return;

    }

    public function subscribeStore(Request $request)
    {
        if(isset($request->first) && isset($request->last) && isset($request->email)){
            $sub = Subscribe::where('email', $request->email)->first();
            if($sub == null){
                $sub = new Subscribe();
                $sub->firstName = $request->first;
                $sub->lastName = $request->last;
                $sub->email = $request->email;
                $sub->save();

                echo 'ok';
            }
            else
                echo 'nok1';
        }
        else
            echo 'nok';

        return;
    }

}
