<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\ActivityPic;
use App\models\ActivityTitle;
use App\models\City;
use App\models\Destination;
use App\models\Language;
use App\models\Package;
use App\models\PackageActivityRelations;
use App\models\PackageMoreInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use test\Mockery\CallableSpyTest;

class ActivityController extends Controller
{
    public function __construct()
    {
        $langs = Language::all();
        \View::share(['langs' => $langs]);
    }

    public function listActivity()
    {
        $lng = app()->getLocale();
        $activity = Activity::where('lang', $lng)->get();
        foreach ($activity as $item){
            if($item->parent != 0) {
                $parent = Activity::find($item->parent);
                if($parent == null){
                    $item->parent= 0;
                    $item->save();

                    $item->parent = '';
                }
                else
                    $item->parent = $parent->name;
            }
            else
                $item->parent = '';
        }

        return view('admin.activity.listActivity', compact(['activity']));
    }

    public function newActivity()
    {
        $lng = app()->getLocale();
        $parent = Activity::where('parent', 0)->where('lang', $lng)->get();
        $sourceParent = Activity::where('lang', 'en')->get();

        return view('admin.activity.newActivity', compact(['parent', 'sourceParent']));
    }

    public function editActivity($id)
    {
        $lang = app()->getLocale();

        $activity = Activity::where('id', $id)->where('lang', $lang)->first();
        if ($activity == null)
            return redirect(route('admin.activity.list'));

        if ($activity->icon != null)
            $activity->icon = asset('uploaded/activity/' . $activity->id . '/' . $activity->icon);

        if ($activity->parent != 0)
            $activity->category = Activity::find($activity->parent)->id;
        else
            $activity->category = 0;

        $activity->sidePic = ActivityPic::where('activityId', $activity->id)->get();
        foreach ($activity->sidePic as $pic)
            $pic->pic = asset('uploaded/activity/' . $activity->id . '/' . $pic->pic);

        if ($activity->video != null && $activity->isEmbeded == 0)
            $activity->video = asset('uploaded/activity/' . $activity->id . '/' . $activity->video);
        if ($activity->podcast != null)
            $activity->podcast = asset('uploaded/activity/' . $activity->id . '/' . $activity->podcast);

        $activity->titles = ActivityTitle::where('activityId', $activity->id)->get();

        if($activity->parent != 0)
            $parent = Activity::where('parent', 0)->where('lang', $lang)->get();
        else
            $parent = Activity::where('parent', 0)->where('lang', $lang)->where('id', '!=', $activity->id)->get();

        $sourceParent = Activity::where('lang', 'en')->get();

        return view('admin.activity.newActivity', compact(['parent', 'activity', 'sourceParent']));
    }


    public function storeActivity(Request $request)
    {
        if(isset($request->id) && isset($request->parentId) && isset($request->name)){
            $sameName = Activity::where('name', $request->name)->where('lang', app()->getLocale())->where('id', '!=', $request->id)->first();
            if($sameName == null){
                if($request->id == 0) {
                    $activity = new Activity();
                    $activity->lang = app()->getLocale();
                }
                else
                    $activity = Activity::find($request->id);

                if($request->source != 0) {
                    $source = Activity::find($request->source);
                    $activity->slug = $source->slug;
                }
                else
                    $activity->slug = makeSlug($request->name);

                $activity->langSource = $request->source;

                $activity->name = $request->name;
                $activity->viewOrder = $request->viewOrder;
                $activity->description = $request->description;
                $activity->parent = $request->parentId;
                if(isset($request->videoEmbeded)){
                    $activity->isEmbeded = 1;
                    if($activity->video != null){
                        $location = __DIR__ .'/../../../public/uploaded/activity/'.$activity->id.'/'.$activity->video;
                        if(is_file($location))
                            unlink($location);
                    }
                    $activity->video = $request->videoEmbeded;
                }
                else if($activity->isEmbeded == 1){
                    $activity->isEmbeded = 0;
                    $activity->video = null;
                }

                $activity->save();

                $childs = Activity::where('langSource', $activity->id)->get();
                foreach ($childs as $item){
                    $item->slug = $activity->slug;
                    $item->save();
                }

                echo json_encode(['status' => 'ok', 'id' => $activity->id]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeImgActivity(Request $request)
    {
        if(isset($request->kind) && isset($request->id)){
            $activity = Activity::find($request->id);
            if($activity != null){
                $location = __DIR__ . '/../../../public/uploaded/activity/' . $activity->id;
                if(!file_exists($location))
                    mkdir($location);

                $image = $request->file('pic');
                $dirs = 'uploaded/activity/' . $activity->id ;
                if($request->kind == 'icon') {
                    $size = [
                        [
                            'width' => 50,
                            'height' => 50,
                            'name' => '',
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
                        ],
                        [
                            'width' => 1200,
                            'height' => null,
                            'name' => '',
                            'destination' => $dirs
                        ]
                    ];
                }

                $fileName = resizeImage($image, $size);
                if($fileName != 'error') {
                    if ($request->kind == 'icon') {
                        if($activity->icon != null) {
                            if (is_file($location . '/' . $activity->icon))
                                unlink($location . '/' . $activity->icon);
                        }
                        $activity->icon = $fileName;
                        $activity->save();
                    }
                    else {
                        $newPic = new ActivityPic();
                        $newPic->activityId = $activity->id;
                        $newPic->pic = $fileName;
                        $newPic->save();
                    }
                    echo json_encode(['status' => 'ok']);
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

    public function deleteImgActivity(Request $request)
    {
        if(isset($request->id)){
            $pic = ActivityPic::find($request->id);
            if($pic != null){
                $location = __DIR__.'/../../../public/uploaded/activity/' . $pic->activityId;
                if(is_file($location .'/'.$pic->pic))
                    unlink($location.'/'.$pic->pic);
                if(is_file($location .'/min_'.$pic->pic))
                    unlink($location.'/min_'.$pic->pic);
                if(is_file($location .'/slide_'.$pic->pic))
                    unlink($location.'/slide_'.$pic->pic);
                if(is_file($location .'/list_'.$pic->pic))
                    unlink($location.'/list_'.$pic->pic);

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

    public function storeVideoAudioActivity(Request $request)
    {
        if(isset($request->id) && $_FILES['file'] && $_FILES['file']['error'] == 0){
            $activity = Activity::find($request->id);
            if($activity != null){
                $fileName = time() . $_FILES['file']['name'];
                $location = __DIR__ . '/../../../public/uploaded/activity';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $fileName;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                    if($request->kind == 'audio'){
                        if($activity->podcast != null)
                            \File::delete('uploaded/activity/'. $activity->id . '/' . $activity->podcast);

                        $activity->podcast = $fileName;
                        $activity->save();

                        $videoUrl = asset('uploaded/activity/' . $activity->id . '/' . $activity->podcast);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
                    }
                    else if($request->kind == 'video'){
                        if($activity->video != null)
                            \File::delete('uploaded/activity/'. $activity->id . '/' . $activity->video);

                        $activity->isEmbeded = 0;
                        $activity->video = $fileName;
                        $activity->save();

                        $videoUrl = asset('uploaded/activity/' . $activity->id . '/' . $activity->video);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
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

    public function storeTitleActivity(Request $request){
        if(isset($request->id) && isset($request->name) && isset($request->activityId)){
            $check = ActivityTitle::where('name', $request->name)->where('activityId', $request->activityId)->first();
            if($check == null) {
                if ($request->id == 0)
                    $title = new ActivityTitle();
                else
                    $title = ActivityTitle::find($request->id);

                $title->name = $request->name;
                $title->activityId = $request->activityId;
                $title->save();

                echo json_encode(['status' => 'ok', 'id' => $title->id, 'name' => $title->name]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteTitleActivity(Request $request)
    {
        if(isset($request->id)){
            $title = ActivityTitle::find($request->id);
            if($title != null){
                $location = __DIR__ .'/../../../public/uploaded/activity/' . $title->activityId . '/title_' . $title->id;
                emptyFolder($location);
                $title->delete();

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function descriptionActivity($id)
    {
        $activity = Activity::where('id', $id)->where('lang', app()->getLocale())->first();

        if($activity != null){
            $activity->titles = ActivityTitle::where('activityId', $activity->id)->get();
            return view('admin.activity.descriptionActivity', compact(['activity', 'showLang']));
        }

        return redirect(route('admin.activity.list'));
    }

    public function storeTitleTextActivity(Request $request)
    {
        if(isset($request->id)){
            $title = ActivityTitle::find($request->id);
            if($title != null){
                $title->text = $request->text;
                $title->save();

                $location = __DIR__ . '/../../../public/uploaded/activity/' . $title->activityId . '/title_' . $title->id . '/';
                if (file_exists($location)) {
                    $files = scandir($location);
                    foreach ($files as $item) {
                        if (is_file($location . '/' . $item)) {
                            if (strpos($request->text, $item) === false) {
                                unlink($location . '/' . $item);
                            }
                        }
                    }
                }

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeTitleTextImgActivity(Request $request)
    {
        $data = json_decode($request->data);
        $titleId = $data;

        if( $_FILES['file'] && $_FILES['file']['error'] == 0){
            $title = ActivityTitle::find($titleId);
            if($title != null){
                $location = __DIR__ . '/../../../public/uploaded/activity/' . $title->activityId;
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/title_' . $title->id;
                if(!file_exists($location))
                    mkdir($location);

                $image = $request->file('file');
                $dirs = 'uploaded/activity/' . $title->activityId . '/title_' . $title->id;
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
                    echo json_encode(['url' => asset('uploaded/activity/' . $title->activityId . '/title_' . $title->id . '/' . $fileName)]);
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


    public function checkActivity(Request $request)
    {
        if(isset($request->id)){
            $act = Activity::find($request->id);
            if($act != null){
                $error = false;
                $mainPackageError = [];
                $sidePackageError = [];
//                $langId = Activity::where('langSource', $act->id)->orWhere('id', $act->id)->pluck('id')->toArray();
                $mainPackage = Package::where('mainActivityId', $act->id)->get();
//                $sidePackage = PackageActivityRelations::where('activityId', $act->id)->get();
                $sidePackage = [];

                if(count($mainPackage) != 0){
                    $error = true;
                    foreach ($mainPackage as $item){
                        $d = [
                            'name' => $item->name,
                            'url' => route('admin.package.edit', ['id' => $item->id])
                        ];
                        array_push($mainPackageError, $d);
                    }
                }
                if(count($sidePackage) != 0){
                    $error = true;
                    foreach ($sidePackage as $item){
                        $p = Package::find($item->packageId);
                        if($p != null) {
                            $d = [
                                'name' => $p->name,
                                'url' => route('admin.package.edit', ['id' => $p->id])
                            ];
                            array_push($sidePackageError, $d);
                        }
                    }
                }

                if($error)
                    echo json_encode(['status' => 'nok2', 'main' => $mainPackageError, 'side' => $sidePackageError ]);
                else
                    echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1' ]);
        }
        else
            echo json_encode(['status' => 'nok' ]);

        return;
    }

    public function deleteActivity(Request $request)
    {
        if(isset($request->id)){
            $act = Activity::find($request->id);
            if($act != null){
                $mainPackage = Package::where('mainActivityId', $act->id)->get();
                if(count($mainPackage) == 0){
                    PackageActivityRelations::where('activityId', $act->id)->delete();
                    ActivityPic::where('activityId', $act->id)->delete();
                    ActivityTitle::where('activityId', $act->id)->delete();
                    Activity::where('parent', $act->id)->update(['parent' => 0]);

                    Activity::where('langSource', $act->id)->update(['langSource' => 0]);

                    $location = __DIR__ . '/../../../public/uploaded/activity/' . $act->id;
                    emptyFolder($location);

                    $act->delete();
                    echo json_encode(['status' => 'ok']);
                }
                else
                    echo json_encode(['status' => 'mainError']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);
    }
}
