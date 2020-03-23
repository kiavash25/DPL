<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Package;
use App\models\PackageActivityRelations;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function listActivity()
    {

        $activity = Activity::all();

        foreach ($activity as $item)
            $item->icon = asset('uploaded/activityIcons/' . $item->icon);

        return view('admin.activity.listActivity', compact(['activity']));
    }

    public function storeActivity(Request $request)
    {
        if(isset($request->name)){
            $sameName = Activity::where('name', $request->name)->first();
            if($sameName == null){

                $iconName = null;
                if(isset($_FILES['icon']) && $_FILES['icon']['error'] == 0){
                    $location = __DIR__ . '/../../../public/uploaded/activityIcons';
                    if(!file_exists($location))
                        mkdir($location);

                    $iconName = time().$_FILES['icon']['name'];
                    $location .= '/'.$iconName;

                    $picResult = storeImage($_FILES['icon']['tmp_name'], $location);
                    if(!$picResult)
                        $iconName = null;
                }

                $newActivity = new Activity();
                $newActivity->name = $request->name;
                $newActivity->icon = $iconName;
                $newActivity->save();

                echo json_encode(['status' => 'ok', 'id' => $newActivity->id]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function doEditActivity(Request $request)
    {
        if(isset($request->id)) {
            $act = Activity::find($request->id);
            if($act == null){
                echo json_encode(['status' => 'nok2']);
                return;
            }

            if ($request->kind == 'deleteIcon') {
                \File::delete('uploaded/activityIcons/' . $act->icon);
                $act->icon = null;
                $act->save();

                echo json_encode(['status' => 'ok']);
            }
            else if ($request->kind == 'editIcon'){
                $iconName = null;
                if(isset($_FILES['icon']) && $_FILES['icon']['error'] == 0){
                    $location = __DIR__ . '/../../../public/uploaded/activityIcons';
                    if(!file_exists($location))
                        mkdir($location);

                    $iconName = time().$_FILES['icon']['name'];
                    $location .= '/'.$iconName;

                    $picResult = storeImage($_FILES['icon']['tmp_name'], $location);
                    if($picResult){
                        \File::delete('uploaded/activityIcons/' . $act->icon);
                        $act->icon = $iconName;
                        $act->save();

                        echo json_encode(['status' => 'ok']);
                    }
                    else
                        echo json_encode(['status' => 'nok4']);
                }
                else
                    echo json_encode(['status' => 'nok3']);

            }
            else if ($request->kind == 'editName' && isset($request->name)){
                $checkName = Activity::where('name', $request->name)->first();
                if($checkName == null) {
                    $act->name = $request->name;
                    $act->save();
                    echo json_encode(['status' => 'ok']);
                }
                else
                    echo json_encode(['status' => 'repeated']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

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
                $mainPackage = Package::where('mainActivityId', $act->id)->get();
                $sidePackage = PackageActivityRelations::where('activityId', $act->id)->get();

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
                    \File::delete('uploaded/activityIcons/' . $act->icon);
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
