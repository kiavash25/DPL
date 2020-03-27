<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\City;
use App\models\Continents;
use App\models\Countries;
use App\models\Destination;
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
        $continentsList = Continents::all();
        foreach ($continentsList as $item)
            $item->countries = Countries::where('continent_code', $item->code)->get();

//        $pack = Package::select(['destId'])->groupBy('destId')->pluck('destId')->toArray();
//        $desst = Destination::whereIn('id', $pack)->groupBy('countryId')->pluck('countryId')->toArray();
//        $conutr = Countries::whereIn('id', $desst)->groupBy('continent_code')->pluck('continent_code')->toArray();
//        $continentsList = Continents::whereIn('code', $conutr)->get();
//        foreach ($continentsList as $item)
//            $item->countries = Countries::where('continent_code', $item->code)->whereIn('id', $desst)->get();
        View::share(['continents' => $continentsList]);
    }

    public function mainPage()
    {
        return \view('main.mainPage');
    }

    public function showDestination($country, $slug)
    {
        $kind = 'destination';

        $country = Countries::where('name', $country)->first();
        $content = Destination::where('slug', $slug)->where('countryId', $country->id)->first();
        $tags = DestinationTagRelation::where('destId', $content->id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $content->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $content->tags = [];

        $city = City::find($content->cityId);
        if($city != null)
            $content->city = $city->name;
        else
            $content->city = '';

        $content->country = $country;

        if($content->pic != null)
            $content->pic = asset('uploaded/destination/'. $content->id . '/' . $content->pic);

        $sideImage = DestinationPic::where('destId', $content->id)->get();
        foreach ($sideImage as $item)
            $item->pic = asset('uploaded/destination/'. $content->id . '/' . $item->pic);
        $content->sidePic = $sideImage;

        $today = Carbon::now()->format('Y-m-d');
        $packages = Package::where('sDate', '>', $today)->where('destId', $content->id)->orderBy('sDate')->take(10)->get();
        foreach ($packages as $item) {
            $item->mainActivity = Activity::find($item->mainActivityId);
            $item->pic = asset('uploaded/packages/' . $item->id . '/' . $item->pic);
            $item->sD = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d');
            $item->sM = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
            $item->url = route('show.package', ['destination' => $content->slug, 'slug' => $item->slug]);
        }
        $content->packages = $packages;

        $continents = Continents::where('code', $country->continent_code)->first();


        $guidance = ['continents' => $continents->name, 'country' => $country->name, 'countryId' => $country->id,
                    'city' => $content->city, 'cityId' => $city->id,
                    'destination' => $content->name, 'destinationSlug' => $content->slug];

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
        $destination->country = Countries::find($destination->countryId);

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
        $pac = Package::all();
//        $pac = Package::where('id', '!=', $content->id)->where('sDate', '>', $today)->where('destId', $content->destination->id)->orderBy('sDate')->take(10)->get();
        foreach ($pac as $item) {
            $item->mainActivity = Activity::find($item->mainActivityId);
            $item->pic = asset('uploaded/packages/' . $item->id . '/' . $item->pic);
            $item->sD = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('d');
            $item->sM = Carbon::createFromFormat('Y-m-d', $item->sDate)->format('M');
            $item->url = route('show.package', ['destination' => $destination->slug, 'slug' => $item->slug]);
        }
        $content->packages = $pac;

        $content->money = commaMoney($content->money);

        $continents = Continents::where('code', $destination->country->continent_code)->first();

        $guidance = ['continents' => $continents->name, 'country' => $destination->country->name, 'countryId' => $destination->country->id,
            'city' => $destination->city->name, 'cityId' => $destination->city->id,
            'destination' => $destination->name, 'destinationSlug' => $destination->slug,
            'package' => $content->name, 'pakcageId' => $content->id];

        return view('main.content', compact(['content', 'kind', 'guidance']));
    }

    public function list()
    {
        $kind = 'package';

        return \view('main.list', compact(['kind']));
    }
}
