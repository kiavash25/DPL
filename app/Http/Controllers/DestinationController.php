<?php

namespace App\Http\Controllers;

use App\models\City;
use App\models\Countries;
use App\models\Destination;
use App\models\DestinationPic;
use App\models\DestinationTagRelation;
use App\models\Tags;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function listDestination()
    {

    }

    public function newDestination()
    {
        $countries = Countries::all();
        $kind = 'new';

        return view('admin.destination.newDestination', compact(['countries', 'kind']));
    }

    public function editDestination($id)
    {
        $countries = Countries::all();
        $kind = 'edit';

        $destination = Destination::find($id);
        if($destination == null)
            return redirect(route('admin.destination.list'));


        $tags = DestinationTagRelation::where('destId', $id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $destination->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $destination->tags = [];

        $city = City::find($destination->cityId);
        if($city != null)
            $destination->city = $city->name;
        else
            $destination->city = '';

        if($destination->pic != null)
            $destination->pic = asset('uploaded/destination/'. $id . '/' . $destination->pic);

        $sideImage = DestinationPic::where('destId', $id)->get();
        foreach ($sideImage as $item)
            $item->pic = asset('uploaded/destination/'. $id . '/' . $item->pic);
        $destination->sidePic = $sideImage;

        return view('admin.destination.newDestination', compact(['countries', 'destination', 'kind']));

    }

    public function storeDestination(Request $request)
    {
        if(isset($request->name) && isset($request->id) && isset($request->lat) && isset($request->lng) && isset($request->countryId) && isset($request->cityId)){
            if($request->id == 0){
                $dest = Destination::where('name', $request->name)->first();
                if($dest != null) {
                    echo json_encode(['nok2']);
                    return;
                }
                $dest = new Destination();
            }
            else{
                $dest = Destination::where('name', $request->name)->where('id' , '!=', $request->id)->first();
                if($dest != null) {
                    echo json_encode(['nok2']);
                    return;
                }

                $dest = Destination::find($request->id);
                if($dest == null){
                    echo json_encode(['nok3']);
                    return;
                }
            }


            $dest->name = $request->name;
            $dest->description = $request->description;
            $dest->cityId = $request->cityId;
            $dest->countryId = $request->countryId;
            $dest->lng = $request->lng;
            $dest->lat = $request->lat;
            $dest->slug = makeSlug($request->name);
            $dest->save();

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
                $query .= '(Null, ' . $dest->id . ', ' . $t->id . ')';
            }

            DestinationTagRelation::where('destId', $dest->id)->delete();
            if($query != '')
                \DB::select('INSERT INTO destinationTagRelations (id, destId, tagId) VALUES ' . $query);

            echo json_encode(['ok', $dest->id]);
        }
        else
            echo json_encode(['nok']);

        return;
    }

    public function storeImgDestination(Request $request)
    {
        if(isset($request->id) && isset($request->kind) && isset($request->pic) && $_FILES['pic'] && $_FILES['pic']['error'] == 0){
            $dest = Destination::find($request->id);
            if($dest != null){
                $fileName = time() . $_FILES['pic']['name'];
                $location = __DIR__ . '/../../../public/uploaded/destination';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $fileName;

                $picResult = compressImage($_FILES['pic']['tmp_name'], $location, 80);
                if ($picResult) {
                    if($request->kind == 'mainPic') {
                        if ($dest->pic != null)
                            \File::delete('uploaded/destination/' . $request->id . '/' . $dest->pic);
                        $dest->pic = $fileName;
                        $dest->save();
                        echo json_encode(['ok']);
                    }
                    else{
                        $sidePic = new DestinationPic();
                        $sidePic->destId = $dest->id;
                        $sidePic->pic = $fileName;
                        $sidePic->save();

                        echo json_encode(['status' => 'ok', 'id' => $sidePic->id]);
                    }
                }
                else
                    echo json_encode(['nok2']);

            }
            else
                echo json_encode(['nok1']);
        }
        else
            echo json_encode(['nok']);

        return;
    }

    public function deleteImgDestination(Request $request)
    {
        if(isset($request->id)){
            $pic = DestinationPic::find($request->id);
            if($pic != null){
                \File::delete('uploaded/destination/' . $pic->destId . '/' . $pic->pic);
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



    public function deleteDestination(Request $request)
    {
        if(isset($request->id)){
            $dest = Destination::find($request->id);
            if($dest != null){
                DestinationTagRelation::where('destId', $dest->id)->delete();
                $pics = DestinationPic::where('destId', $dest->id)->get();

                foreach ($pics as $item){
                    \File::delete('uploaded/destination/' . $item->destId . '/' . $item->pic);
                    $item->delete();
                }

                $dest->delete();
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
