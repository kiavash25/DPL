<?php

namespace App\Http\Controllers;

use App\models\Destination;
use App\models\DestinationCategory;
use App\models\DestinationCategoryPic;
use App\models\DestinationCategoryTitle;
use App\models\DestinationCategoryTitleText;
use Illuminate\Http\Request;

class DestCategoryController extends Controller
{
    public function listCategory()
    {
        $category = DestinationCategory::where('lang', app()->getLocale())->get();
        foreach ($category as $item) {
            $item->title = DestinationCategoryTitle::where('categoryId', $item->id)->get();
            if($item->langSource == 0)
                $item->icon = asset('uploaded/destination/category/' . $item->id . '/' . $item->icon);
            else
                $item->icon = asset('uploaded/destination/category/' . $item->langSource . '/' . $item->icon);

            $item->destNum = Destination::where('categoryId', $item->id)->count();
        }

        return view('profile.admin.destination.categoryDestination', compact(['category']));
    }

    public function newCategory()
    {
        $sourceParent = DestinationCategory::where('lang', 'en')->get();

        return view('profile.admin.destination.newDestinationCategory', compact(['sourceParent']));
    }

    public function editCategory($id){

        $category = DestinationCategory::where('id', $id)->where('lang', app()->getLocale())->first();
        if($category == null)
            return redirect(route('admin.destination.category.index'));

        $pics = DestinationCategoryPic::where('categoryId', $id)->get();
        foreach ($pics as $pic)
            $pic->pic = asset('uploaded/destination/category/' . $id . '/' . $pic->pic);
        $category->pic = $pics;

        $category->titles = DestinationCategoryTitle::where('categoryId', $id)->get();
        $category->icon = asset('uploaded/destination/category/' . $id . '/' . $category->icon);

        if($category->video != null && $category->isEmbeded == 0)
            $category->video = asset('uploaded/destination/category/' . $id . '/' . $category->video);

        if($category->podcast != null)
            $category->podcast = asset('uploaded/destination/category/' . $id . '/' . $category->podcast);

        $sourceParent = DestinationCategory::where('lang', 'en')->get();
        return view('profile.admin.destination.newDestinationCategory', compact(['category', 'sourceParent']));
    }

    public function storeCategory(Request $request)
    {
        if(isset($request->name) && isset($request->id)){
            $check = DestinationCategory::where('name', $request->name)->where('id', '!=', $request->id)->first();
            if($check == null){
                if($request->id == 0) {
                    $category = new DestinationCategory();
                    $category->lang = app()->getLocale();
                }
                else
                    $category = DestinationCategory::find($request->id);

                if($request->source != 0) {
                    $source = DestinationCategory::find($request->source);
                    $category->slug = $source->slug;
                    $category->icon = $source->icon;
                    $category->langSource = $source->id;
                }
                else{
                    $category->slug = makeSlug($request->name);
                    $category->langSource = 0;
                }

                $category->name = $request->name;
                $category->viewOrder = $request->viewOrder;
                $category->description = $request->description;
                if(isset($request->videoEmbeded)){
                    $category->isEmbeded = 1;
                    if($category->video != null){
                        $location = __DIR__ .'/../../../public/uploaded/destination/category/'.$category->id.'/'.$category->video;
                        if(is_file($location))
                            unlink($location);
                    }
                    $category->video = $request->videoEmbeded;
                }
                else if($category->isEmbeded == 1){
                    $category->isEmbeded = 0;
                    $category->video = null;
                }
                $category->save();

                DestinationCategory::where('langSource', $category->id)->update(['icon' => $category->icon, 'slug' => $category->slug]);

                echo json_encode(['status' => 'ok', 'id' => $category->id]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeImgCategory(Request $request)
    {
        if(isset($request->id) && isset($request->kind) && isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
            $category = DestinationCategory::find($request->id);
            $location = __DIR__ . '/../../../public/uploaded/destination/category';
            if (!file_exists($location))
                mkdir($location);

            $location .= '/' . $category->id;
            if (!file_exists($location))
                mkdir($location);

            $picName = time() . $_FILES['pic']['name'];

            if($request->kind != 'icon'){
                $dirs = 'uploaded/destination/category/' . $category->id;
                $image = $request->file('pic');
                $size = [
                    [
                        'width' => 200,
                        'height' => 250,
                        'name' => 'min_',
                        'destination' => $dirs
                    ],
                    [
                        'width' => 400,
                        'height' => 200,
                        'name' => 'list_',
                        'destination' => $dirs
                    ],
                    [
                        'width' => 950,
                        'height' => 500,
                        'name' => 'slide_',
                        'destination' => $dirs
                    ]
                ];
                $picName = resizeImage($image, $size);
            }

            $location .= '/' . $picName;

            $picResult = storeImage($_FILES['pic']['tmp_name'], $location);
            if ($picResult){
                if($request->kind == 'icon'){
                    if($category->icon != null)
                        \File::delete('uploaded/destination/category/' . $category->id . '/' . $category->icon );
                    $category->icon = $picName;
                    $category->save();
                    echo json_encode(['status' => 'ok']);
                }
                else{
                    $newPic = new DestinationCategoryPic();
                    $newPic->categoryId = $category->id;
                    $newPic->pic = $picName;
                    $newPic->save();

                    echo json_encode(['status' => 'ok', 'id' => $newPic->id]);
                }
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteImgCategory(Request $request)
    {
        if(isset($request->id)){
            $pic = DestinationCategoryPic::find($request->id);
            if($pic != null){
                \File::delete('uploaded/destination/category/' . $pic->categoryId . '/' . $pic->pic );
                \File::delete('uploaded/destination/category/' . $pic->categoryId . '/min_' . $pic->pic );
                \File::delete('uploaded/destination/category/' . $pic->categoryId . '/list_' . $pic->pic );
                \File::delete('uploaded/destination/category/' . $pic->categoryId . '/slide_' . $pic->pic );
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

    public function storeVideoAudioCategory(Request $request)
    {
        if(isset($request->id) && $_FILES['file'] && $_FILES['file']['error'] == 0){
            $cat = DestinationCategory::find($request->id);
            if($cat != null){
                $fileName = time() . $_FILES['file']['name'];
                $location = __DIR__ . '/../../../public/uploaded/destination/category';
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $request->id;
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $fileName;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                    if($request->kind == 'audio'){
                        if($cat->podcast != null)
                            \File::delete('uploaded/destination/category/'. $cat->id . '/' . $cat->podcast);

                        $cat->podcast = $fileName;
                        $cat->save();

                        $videoUrl = asset('uploaded/destination/category/' . $cat->id . '/' . $cat->podcast);
                        echo json_encode(['status' => 'ok', 'result' => $videoUrl]);
                    }
                    else if($request->kind == 'video'){
                        if($cat->video != null)
                            \File::delete('uploaded/destination/category/'. $cat->id . '/' . $cat->video);

                        $cat->video = $fileName;
                        $cat->save();

                        $videoUrl = asset('uploaded/destination/category/' . $cat->id . '/' . $cat->video);
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

    public function storeCategoryTitle(Request $request)
    {
        if(isset($request->id) && isset($request->categoryId) && isset($request->name) && $request->name != ''){
            $check = DestinationCategoryTitle::where('name', $request->name)->where('categoryId', $request->categoryId)->count();
            if($check == 0){
                if($request->id == 0)
                    $title = new DestinationCategoryTitle();
                else
                    $title = DestinationCategoryTitle::find($request->id);

                $title->name = $request->name;
                $title->categoryId = $request->categoryId;
                $title->save();

                echo json_encode(['status' => 'ok', 'id' => $title->id]);
            }
            else{
                $lastTitle = '';
                if($request->id != 0)
                    $lastTitle = DestinationCategoryTitle::find($request->id)->name;

                echo json_encode(['status' => 'nok1', 'lastTitle' => $lastTitle]);
            }
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
                $dest = DestinationCategoryTitleText::where('titleId', $title->id)->get();
                foreach ($dest as $item)
                    DestinationCategoryTitleText::deleteWithPic($item->id);

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

    public function deleteCategory(Request $request)
    {
        if(isset($request->id)) {
            $category = DestinationCategory::find($request->id);
            if($category != null){

                $destinations = Destination::where('categoryId', $category->id)->get();
                if(count($destinations) != 0) {
                    echo json_encode(['status' => 'nok3']);
                    return;
                }

                $titles = DestinationCategoryTitle::where('categoryId', $category->id)->get();
                foreach ($titles as $title){
                    $texts = DestinationCategoryTitleText::where('titleId', $title->id)->get();
                    foreach ($texts as $text)
                        DestinationCategoryTitleText::deleteWithPic($text->id);

                    $title->delete();
                }
                DestinationCategoryPic::where('categoryId', $category->id)->delete();

                $location = __DIR__.'/../../../public/uploaded/destination/category/' . $category->id ;
                emptyFolder($location);

                DestinationCategory::where('langSource', $category->id)->update(['langSource' => 0]);

                $category->delete();
                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function checkCategoryDestination(Request $request)
    {
        if(isset($request->id)){
            $category = DestinationCategory::find($request->id);
            if($category != null){
                $error = false;
                $mainDestinationError = [];
                $mainDestination = Destination::where('categoryId', $category->id)->get();

                if(count($mainDestination) != 0){
                    $error = true;
                    foreach ($mainDestination as $item){
                        $d = [
                            'name' => $item->name,
                            'url' => route('admin.destination.list')
                        ];
                        array_push($mainDestinationError, $d);
                    }
                }

                if($error)
                    echo json_encode(['status' => 'nok2', 'main' => $mainDestinationError]);
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
