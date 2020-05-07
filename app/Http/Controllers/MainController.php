<?php

namespace App\Http\Controllers;

use App\models\Activity;
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
use App\models\MainPageSlider;
use App\models\Package;
use App\models\PackageActivityRelations;
use App\models\PackagePic;
use App\models\PackageSideInfo;
use App\models\PackageTagRelation;
use App\models\PackageThumbnailsPic;
use App\models\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MainController extends Controller
{
    public function __construct()
    {
        $destCategory = DestinationCategory::all();
        foreach ($destCategory as $item) {
            $item->destination = Destination::where('categoryId', $item->id)->orderBy('name')->get();
            foreach ($item->destination as $dest){
                $titleId = DestinationCategoryTitleText::where('destId', $dest->id)->pluck('titleId')->toArray();
                $dest->titles = DestinationCategoryTitle::whereIn('id', $titleId)->pluck('name')->toArray();

                $dest->url = route('show.destination', ['categoryId' => $dest->categoryId, 'slug' => $dest->slug]);
            }
        }

        $today = Carbon::now()->format('Y-m-d');
        $activitiesList = Activity::all();
        foreach($activitiesList as $item){
            $item->packages = Package::where('mainActivityId', $item->id)
                                        ->where('showPack', 1)
                                        ->where(function ($query) {
                                            $today = Carbon::now()->format('Y-m-d');
                                            $query->where('sDate', '>', $today)
                                                ->orWhereNull('sDate');
                                        })
                                        ->orderBy('sDate')->get();

            foreach ($item->packages as $pack){
                $desti = Destination::find($pack->destId);
                $pack->url = route('show.package', ['destination' => $desti->slug, 'slug' => $pack->slug]);
            }
        }

        View::share(['destCategory' => $destCategory, 'activitiesList' => $activitiesList]);
    }

    public function mainPage()
    {

        $mainPageSlider = MainPageSlider::orderByDesc('showNumber')->get();
        foreach ($mainPageSlider as $item)
            $item->pic = asset('images/MainSliderPics/' . $item->pic);

        $destinationCategoryMain = DestinationCategory::get();
        foreach ($destinationCategoryMain as $categ) {
            $categ->destination = Destination::where('categoryId', $categ->id)->orderBy('name')->get(['id', 'name', 'slug', 'categoryId', 'pic', 'description']);
            foreach ($categ->destination as $dest){
                $dest->pic = asset('uploaded/destination/' . $dest->id . '/' . $dest->pic);
                $dest->url = route('show.destination', ['slug' => $dest->slug, 'categoryId' => $dest->categoryId]);
            }
        }


        $today = Carbon::now()->format('Y-m-d');
        $recentlyPackage = Package::where('showPack', 1)
                                    ->where(function ($query) {
                                        $today = Carbon::now()->format('Y-m-d');
                                        $query->where('sDate', '>', $today)
                                            ->orWhereNull('sDate');
                                    })
                                    ->orderBy('sDate')->take(5)->get();
        foreach ($recentlyPackage as $item)
            $item = getMinPackage($item);

//        $mapDestination = Destination::select(['id', 'slug', 'name', 'lat', 'lng', 'categoryId'])->get()->groupBy('categoryId');
//        foreach ($mapDestination as $key => $item) {
//            $categ = DestinationCategory::find($key);
//            if($categ->icon != null)
//                $mapIcon = asset('uploaded/MapIcon/' . $categ->icon);
//            else
//                $mapIcon = null;
//            foreach ($item as $it)
//                $it->mapIcon = $mapIcon;
//        }
//
//        $catId = DestinationCategory::get()->pluck('id')->toArray();
//        $catId = json_encode($catId);
//        return \view('main.mainPage', compact(['activities', 'mapDestination', 'catId', 'destinationCategoryMain']));
        return \view('main.mainPage', compact(['destinationCategoryMain', 'recentlyPackage', 'mainPageSlider']));
    }

    public function aboutUs()
    {
        return \view('main.aboutUs');
    }

    public function showDestination($categoryId, $slug)
    {
        $kind = 'destination';
        $category = DestinationCategory::find($categoryId);
        if($category == null)
            return redirect(url('/'));

        $content = Destination::where('slug', $slug)->where('categoryId', $category->id)->first();
        if($content == null)
            return redirect(url('/'));

        $content->category = $category;
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

        $loc = 'uploaded/destination/' . $content->id;
        $slip = [];
        $sliderPics =  DestinationPic::where('destId', $content->id)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'id' => $item->id
            ]);
        }
        if($content->pic != null) {
            array_unshift($slip, (object)[
                'pic' => getKindPic($loc, $content->pic, ''),
                'slide' =>  getKindPic($loc, $content->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $content->pic, 'min'),
                'id' => 0
            ]);
        }
        $content->slidePic = $slip;

        $today = Carbon::now()->format('Y-m-d');
        $packages = Package::where('showPack', 1)
                            ->where('destId', $content->id)
                            ->where(function ($query) {
                                $today = Carbon::now()->format('Y-m-d');
                                $query->where('sDate', '>', $today)
                                    ->orWhereNull('sDate');
                            })
                            ->orderBy('sDate')->take(5)->get();
        foreach ($packages as $item)
            $item = getMinPackage($item);
        $content->packages = $packages;

        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $content->slug]);

        if($content->video != null)
            $content->video = asset('uploaded/destination/'. $content->id . '/' . $content->video);
        if($content->podcast != null)
            $content->podcast = asset('uploaded/destination/'. $content->id . '/' . $content->podcast);


        $guidance = ['value1' => $content->category->name, 'value1Url' => route('show.category', ['categoryName' => $content->category->name]),
                    'value2' => $content->name, 'value2Url' => route('show.destination', ['city' => $content->categoryId, 'slug' => $content->slug])];

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function showPackage($destination, $slug)
    {
        $kind = 'package';
        $destination = Destination::where('slug', $destination)->first();
        if($destination == null)
            return redirect(url('/'));


        $content = Package::where('destId', $destination->id)->where('slug', $slug)->first();
        if($content == null)
            return redirect(url('/'));

        $destination->city = City::find($destination->cityId);
        $destination->category = DestinationCategory::find($destination->categoryId);

        $content->destination = $destination;

        $content->mainActivity = Activity::find($content->mainActivityId);
        $content->mainActivity->icon = asset('uploaded/activityIcons/' . $content->mainActivity->icon);

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

        $loc = 'uploaded/packages/' . $content->id ;
        $slip = [];
        $sliderPics =  PackagePic::where('packageId', $content->id)->get();
        foreach ($sliderPics as $item){
            array_push($slip, (object)[
                'pic' => getKindPic($loc, $item->pic, ''),
                'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                'id' => $item->id
            ]);
        }
        if($content->pic != null) {
            array_unshift($slip, (object)[
                'pic' => getKindPic($loc, $content->pic, ''),
                'slide' =>  getKindPic($loc, $content->pic, 'slide'),
                'thumbnail' =>  getKindPic($loc, $content->pic, 'min'),
                'id' => 0
            ]);
        }
        $content->slidePic = $slip;

        $content->thumbnails = PackageThumbnailsPic::where('packageId', $content->id)->get();
        foreach ($content->thumbnails as $item){
            $item->thumbnail = asset('uploaded/packages/' . $content->id . '/thumbnail_' . $item->pic);
            $item->pic = asset('uploaded/packages/' . $content->id . '/' . $item->pic);
        }

        $today = Carbon::now()->format('Y-m-d');
        $pac = Package::where('id', '!=', $content->id)
                        ->where('showPack', 1)
                        ->where(function ($query) {
                            $today = Carbon::now()->format('Y-m-d');
                            $query->where('sDate', '>', $today)
                                ->orWhereNull('sDate');
                        })
                        ->where('destId', $content->destination->id)
                        ->orderBy('sDate')->take(5)->get();
        foreach ($pac as $item)
            $item = getMinPackage($item);

        $content->packages = $pac;

        $content->brochure = asset('uploaded/packages/' . $content->id . '/' . $content->brochure);

        $content->money = commaMoney($content->money);

        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $destination->slug]);

        $guidance = ['value1' => $destination->category->name, 'value1Url' => route('show.category', ['categoryName' => $destination->category->name]),
            'value2' => $destination->name, 'value2Url' => route('show.destination', ['categoryId' => $destination->categoryId, 'slug' => $destination->slug]),
            'value3' => $content->name, 'value3Url' => $content->id];

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function showCategory($categoryName)
    {
        $kind = 'category';
        $content = DestinationCategory::where('name', $categoryName)->first();
        if($content == null)
            return redirect()->back();
        else{
            $mainLoc = __DIR__  . '/../../../public/uploaded/destination/category/' . $content->id;

            $loc = 'uploaded/destination/category/' . $content->id;
            $slip = [];
            $sliderPics =  DestinationCategoryPic::where('categoryId', $content->id)->get();
            foreach ($sliderPics as $item){
                array_push($slip, (object)[
                    'pic' => getKindPic($loc, $item->pic, ''),
                    'slide' =>  getKindPic($loc, $item->pic, 'slide'),
                    'thumbnail' =>  getKindPic($loc, $item->pic, 'min'),
                    'id' => $item->id
                ]);
            }
            if($content->pic != null) {
                array_unshift($slip, (object)[
                    'pic' => getKindPic($loc, $content->pic, ''),
                    'slide' =>  getKindPic($loc, $content->pic, 'slide'),
                    'thumbnail' =>  getKindPic($loc, $content->pic, 'min'),
                    'id' => 0
                ]);
            }
            $content->slidePic = $slip;

            if(count($content->slidePic) == 0){
                $slidePic = [];
                $destIds = Destination::where('categoryId', $content->id)->select(['id', 'pic'])->inRandomOrder()->take(5)->get();
                foreach ($destIds as $de){
                    $loc = 'uploaded/destination/' . $de->id;
                    array_push($slidePic, (object)[
                        'pic' => getKindPic($loc, $de->pic, ''),
                        'slide' =>  getKindPic($loc, $de->pic, 'slide'),
                        'thumbnail' =>  getKindPic($loc, $de->pic, 'min'),
                        'id' => $de->id
                    ]);
                }
            }
            $content->slidePic = $slidePic;

            $content->titles = DestinationCategoryTitle::where('categoryId', $content->id)->get();
            if(is_file($mainLoc . '/' .$content->icon))
                $content->icon = asset('uploaded/destination/category/' . $content->id . '/' . $content->icon);
            else
                $content->icon = null;

            if($content->video != null)
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
                $l = 'uploaded/destination/' . $item->id;
                $item->minPic = getKindPic($l, $item->pic, 'min');
                $item->description = strip_tags($item->description);
                $item->url = route('show.destination', ['categoryId' => $content->id, 'slug' => $item->slug]);

                $p = Package::where('destId', $item->id)
                            ->where('showPack', 1)
                            ->where(function ($query) {
                                $today = Carbon::now()->format('Y-m-d');
                                $query->where('sDate', '>', $today)
                                    ->orWhereNull('sDate');
                            })->orderBy('day', 'DESC')->take(1)->get();
                if(count($p) != 0) {
                    $p = getMinPackage($p[0]);
                    array_push($packages, $p);
                }

//                average lat and lng for map center
                if($item->lat != 0 && $item->lng != 0){
                    $lat += (integer)$item->lat;
                    $lng += (integer)$item->lng;
                    $count++;
                }
            }
            $content->destinations = $destinations;
            $content->packages = $packages;

            $lat /= $count;
            $lng /= $count;
            $content->mapCenter = ['lat' => $lat, 'lng' => $lng];

            $kind = 'category';
            $guidance = ['value1' => $content->name, 'value1Url' => route('show.category', ['categoryName' => $content->name])];

            return \view('main.content', compact(['content', 'kind', 'guidance']));
        }
    }

    public function beforeList(Request $request)
    {
        if(isset($request->season)){
            session(['season' => $request->season]);
        }
        if(isset($request->activity)){
            $activit = Activity::where('name', $request->activity)->first();
            if($activit != null)
                session(['activityId' => $activit->id]);
        }
        if(isset($request->destination)){
            $destId = Destination::where('name', $request->destination)->first();
            if($destId != null)
                session(['destId' => $destId->id]);
        }

        return redirect(route('show.list', ['kind' => 'mainSearch', 'value1' => 'all']));
    }

    public function list($kind, $value1)
    {
        if($kind == 'destination'){
            $category = DestinationCategory::where('name', $value1)->first();
            if($category == null)
                return redirect(url('/'));

            $today = Carbon::now()->format('Y-m-d');
            $destinations = Destination::where('categoryId', $category->id)->get();
            foreach ($destinations as $item) {
                $item->pic = asset('uploaded/destination/' . $item->id . '/' . $item->pic);
                $item->package = Package::where('destId', $item->id)
                                        ->where('showPack', 1)
                                        ->where(function ($query) {
                                            $today = Carbon::now()->format('Y-m-d');
                                            $query->where('sDate', '>', $today)
                                                ->orWhereNull('sDate');
                                        })->count();
            }

            $title = 'List of ' . $category->name . ' category destinations';
            $guidance = ['value1' => $category->name, 'value1Url' => '#'];

            return \view('main.list', compact(['kind', 'destinations', 'guidance', 'title']));
        }
        else{
            $guidance = [];
            $destination = 'all';
            $season = 'all';
            $tag = 'all';
            $activity = 'all';
            switch ($kind){
                case 'activity':
                    $activity = Activity::where('name', $value1)->first();
                    if ($activity != null) {
                        $guidance = ['value1' => 'Activity', 'value1Url' => '#',
                            'value2' => $activity->name, 'value2Url' => '#'];
                        $title = $activity->name . ' Package List';
                        $activity = $activity->id;
                    }
                    else {
                        $guidance = ['value1' => 'Activity', 'value1Url' => '#'];
                        $title = 'All Activity List';
                    }
                    break;
                case 'destinationPackage':
                    $destination = Destination::where('slug', $value1)->first();
                    if ($destination != null) {
                        $ci = City::find($destination->cityId);
                        $guidance = ['value1' => 'Destination', 'value1Url' => route('show.list', ['kind' => 'destination', 'value1' => 'All']),
                            'value2' => $destination->name, 'value2Url' => route('show.destination', ['categoryId' => $destination->categoryId, 'slug' => $destination->slug])];
                        $title = $destination->name . ' Package List';
                        $destination = $destination->id;
                    }
                    else {
                        $guidance = ['value1' => 'Destination', 'value1Url' => '#'];
                        $title = 'All Destination List';
                    }
                    break;
                case 'tags':
                    $tag = $value1;
                    $title = $value1 . ' Tag Package List';
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
                                'value2' => $destination->name, 'value2Url' => route('show.destination', ['categoryId' => $destination->categoryId, 'slug' => $destination->slug])];
                            $title = $destination->name . ' Package List';
                            $destination = $destination->id;
                        }
                        session()->forget('destId');
                    }

                    if(session('season')){
                        $season = session('season');
                        session()->forget('season');
                    }

                    if(session('activityId')){
                        $activityId = Activity::find(session('activityId'));
                        if ($activityId != null)
                            $activity = $activityId->id;

                        session()->forget('activityId');
                    }

                    if(count($guidance) == 0) {
                        $guidance = ['value1' => 'All Packages', 'value1Url' => '#'];
                        $title = 'All Package List';
                    }

                    break;
            }

            return \view('main.list', compact(['kind', 'activity', 'destination', 'season', 'guidance', 'title', 'tag']));
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
        $sqlQuery .= ' money >= ' . $money[0] . ' AND money < ' . $money[1];


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
                $query->where('sDate', '>', $today)
                        ->orWhereNull('sDate');
            })
            ->orderBy($orderBy, $orderType)
            ->skip(($page - 1) * $take)
            ->take($take)->get();

        foreach ($packages as $item){
            $item->bad = false;
            $item->imgUrl = asset('uploaded/packages/' . $item->id . '/' . $item->pic);
            $destination = Destination::find($item->destId);
            if($destination == null)
                $item->bad = true;
            else {
                $item->url = route('show.package', ['destination' => $destination->slug, 'slug' => $item->slug]);
                $item->destinationName = $destination->name;
                $item->destinationUrl = route('show.destination', ['categoryId' => $destination->categoryId, 'slug'=>$destination->slug]);
            }

            if($item->sDate != null)
                $item->circleSDate = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d') . ' ' . Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
            else
                $item->circleSDate = 'Call Us';

            $item->money = commaMoney($item->money);
            $actv = Activity::find($item->mainActivityId);
            if($actv == null)
                $item->bad = true;
            else
                $item->activity = $actv->name;
        }

        echo json_encode(['status' => 'ok', 'result' => $packages]);
        return;

    }

}
