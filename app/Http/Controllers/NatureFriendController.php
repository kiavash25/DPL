<?php

namespace App\Http\Controllers;

use App\models\Destination;
use App\models\DestinationCategoryPic;
use App\models\DestinationPic;
use App\models\DestinationTagRelation;
use App\models\NatureFriend;
use App\models\NatureFriendPic;
use App\models\Tags;
use Illuminate\Http\Request;

class NatureFriendController extends Controller
{
    public function natAdminList()
    {
        $nats = NatureFriend::where('lang', app()->getLocale())->get();

        return view('profile.admin.natureFriend.natList', compact(['nats']));
    }

    public function newAdminNat()
    {
        $sourceParent = NatureFriend::where('lang', 'en')->get();
        return view('profile.admin.natureFriend.natNew', compact(['sourceParent']));
    }

    public function editAdminNat($id)
    {
        $nat = NatureFriend::where('id', $id)->where('lang', app()->getLocale())->first();
        if($nat == null)
            return redirect()->route('admin.natureFriend.list');

        if($nat->pic != null)
            $nat->pic = asset('uploaded/natureFriend/'. $id . '/' . $nat->pic);

        $sideImage = NatureFriendPic::where('natId', $id)->get();
        foreach ($sideImage as $item)
            $item->pic = asset('uploaded/natureFriend/'. $id . '/' . $item->pic);
        $nat->sidePic = $sideImage;

        if($nat->video != null && $nat->isEmbeded == 0)
            $nat->video = asset('uploaded/natureFriend/' . $nat->id . '/' . $nat->video);

        if($nat->podcast != null)
            $nat->podcast = asset('uploaded/natureFriend/' . $nat->id . '/' . $nat->podcast);

        $sourceParent = NatureFriend::where('lang', 'en')->get();
        return view('profile.admin.natureFriend.natNew', compact(['nat', 'sourceParent']));
    }

    public function storeAdminNat(Request $request)
    {
        if(isset($request->name) && isset($request->id)){
            $check = NatureFriend::where('name', $request->name)->where('id', '!=', $request->id)->where('lang', $request->lang)->first();
            if($check != null){
                echo json_encode(['status' => 'duplicate']);
                return;
            }

            if($request->id == 0) {
                $nat = new NatureFriend();
                $nat->lang = $request->lang;
            }
            else
                $nat = NatureFriend::find($request->id);

            if($request->source == 0)
                $nat->slug = makeSlug($request->name);
            else{
                $s = NatureFriend::find($request->source);
                $nat->slug = $s->slug;
            }

            $nat->name = $request->name;
            $nat->description = $request->description;
            $nat->langSource = $request->source;
            if(isset($request->videoEmbeded)){
                $nat->isEmbeded = 1;
                if($nat->video != null){
                    $location = __DIR__ .'/../../../public/uploaded/natureFriend/'.$nat->id.'/'.$nat->video;
                    if(is_file($location))
                        unlink($location);
                }
                $nat->video = $request->videoEmbeded;
            }
            else if($nat->isEmbeded == 1){
                $nat->isEmbeded = 0;
                $nat->video = null;
            }

            $nat->save();

            NatureFriend::where('langSource', $nat->id)->update(['slug' => $nat->slug]);

            echo json_encode(['status' => 'ok', 'id' => $nat->id]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeAltImgAdminNat(Request $request)
    {
        if(isset($request->id) && isset($request->alt)){
            $pic = NatureFriendPic::find($request->id);
            if($pic != null){
                $pic->alt = $request->alt;
                $pic->save();
                return response()->json(['status' => 'ok']);
            }
            else
                return response()->json(['status' => 'error2']);
        }
        else
            return response()->json(['status' => 'error1']);
    }

    public function storeImgAdminNat(Request $request)
    {
        if(isset($request->id) && isset($request->kind) && isset($request->pic) && $_FILES['pic'] && $_FILES['pic']['error'] == 0){
            $nat = NatureFriend::find($request->id);
            if($nat != null){
                $location = __DIR__ . '/../../../public/uploaded/natureFriend';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);

                $dirs = 'uploaded/natureFriend/' . $request->id;
                $image = $request->file('pic');
                $size = [
                    [
                        'width' => null,
                        'height' => null,
                        'name' => '',
                        'destination' => $dirs
                    ],
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
                        'height' => 700,
                        'name' => 'slide_',
                        'destination' => $dirs
                    ]
                ];
                $fileName = resizeImage($image, $size);

                if($fileName != 'error') {
                    $location .= '/' . $fileName;

                    if ($request->kind == 'mainPic') {
                        if ($nat->pic != null) {
                            \File::delete('uploaded/natureFriend/' . $request->id . '/slide_' . $nat->pic);
                            \File::delete('uploaded/natureFriend/' . $request->id . '/min_' . $nat->pic);
                            \File::delete('uploaded/natureFriend/' . $request->id . '/list_' . $nat->pic);
                            \File::delete('uploaded/natureFriend/' . $request->id . '/' . $nat->pic);
                        }
                        $nat->pic = $fileName;
                        $nat->save();
                        echo json_encode(['status' => 'ok']);
                    } else {
                        $sidePic = new NatureFriendPic();
                        $sidePic->natId = $nat->id;
                        $sidePic->pic = $fileName;
                        $sidePic->save();

                        echo json_encode(['status' => 'ok', 'id' => $sidePic->id]);
                    }
                }else
                    echo json_encode(['status' => 'nok2']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeVideoAudioNat(Request $request)
    {
        if(isset($request->id) && $_FILES['file'] && $_FILES['file']['error'] == 0){
            $nat = NatureFriend::find($request->id);
            if($nat != null){
                $fileName = time() . $_FILES['file']['name'];
                $location = __DIR__ . '/../../../public/uploaded/natureFriend';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $fileName;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                    if($request->kind == 'audio'){
                        if($nat->podcast != null)
                            \File::delete('uploaded/natureFriend/'. $nat->id . '/' . $nat->podcast);

                        $nat->podcast = $fileName;
                        $nat->save();

                        $videoUrl = asset('uploaded/natureFriend/' . $nat->id . '/' . $nat->podcast);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
                    }
                    else if($request->kind == 'video'){
                        if($nat->video != null)
                            \File::delete('uploaded/natureFriend/'. $nat->id . '/' . $nat->video);

                        $nat->isEmbeded = 0;
                        $nat->video = $fileName;
                        $nat->save();

                        $videoUrl = asset('uploaded/natureFriend/' . $nat->id . '/' . $nat->video);
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

    public function deleteImgNat(Request $request)
    {
        if(isset($request->id)){
            $pic = NatureFriendPic::find($request->id);
            if($pic != null){
                \File::delete('uploaded/natureFriend/' . $pic->destId . '/list_' . $pic->pic);
                \File::delete('uploaded/natureFriend/' . $pic->destId . '/slide_' . $pic->pic);
                \File::delete('uploaded/natureFriend/' . $pic->destId . '/min_' . $pic->pic);
                \File::delete('uploaded/natureFriend/' . $pic->destId . '/' . $pic->pic);
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

    public function deleteAdminNat(Request $request)
    {
        if(isset($request->id)){
            $result = NatureFriend::deleteWithPic($request->id);
            if($result)
                echo json_encode(['status' => 'ok']);
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

}
