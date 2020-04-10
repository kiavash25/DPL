<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\City;
use App\models\Continents;
use App\models\Countries;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\DestinationCategoryTitle;
use App\models\DestinationCategoryTitleText;
use App\models\DestinationPic;
use App\models\DestinationTagRelation;
use App\models\Package;
use App\models\PackageActivityRelations;
use App\models\PackagePic;
use App\models\PackageTagRelation;
use App\models\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MainController extends Controller
{
    public function __construct()
    {
        $destCategory = DestinationCategory::all();
        foreach ($destCategory as $item)
            $item->destination = Destination::where('categoryId', $item->id)->orderBy('name')->get();

        $activitiesList = Activity::all();

        View::share(['destCategory' => $destCategory, 'activitiesList' => $activitiesList]);
    }

    public function mainPage()
    {
        $mapDestination = Destination::all();
        foreach ($mapDestination as $item)
            $item->category = DestinationCategory::find($item->categoryId);


        return \view('main.mainPage', compact(['activities', 'mapDestination']));
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

        if($content->pic != null)
            $content->pic = asset('uploaded/destination/'. $content->id . '/' . $content->pic);

        $sideImage = DestinationPic::where('destId', $content->id)->get();
        foreach ($sideImage as $item)
            $item->pic = asset('uploaded/destination/'. $content->id . '/' . $item->pic);
        $content->sidePic = $sideImage;

        $today = Carbon::now()->format('Y-m-d');
        $packages = Package::where('sDate', '>', $today)->where('destId', $content->id)->orderBy('sDate')->take(5)->get();
        foreach ($packages as $item) {
            $item->mainActivity = Activity::find($item->mainActivityId);
            $item->pic = asset('uploaded/packages/' . $item->id . '/' . $item->pic);
            $item->sD = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d');
            $item->sM = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
            $item->url = route('show.package', ['destination' => $content->slug, 'slug' => $item->slug]);
        }
        $content->packages = $packages;

        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $content->slug]);

        if($content->video != null)
            $content->video = asset('uploaded/destination/'. $content->id . '/' . $content->video);
        if($content->podcast != null)
            $content->podcast = asset('uploaded/destination/'. $content->id . '/' . $content->podcast);


        $guidance = ['value1' => $content->category->name, 'value1Url' => route('show.list', ['kind' => 'destination', 'value1' => $content->category->name]),
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

        $tags = PackageTagRelation::where('packageId', $content->id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $content->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $content->tags = [];

        if($content->pic != null)
            $content->pic = asset('uploaded/packages/'. $content->id . '/' . $content->pic);

        $sideImage = PackagePic::where('packageId', $content->id)->get();
        foreach ($sideImage as $item)
            $item->pic = asset('uploaded/packages/'. $content->id . '/' . $item->pic);
        $content->sidePic = $sideImage;

        $today = Carbon::now()->format('Y-m-d');
        $pac = Package::where('id', '!=', $content->id)->where('sDate', '>', $today)->where('destId', $content->destination->id)->orderBy('sDate')->take(5)->get();
        foreach ($pac as $item) {
            $item->mainActivity = Activity::find($item->mainActivityId);
            $item->pic = asset('uploaded/packages/' . $item->id . '/' . $item->pic);
            $item->sD = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d');
            $item->sM = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
            $item->url = route('show.package', ['destination' => $destination->slug, 'slug' => $item->slug]);
        }
        $content->packages = $pac;

        $content->money = commaMoney($content->money);

        $content->packageListUrl = route('show.list', ['kind' => 'destinationPackage', 'value1' => $destination->slug]);

        $guidance = ['value1' => $destination->category->name, 'value1Url' => '#',
            'value2' => $destination->name, 'value2Url' => route('show.destination', ['categoryId' => $destination->categoryId, 'slug' => $destination->slug]),
            'value3' => $content->name, 'value3Url' => $content->id];

        return view('main.content', compact(['content', 'kind', 'guidance']));
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
                $item->package = Package::where('destId', $item->id)->where('sDate', '>', $today)->count();
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
                $packages = Package::where('sDate', '>', $today)->whereRaw($sqlQuery)->orderBy('sDate', 'ASC')->skip(($page - 1) * $take)->take($take)->get();
                break;
            case 'minConst':
                $packages = Package::where('sDate', '>', $today)->whereRaw($sqlQuery)->orderBy('money', 'ASC')->skip(($page - 1) * $take)->take($take)->get();
                break;
            case 'maxConst':
                $packages = Package::where('sDate', '>', $today)->whereRaw($sqlQuery)->orderBy('money', 'DESC')->skip(($page - 1) * $take)->take($take)->get();
                break;
            case 'minDay':
                $packages = Package::where('sDate', '>', $today)->whereRaw($sqlQuery)->orderBy('day', 'ASC')->skip(($page - 1) * $take)->take($take)->get();
                break;
            case 'maxDay':
                $packages = Package::where('sDate', '>', $today)->whereRaw($sqlQuery)->orderBy('day', 'DESC')->skip(($page - 1) * $take)->take($take)->get();
                break;
        }

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

            $item->circleSDate = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d') . ' ' . Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
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
