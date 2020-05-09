<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\City;
use App\models\Countries;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\Package;
use App\models\PackageActivityRelations;
use App\models\PackageMoreInfo;
use App\models\PackageMoreInfoRelation;
use App\models\PackagePic;
use App\models\PackageSideInfo;
use App\models\PackageTag;
use App\models\PackageTagRelation;
use App\models\PackageThumbnailsPic;
use App\models\Tags;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Element;

class PackageController extends Controller
{
    public function listPackage()
    {
        $packages = Package::all();
        foreach ($packages as $item){
            $item->destination = Destination::find($item->destId);
            $item->activity = Activity::find($item->mainActivityId);
        }

        return view('admin.package.listPackage', compact(['packages']));
    }

    public function newPackage()
    {
        $kind = 'new';
        $destinations = Destination::all()->groupBy('categoryId');
        foreach ($destinations as $key => $item)
            $item->category = DestinationCategory::find($key);

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
        $destinations = Destination::all()->groupBy('categoryId');
        foreach ($destinations as $key => $item)
            $item->category = DestinationCategory::find($key);

        $package->thumbnail = PackageThumbnailsPic::where('packageId', $package->id)->get();
        foreach ($package->thumbnail as $item)
            $item->pic = asset('uploaded/packages/' . $package->id . '/thumbnail_' . $item->pic);

        $package->sideInfos = PackageSideInfo::where('packageId', $package->id)->get();
        foreach ($package->sideInfos as $side)
            $side->icon = asset('uploaded/packages/' . $package->id . '/' . $side->icon);



        $activity = Activity::all();

        $allDestination = Destination::all();
        return view('admin.package.newPackage', compact(['kind', 'destinations', 'allDestination', 'activity', 'package']) );
    }

    public function storePackage(Request $request)
    {
        if(isset($request->name) && isset($request->id) && isset($request->lat) && isset($request->lng) && isset($request->destinationId) && isset($request->code) && isset($request->mainActivity)){

            if($request->id == 0){
                $pack = Package::where('name', $request->name)->where('destId', $request->destinationId)->first();
                if($pack != null) {
                    echo json_encode(['status' => 'nok2']);
                    return;
                }

                $codePack = Package::where('code', $request->code)->first();
                if($codePack != null) {
                    echo json_encode(['status' => 'nok9']);
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

                $codePack = Package::where('code', $request->code)->where('id' , '!=', $request->id)->first();
                if($codePack != null) {
                    echo json_encode(['status' => 'nok9']);
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
            $pack->code = $request->code;
            $pack->season = $request->season;
            $pack->sDate = $request->sDate;
            $pack->eDate = $request->eDate;
            $pack->money = $request->cost;
            $pack->level = $request->level;
            $pack->showPack = $request->showPack;
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
            if(isset($request->sideActivity) && count($request->sideActivity) > 0) {
                foreach ($request->sideActivity as $item) {
                    if ($item != $pack->mainActivityId) {
                        $newAct = new PackageActivityRelations();
                        $newAct->packageId = $pack->id;
                        $newAct->activityId = $item;
                        $newAct->save();
                    }
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

                $image = $request->file('pic');
                $dirs = 'uploaded/packages/' . $request->id;

                if($request->kind == 'thumbnail'){
                    $size = [
                        [
                            'width' => null,
                            'height' => 100,
                            'name' => 'thumbnail_',
                            'destination' => $dirs
                        ]
                    ];
                }
                else{
                    $size = [
                        [
                            'width' => null,
                            'height' => 250,
                            'name' => 'min_',
                            'destination' => $dirs
                        ],
                        [
                            'width' => null,
                            'height' => 400,
                            'name' => 'list_',
                            'destination' => $dirs
                        ],
                        [
                            'width' => null,
                            'height' => 500,
                            'name' => 'slide_',
                            'destination' => $dirs
                        ]
                    ];
                }

                $fileName = resizeImage($image, $size);

                $location .= '/' . $fileName;

                $picResult = storeImage($_FILES['pic']['tmp_name'], $location);
                if ($picResult) {
                    if($request->kind == 'mainPic') {
                        if ($pack->pic != null)
                            \File::delete('uploaded/packages/' . $request->id . '/' . $pack->pic);
                        $pack->pic = $fileName;
                        $pack->save();
                        echo json_encode(['status' => 'ok']);
                    }
                    else if($request->kind == 'thumbnail'){
                        $thumbnail = new PackageThumbnailsPic();
                        $thumbnail->packageId = $pack->id;
                        $thumbnail->pic = $fileName;
                        $thumbnail->save();

                        echo json_encode(['status' => 'ok', 'id' => $thumbnail->id]);
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
        if(isset($request->id) && isset($request->kind)){

            if($request->kind == 'side') {
                $pic = PackagePic::find($request->id);
                if ($pic != null) {
                    \File::delete('uploaded/packages/' . $pic->packageId . '/slide_' . $pic->pic);
                    \File::delete('uploaded/packages/' . $pic->packageId . '/list_' . $pic->pic);
                    \File::delete('uploaded/packages/' . $pic->packageId . '/min_' . $pic->pic);
                    \File::delete('uploaded/packages/' . $pic->packageId . '/' . $pic->pic);
                    $pic->delete();
                    echo json_encode(['status' => 'ok']);
                } else
                    echo json_encode(['status' => 'nok1']);
            }
            else{
                $pic = PackageThumbnailsPic::find($request->id);
                if ($pic != null) {
                    \File::delete('uploaded/packages/' . $pic->packageId . '/thumbnail_' . $pic->pic);
                    \File::delete('uploaded/packages/' . $pic->packageId . '/' . $pic->pic);
                    $pic->delete();
                    echo json_encode(['status' => 'ok']);
                } else
                    echo json_encode(['status' => 'nok1']);
            }
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deletePackage(Request $request)
    {
        if(isset($request->id)){
            $package = Package::find($request->id);
            if($package != null){
                PackageTagRelation::where('packageId', $package->id)->delete();
                PackageActivityRelations::where('packageId', $package->id)->delete();
                PackagePic::where('packageId', $package->id)->delete();
                PackageThumbnailsPic::where('packageId', $package->id)->delete();

                $loc = __DIR__ .'/../../../public/uploaded/packages/' . $package->id;
                emptyFolder($loc);

                $package->delete();
                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeVideoAudioPackage(Request $request)
    {
        if(isset($request->id) && $_FILES['file'] && $_FILES['file']['error'] == 0){
            $packe = Package::find($request->id);
            if($packe != null){
                $fileName = $_FILES['file']['name'];
                $location = __DIR__ . '/../../../public/uploaded/packages';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);

                if(is_file($location . '/' . $fileName))
                    $fileName = time() . $fileName;

                $location .= '/' . $fileName;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                    if($request->kind == 'audio'){
                        if($packe->podcast != null)
                            \File::delete('uploaded/packages/'. $packe->id . '/' . $packe->podcast);

                        $packe->podcast = $fileName;
                        $packe->save();

                        $videoUrl = asset('uploaded/packages/' . $packe->id . '/' . $packe->podcast);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
                    }
                    else if($request->kind == 'video'){
                        if($packe->video != null)
                            \File::delete('uploaded/packages/'. $packe->id . '/' . $packe->video);

                        $packe->video = $fileName;
                        $packe->save();

                        $videoUrl = asset('uploaded/packages/' . $packe->id . '/' . $packe->video);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
                    }
                    else if($request->kind == 'brochure'){
                        if($packe->brochure != null)
                            \File::delete('uploaded/packages/'. $packe->id . '/' . $packe->brochure);

                        $packe->brochure = $fileName;
                        $packe->save();

                        $brochureUrl = asset('uploaded/packages/' . $packe->id . '/' . $packe->brochure);
                        echo json_encode(['status' => 'ok', 'url' => $brochureUrl, 'name' => $fileName]);
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

    public function storeSideInfo(Request $request)
    {
        if(isset($request->id) && isset($request->icon) && isset($request->text) && isset($request->packageId)){
            if($request->id == 0)
                $sideInfo = new PackageSideInfo();
            else
                $sideInfo = PackageSideInfo::find($request->id);

            $sideInfo->packageId = $request->packageId;
            $sideInfo->text = $request->text;

            if(isset($_FILES['icon']) && $_FILES['icon']['error'] == 0){
                $location = __DIR__ . '/../../../public/uploaded/packages';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->packageId;
                if(!file_exists($location))
                    mkdir($location);

                $image = $request->file('icon');
                $dirs = 'uploaded/packages/' . $request->packageId;
                $size = [
                    [
                        'width' => 50,
                        'height' => 50,
                        'name' => '',
                        'destination' => $dirs
                    ]
                ];
                $fileName = resizeImage($image, $size);
                if(isset($sideInfo->icon) && $sideInfo->icon != null)
                    \File::delete('uploaded/packages/' . $request->packageId . '/' . $sideInfo->icon);

                $sideInfo->icon = $fileName;
            }
            $sideInfo->save();

            $iconUrl = asset('uploaded/packages/' . $request->packageId . '/' . $sideInfo->icon);

            echo json_encode(['status' => 'ok', 'result' => [
                    'id' => $sideInfo->id,
                    'icon' => $iconUrl,
                    'text' => $sideInfo->text
                ]
            ]);
        }
        else
            echo json_encode(['stutus' => 'nok']);

        return;
    }

    public function deleteSideInfo(Request $request)
    {
        if(isset($request->id)){
            $sideInfo = PackageSideInfo::find($request->id);
            if($sideInfo != null){
                \File::delete('uploaded/packages/' . $sideInfo->packageId . '/' . $sideInfo->icon);
                $sideInfo->delete();
                echo  json_encode(['status' => 'ok']);
            }
            else
                echo  json_encode(['status' => 'nok1']);
        }
        else
            echo  json_encode(['status' => 'nok']);

        return;
    }

    public function moreInfoTitlePackage()
    {
        $moreInfo = PackageMoreInfo::all();
        $moreInfoCallVenture = PackageMoreInfo::where('category', 'callventureDetail')->get();
        $moreInfoNature = PackageMoreInfo::where('category', 'neutralDetail')->get();
        return view('admin.package.moreInfoTitle', compact(['moreInfo', 'moreInfoCallVenture', 'moreInfoNature']));
    }

    public function storeMoreInfoTitlePackage(Request $request)
    {
        if(isset($request->id) && isset($request->name)){
            $check = PackageMoreInfo::where('name', $request->name)->where('category', $request->category)->first();
            if($check != null){
                echo json_encode(['status' => 'nok1']);
                return;
            }
            if($request->id == 0)
                $moreInfo = new PackageMoreInfo();
            else
                $moreInfo = PackageMoreInfo::find($request->id);

            $moreInfo->name = $request->name;
            $moreInfo->category = $request->category;
            $moreInfo->save();

            echo json_encode(['status' => 'ok', 'id' => $moreInfo->id, 'name' => $moreInfo->name, 'category' => $moreInfo->category]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteMoreInfoTitlePackage(Request $request)
    {
        if(isset($request->id)){
            $moreInfo = PackageMoreInfo::find($request->id);
            if($moreInfo != null){
                $text = PackageMoreInfoRelation::where('moreInfoId', $moreInfo->id)->get();
                foreach ($text as $item){
                    $location = __DIR__ .'/../../../public/uploaded/packages/' . $item->packageId . '/moreInfo_' . $item->moreInfoId;
                    emptyFolder($location);

                    $item->delete();
                }

                $moreInfo->delete();
                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function moreInfoText($id)
    {
        $moreInfo = PackageMoreInfo::all();
        $moreInfoCallVenture = PackageMoreInfo::where('category', 'callventureDetail')->get();
        $moreInfoNature = PackageMoreInfo::where('category', 'neutralDetail')->get();

        $package = Package::find($id);
        if($package == null)
            return redirect(route('admin.package.list'));

        foreach ($moreInfo as $item)
            $item->text = PackageMoreInfoRelation::where('packageId', $package->id)->where('moreInfoId', $item->id)->first();

        return view('admin.package.moreInfoText', compact(['moreInfoCallVenture', 'moreInfoNature', 'moreInfo', 'package']));
    }

    public function storeMoreInfoTextPackage(Request $request)
    {
        if(isset($request->packageId) && isset($request->value) && isset($request->titleId)){
            $desc = PackageMoreInfoRelation::where('packageId', $request->packageId)->where('moreInfoId', $request->titleId)->first();
            if($desc == null)
                $desc = new PackageMoreInfoRelation();

            $desc->packageId = $request->packageId;
            $desc->moreInfoId = $request->titleId;
            $desc->text = $request->value;
            $desc->save();

            $location = __DIR__ . '/../../../public/uploaded/packages/' . $desc->packageId . '/moreInfo_' . $desc->moreInfoId . '/';
            if (file_exists($location)) {
                $files = scandir($location);
                foreach ($files as $item) {
                    if (is_file($location . '/' . $item)) {
                        if (strpos($request->value, $item) === false) {
                            unlink($location . '/' . $item);
                        }
                    }
                }
            }

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeImgMoreInfoTextPackage(Request $request)
    {
        $data = json_decode($request->data);
        $packageId = $data[0];
        $moreInfoId = $data[1];

        if( $_FILES['file'] && $_FILES['file']['error'] == 0){
            $package = Package::find($packageId);
            $moreInfo = PackageMoreInfo::find($moreInfoId);
            if($package != null && $moreInfo != null){
                $location = __DIR__ . '/../../../public/uploaded/packages/' . $package->id;
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/moreInfo_' . $moreInfo->id;
                if(!file_exists($location))
                    mkdir($location);

                $image = $request->file('file');
                $dirs = 'uploaded/packages/' . $package->id . '/moreInfo_' . $moreInfo->id;
                $size = [
                    [
                        'width' => 1200,
                        'height' => null,
                        'name' => '',
                        'destination' => $dirs
                    ]
                ];
                $fileName = resizeImage($image, $size);

                if($fileName != 'error')
                    echo json_encode(['url' => asset('uploaded/packages/' . $package->id . '/moreInfo_' . $moreInfo->id . '/' . $fileName)]);
                else
                    echo false;
            }
            else
                echo false;
        }
        else
            echo json_encode(['error' => true]);

        return;
    }

}
