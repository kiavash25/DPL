<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Countries;
use App\models\Destination;
use App\models\Package;
use App\models\PackageActivityRelations;
use App\models\PackagePic;
use App\models\PackageTag;
use App\models\PackageTagRelation;
use App\models\Tags;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function listPackage()
    {
        return view('admin.package.listPackage');
    }

    public function newPackage()
    {
        $kind = 'new';
        $destinations = Destination::all()->groupBy('countryId');

        foreach ($destinations as $key => $item)
            $item->country = Countries::find($key);

        $activity = Activity::all();

        $allDestination = Destination::all();

        return view('admin.package.newPackage', compact(['kind', 'destinations', 'allDestination', 'activity']));
    }

    public function editPackage($id)
    {
        $package = Package::find($id);
        if($package == null)
            return redirect(route('admin.package.list'));

        $package->activities = PackageActivityRelations::where('packageId', $package->id)->pluck('activityId')->toArray();

        $tags = PackageTagRelation::where('packageId', $id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $package->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $package->tags = [];

        if($package->pic != null)
            $package->pic = asset('uploaded/packages/'. $id . '/' . $package->pic);

        $sideImage = PackagePic::where('packageId', $id)->get();
        foreach ($sideImage as $item)
            $item->pic = asset('uploaded/packages/'. $id . '/' . $item->pic);
        $package->sidePic = $sideImage;

        $kind = 'edit';
        $destinations = Destination::all()->groupBy('countryId');

        foreach ($destinations as $key => $item)
            $item->country = Countries::find($key);

        $activity = Activity::all();

        $allDestination = Destination::all();
        return view('admin.package.newPackage', compact(['kind', 'destinations', 'allDestination', 'activity', 'package']) );
    }

    public function storePackage(Request $request)
    {
        if(isset($request->name) && isset($request->id) && isset($request->lat) && isset($request->lng) && isset($request->destinationId) && isset($request->mainActivity)){

            if($request->id == 0){
                $pack = Package::where('name', $request->name)->where('destId', $request->destinationId)->first();
                if($pack != null) {
                    echo json_encode(['status' => 'nok2']);
                    return;
                }

                $pack = new Package();
            }
            else{
                $pack = Package::where('name', $request->name)->where('destId', $request->destinationId)->where('id' , '!=', $request->id)->first();
                if($pack != null) {
                    echo json_encode(['status' => 'nok2']);
                    return;
                }

                $pack = Package::find($request->id);
                if($pack == null){
                    echo json_encode(['status' => 'nok3']);
                    return;
                }
            }

            $pack->name = $request->name;
            $pack->slug = makeSlug($request->name);
            $pack->description = $request->description;
            $pack->destId = $request->destinationId;
            $pack->lat = $request->lat;
            $pack->lng = $request->lng;
            $pack->day = $request->day;
            $pack->season = $request->season;
            $pack->sDate = $request->sDate;
            $pack->eDate = $request->eDate;
            $pack->money = $request->cost;
            $pack->mainActivityId = $request->mainActivity;
            $pack->save();

            $tags = json_decode($request->tags);
            $query = '';
            foreach ($tags as $tag){
                $t = Tags::where('tag', $tag)->first();
                if($t == null){
                    $t = new Tags();
                    $t->tag = $tag;
                    $t->save();
                }

                if($query != '')
                    $query .= ' ,';
                $query .= '(Null, ' . $pack->id . ', ' . $t->id . ')';
            }

            PackageActivityRelations::where('packageId', $pack->id)->delete();
            foreach ($request->sideActivity as $item){
                if($item != $pack->mainActivityId){
                    $newAct = new PackageActivityRelations();
                    $newAct->packageId = $pack->id;
                    $newAct->activityId = $item;
                    $newAct->save();
                }
            }

            PackageTagRelation::where('packageId', $pack->id)->delete();
            if($query != '')
                \DB::select('INSERT INTO packageTagRelations (id, packageId, tagId) VALUES ' . $query);
            echo json_encode(['status' => 'ok', 'id' => $pack->id]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeImgPackage(Request $request)
    {
        if(isset($request->id) && isset($request->kind) && isset($request->pic) && $_FILES['pic'] && $_FILES['pic']['error'] == 0){
            $pack = Package::find($request->id);
            if($pack != null){
                $fileName = time() . $_FILES['pic']['name'];
                $location = __DIR__ . '/../../../public/uploaded/packages';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $fileName;

                $picResult = compressImage($_FILES['pic']['tmp_name'], $location, 80);
                if ($picResult) {
                    if($request->kind == 'mainPic') {
                        if ($pack->pic != null)
                            \File::delete('uploaded/packages/' . $request->id . '/' . $pack->pic);
                        $pack->pic = $fileName;
                        $pack->save();
                        echo json_encode(['status' => 'ok']);
                    }
                    else{
                        $sidePic = new PackagePic();
                        $sidePic->packageId = $pack->id;
                        $sidePic->pic = $fileName;
                        $sidePic->save();

                        echo json_encode(['status' => 'ok', 'id' => $sidePic->id]);
                    }
                }
                else
                    echo json_encode(['status' => 'nok2']);

            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteImgPackage(Request $request)
    {
        if(isset($request->id)){
            $pic = PackagePic::find($request->id);
            if($pic != null){
                \File::delete('uploaded/packages/' . $pic->packageId . '/' . $pic->pic);
                $pic->delete();
                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }


}
