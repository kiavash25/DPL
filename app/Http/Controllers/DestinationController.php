<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\City;
use App\models\Countries;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\DestinationCategoryPic;
use App\models\DestinationCategoryTitle;
use App\models\DestinationCategoryTitleText;
use App\models\DestinationPic;
use App\models\DestinationTagRelation;
use App\models\Package;
use App\models\Tags;
use Carbon\FactoryImmutable;
use Illuminate\Http\Request;
use Symfony\Component\Console\Descriptor\DescriptorInterface;

class DestinationController extends Controller
{
    public function listDestination()
    {
        $destination = Destination::where('lang', app()->getLocale())->get();
        foreach ($destination as $item)
            $item->category = DestinationCategory::find($item->categoryId);

        return view('profile.admin.destination.listDestination', compact(['destination']));
    }

    public function newDestination()
    {
        $sourceParent = Destination::where('lang', 'en')->get();

        $countries = Countries::all();
        $kind = 'new';

        $category = DestinationCategory::where('lang', app()->getLocale())->get();
        foreach($category as $item)
            $item->titles = DestinationCategoryTitle::where('categoryId', $item->id)->get();

        return view('profile.admin.destination.newDestination', compact(['countries', 'kind', 'category', 'sourceParent']));
    }

    public function editDestination($id)
    {
        $countries = Countries::all();
        $kind = 'edit';

        $sourceParent = Destination::where('lang', 'en')->get();

        $destination = Destination::where('id', $id)->where('lang', app()->getLocale())->first();
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

        if($destination->video != null && $destination->isEmbeded == 0)
            $destination->video = asset('uploaded/destination/' . $destination->id . '/' . $destination->video);

        if($destination->podcast != null)
            $destination->podcast = asset('uploaded/destination/' . $destination->id . '/' . $destination->podcast);

        $category = DestinationCategory::where('lang', app()->getLocale())->get();
        foreach($category as $item)
            $item->titles = DestinationCategoryTitle::where('categoryId', $item->id)->get();

        return view('profile.admin.destination.newDestination', compact(['countries', 'destination', 'kind', 'category', 'sourceParent']));
    }

    public function storeDestination(Request $request)
    {
        if(isset($request->name) && isset($request->id) && isset($request->lat) && isset($request->lng) && isset($request->categoryId)){

            $check = Destination::where('name', $request->name)->where('id', '!=', $request->id)->where('lang', app()->getLocale())->first();
            if($check != null){
                echo json_encode(['nok1']);
                return;
            }

            if($request->id == 0) {
                $dest = new Destination();
                $dest->lang = app()->getLocale();
            }
            else
                $dest = Destination::find($request->id);

            if($request->source == 0){
                $dest->slug = makeSlug($request->name);
                $dest->lng = $request->lng;
                $dest->lat = $request->lat;
            }
            else{
                $s = Destination::find($request->source);
                $dest->slug = $s->slug;
                $dest->lat = $s->lat;
                $dest->lng = $s->lng;
            }

            $dest->name = $request->name;
            $dest->description = $request->description;
            $dest->cityId = $request->cityId;
            $dest->categoryId = $request->categoryId;
            $dest->countryId = 107;
            $dest->langSource = $request->source;
            if(isset($request->videoEmbeded)){
                $dest->isEmbeded = 1;
                if($dest->video != null){
                    $location = __DIR__ .'/../../../public/uploaded/destination/'.$dest->id.'/'.$dest->video;
                    if(is_file($location))
                        unlink($location);
                }
                $dest->video = $request->videoEmbeded;
            }
            else if($dest->isEmbeded == 1){
                $dest->isEmbeded = 0;
                $dest->video = null;
            }

            $dest->save();

            Destination::where('langSource', $dest->id)->update(['slug' => $dest->slug, 'lng' => $dest->lng, 'lat' => $dest->lat]);

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

                $dirs = 'uploaded/destination/' . $request->id;
                $image = $request->file('pic');
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
                $fileName = resizeImage($image, $size);

                $location .= '/' . $fileName;

                $picResult = storeImage($_FILES['pic']['tmp_name'], $location);
                if ($picResult) {
                    if($request->kind == 'mainPic') {
                        if ($dest->pic != null) {
                            \File::delete('uploaded/destination/' . $request->id . '/slide_' . $dest->pic);
                            \File::delete('uploaded/destination/' . $request->id . '/min_' . $dest->pic);
                            \File::delete('uploaded/destination/' . $request->id . '/list_' . $dest->pic);
                            \File::delete('uploaded/destination/' . $request->id . '/' . $dest->pic);
                        }
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

                        $dest->isEmbeded = 0;
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
                \File::delete('uploaded/destination/' . $pic->destId . '/list_' . $pic->pic);
                \File::delete('uploaded/destination/' . $pic->destId . '/slide_' . $pic->pic);
                \File::delete('uploaded/destination/' . $pic->destId . '/min_' . $pic->pic);
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

        return view('profile.admin.destination.titleDescriptionDestination', compact(['dest', 'category']) );
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
            $result = Destination::deleteWithPic($request->id);
            if($result)
                echo json_encode(['status' => 'ok']);
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
