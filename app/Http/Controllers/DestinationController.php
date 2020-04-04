<?php

namespace App\Http\Controllers;

use App\models\City;
use App\models\Countries;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\DestinationCategoryTitle;
use App\models\DestinationCategoryTitleText;
use App\models\DestinationPic;
use App\models\DestinationTagRelation;
use App\models\Package;
use App\models\Tags;
use Illuminate\Http\Request;

class DestinationController extends Controller
{

    public function listCategory()
    {
        $category = DestinationCategory::all();
        foreach ($category as $item)
            $item->title = DestinationCategoryTitle::where('categoryId', $item->id)->get();

        return view('admin.destination.categoryDestination', compact(['category']));
    }

    public function storeCategory(Request $request)
    {
        if(isset($request->name)){
            $check = DestinationCategory::where('name', $request->name)->first();
            if($check == null){
                $category = new DestinationCategory();
                $category->name = $request->name;
                $category->save();

                echo json_encode(['status' => 'ok', 'result' => $category->id]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function editCategory(Request $request){
        if(isset($request->id) && isset($request->name) && $request->name != ''){
            $check = DestinationCategory::where('name', $request->name)->where('id', '!=', $request->id)->first();
            if($check == null){
                $category = DestinationCategory::find($request->id);
                if($category != null){
                    $category->name = $request->name;
                    $category->save();

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

    public function storeCategoryTitle(Request $request)
    {
        if(isset($request->kind) &&isset($request->categoryId) && isset($request->name) && $request->name != ''){

            $check = DestinationCategoryTitle::where('name', $request->name)->where('categoryId', $request->categoryId)->first();
            if($check == null) {
                if ($request->kind == 'new') {
                    $title = new DestinationCategoryTitle();
                }
                else if ($request->kind == 'edit' && isset($request->id)) {
                    $title = DestinationCategoryTitle::find($request->id);
                    if($title == null){
                        echo json_encode(['status' => 'nok3']);
                        return;
                    }
                }
                else {
                    echo json_encode(['status' => 'nok2']);
                    return;
                }

                $title->name = $request->name;
                $title->categoryId = $request->categoryId;
                $title->save();

                echo json_encode(['status' => 'ok', 'result' => $title->id]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteCategoryTitle(Request $request)
    {
        if(isset($request->id)){
            $title = DestinationCategoryTitle::find($request->id);
            if($title != null){
                DestinationCategoryTitleText::where('titleId', $title->id)->delete();
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



    public function listDestination()
    {
        $destination = Destination::all();
        foreach ($destination as $item)
            $item->category = DestinationCategory::find($item->categoryId);


        return view('admin.destination.listDestination', compact(['destination']));
    }

    public function newDestination()
    {
        $countries = Countries::all();
        $kind = 'new';

        $category = DestinationCategory::all();
        foreach($category as $item)
            $item->titles = DestinationCategoryTitle::where('categoryId', $item->id)->get();

        return view('admin.destination.newDestination', compact(['countries', 'kind', 'category']));
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

        if($destination->video != null)
            $destination->video = asset('uploaded/destination/' . $destination->id . '/' . $destination->video);

        if($destination->podcast != null)
            $destination->podcast = asset('uploaded/destination/' . $destination->id . '/' . $destination->podcast);

        $category = DestinationCategory::all();
        foreach($category as $item)
            $item->titles = DestinationCategoryTitle::where('categoryId', $item->id)->get();

        return view('admin.destination.newDestination', compact(['countries', 'destination', 'kind', 'category']));
    }

    public function storeDestination(Request $request)
    {
        if(isset($request->name) && isset($request->id) && isset($request->lat) && isset($request->lng) && isset($request->categoryId)){
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
            $dest->categoryId = $request->categoryId;
            $dest->countryId = 107;
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

    public function storeVideoAudioDestination(Request $request)
    {
        if(isset($request->id) && $_FILES['file'] && $_FILES['file']['error'] == 0){
            $dest = Destination::find($request->id);
            if($dest != null){
                $fileName = time() . $_FILES['file']['name'];
                $location = __DIR__ . '/../../../public/uploaded/destination';
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);
                $location .= '/' . $fileName;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                    if($request->kind == 'audio'){
                        if($dest->podcast != null)
                            \File::delete('uploaded/destination/'. $dest->id . '/' . $dest->podcast);

                        $dest->podcast = $fileName;
                        $dest->save();

                        $videoUrl = asset('uploaded/destination/' . $dest->id . '/' . $dest->podcast);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
                    }
                    else if($request->kind == 'video'){
                        if($dest->video != null)
                            \File::delete('uploaded/destination/'. $dest->id . '/' . $dest->video);

                        $dest->video = $fileName;
                        $dest->save();

                        $videoUrl = asset('uploaded/destination/' . $dest->id . '/' . $dest->video);
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

    public function descriptionDestination($id)
    {
        $dest = Destination::find($id);
        if($dest ==  null)
            return redirect(route('admin.destination.list'));

        $category = DestinationCategoryTitle::find($dest->categoryId);
        if($category == null)
            return redirect(route('admin.destination.edit', ['id' => $dest->id]));

        $category->titles = DestinationCategoryTitle::where('categoryId', $category->id)->get();
        foreach ($category->titles as $item){
            $item->text = DestinationCategoryTitleText::where('titleId', $item->id)->where('destId', $dest->id)->first();
            if($item->text != null)
                $item->text = $item->text->text;
        }

        return view('admin.destination.titleDescriptionDestination', compact(['dest', 'category']) );
    }

    public function storeDescriptionDestination(Request $request)
    {
        if(isset($request->destId) && isset($request->titleId)){
            $dest = Destination::find($request->destId);
            $title = DestinationCategoryTitle::find($request->titleId);

            if ($title != null && $dest != null) {
                $text = DestinationCategoryTitleText::where('destId', $dest->id)->where('titleId', $title->id)->first();
                if($request->value == null && $text !=  null){
                    $text->delete();
                }
                else {
                    if ($text == null)
                        $text = new DestinationCategoryTitleText();

                    $text->destId = $dest->id;
                    $text->titleId = $title->id;
                    $text->text = $request->value;
                    $text->save();
                }

                $location = __DIR__ . '/../../../public/uploaded/destination/' . $dest->id . '/' . $title->id . '/';
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
            } else
                echo json_encode(['status' => 'nok1']);

        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeDescriptionImgDestination(Request $request)
    {
        $destId = json_decode($request->data)[0];
        $titleId = json_decode($request->data)[1];

        if( $_FILES['file'] && $_FILES['file']['error'] == 0){
            $dest = Destination::find($destId);
            $title = DestinationCategoryTitle::find($titleId);
            if($dest != null && $title != null){
                $fileName = time() . $_FILES['file']['name'];
                $location = __DIR__ . '/../../../public/uploaded/destination';
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $dest->id;
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $title->id;
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $fileName;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
                    echo json_encode(['url' => asset('uploaded/destination/' . $dest->id . '/' . $title->id . '/' . $fileName)]);
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

    public function deleteDestination(Request $request)
    {
        if(isset($request->id)){
            $dest = Destination::find($request->id);
            if($dest != null){
                $destTitlesId = DestinationCategoryTitleText::where('destId', $dest->id)->pluck('titleId')->toArray();
                $location = __DIR__ . '/../../../public/uploaded/destination/' . $dest->id;
                foreach ($destTitlesId as $item) {
                    $nLoc = $location . '/' . $item;
                    if(file_exists($nLoc)){
                        $scan = scandir($nLoc);
                        foreach ($scan as $file) {
                            if(is_file($nLoc . '/' . $file))
                                unlink($nLoc . '/' . $file);
                        }
                        try {
                            rmdir($nLoc);
                        }
                        catch (\Exception $exception){
                            continue;
                        }
                    }
                }

                DestinationCategoryTitleText::where('destId', $dest->id)->delete();

                DestinationTagRelation::where('destId', $dest->id)->delete();
                $pics = DestinationPic::where('destId', $dest->id)->get();
                foreach ($pics as $item){
                    \File::delete('uploaded/destination/' . $item->destId . '/' . $item->pic);
                    $item->delete();
                }

                if($dest->video != null)
                    \File::delete('uploaded/destination/' . $dest->id . '/' . $dest->video);
                if($dest->podcast != null)
                    \File::delete('uploaded/destination/' . $dest->id . '/' . $dest->podcast);
                if($dest->pic != null)
                    \File::delete('uploaded/destination/' . $dest->id . '/' . $dest->pic);

                try {
                    rmdir($location);
                }
                catch (\Exception $exception){
                    //
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

    public function checkDestination(Request $request)
    {
        if(isset($request->id)){
            $dest = Destination::find($request->id);
            if($dest != null){
                $error = false;
                $mainPackageError = [];
                $mainPackage = Package::where('destId', $dest->id)->get();

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

                if($error)
                    echo json_encode(['status' => 'nok2', 'main' => $mainPackageError ]);
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
}
