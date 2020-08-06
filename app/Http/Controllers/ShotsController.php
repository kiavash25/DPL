<?php

namespace App\Http\Controllers;

use App\Events\makeLog;
use App\models\Language;
use App\models\Shots;
use App\models\ShotsCategory;
use App\models\ShotsLike;
use App\models\ShotsTagRelation;
use App\models\Tags;
use Illuminate\Http\Request;

class ShotsController extends Controller
{
    public function adminShotsCategory()
    {
        $langs = Language::all();

        $category = ShotsCategory::where('sourceId', 0)->get();
        foreach ($category as $item){
            $item->langs = ShotsCategory::where('sourceId', $item->id)->get();
            $item->shots = Shots::where('categoryId', $item->id)->count();
        }

        return view('profile.admin.shots.shotCategory', compact(['category', 'langs']));
    }

    public function adminShotsCategoryStore(Request $request)
    {
        if(isset($request->id) && isset($request->name)){
            $check = ShotsCategory::where('name', $request->name)->where('id', '!=', $request->id)->where('lang', 'en')->first();
            if($check == null){
                if($request->id == 0){
                    $category = new ShotsCategory();
                    $category->lang = 'en';
                    $category->sourceId = 0;
                }
                else
                    $category = ShotsCategory::find($request->id);

                $category->name = $request->name;
                $category->save();

                ShotsCategory::where('sourceId', $category->id)->delete();

                $otherLang = json_decode($request->otherLang);
                foreach ($otherLang as $item){
                    if($item->name != '' && $item->lang != ''){
                        $lang = new ShotsCategory();
                        $lang->name = $item->name;
                        $lang->lang = $item->lang;
                        $lang->sourceId = $category->id;
                        $lang->save();
                    }
                }

                if($request->id == 0)
                    event(new makeLog([
                        'subject' => 'new_shot_category',
                        'referenceId' => $category->id,
                        'referenceTable' => 'shotsCategories'
                    ]));
                else
                    event(new makeLog([
                        'subject' => 'edit_shot_category',
                        'referenceId' => $category->id,
                        'referenceTable' => 'shotsCategories'
                    ]));

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'duplicate']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function adminShotsCategoryDelete(Request $request)
    {
        if(isset($request->id)){
            $category = ShotsCategory::find($request->id);
            $shots = Shots::where('categoryId', $category->id)->count();
            if($shots == 0){
                event(new makeLog([
                    'subject' => 'delete_shot_category',
                    'referenceId' => $category->id,
                    'referenceTable' => 'shotsCategories'
                ]));

                ShotsCategory::where('sourceId', $request->id)->delete();
                $category->delete();

                echo 'ok';
            }
            else
                echo 'notZero';
        }
        else
            echo 'nok';

        return;
    }

    public function adminOurShots()
    {
        $category = ShotsCategory::where('sourceId', 0)->get();
        foreach ($category as $item){
            $langs = ShotsCategory::where('sourceId', $item->id)->where('lang', app()->getLocale())->first();
            if($langs != null)
                $item->name = $langs->name;
        }

        $shots = Shots::where('ourShot', 1)->get();
        foreach ($shots as $item){
            $item->pic200 = asset('uploaded/ourShots/200_' . $item->pic);
            $item->pic500 = asset('uploaded/ourShots/500_' . $item->pic);
            $item->pic    = asset('uploaded/ourShots/' . $item->pic);

            $item->tag = \DB::table('tags')
                    ->join('shotsTagRelations', 'tags.id', '=', 'shotsTagRelations.tagId')
                    ->where('shotsTagRelations.shotId', $item->id)
                    ->select(['tags.tag'])->pluck('tag')->toArray();
        }

        return view('profile.admin.shots.ourShots', compact(['category', 'shots']));
    }

    public function adminOurShotsStore(Request $request)
    {
        if(isset($request->id) && isset($request->name) && isset($request->categoryId)){

            $location = __DIR__ .'/../../../public/uploaded/ourShots';
            if(!is_dir($location))
                mkdir($location);

            if($request->id == 0){
                if(!(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0)){
                    echo 'notPic';
                    return;
                }
                $newShots = new Shots();
                $newShots->like = 0;
                $newShots->ourShot = 1;
                $newShots->yourShot = 0;
            }
            else
                $newShots = Shots::find($request->id);

            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $image = $request->file('pic');
                $dirs = 'uploaded/ourShots';
                $size = [
                    [
                        'width' => null,
                        'height' => null,
                        'name' => '',
                        'destination' => $dirs
                    ],
                    [
                        'width' => 500,
                        'height' => null,
                        'name' => '500_',
                        'destination' => $dirs
                    ],
                    [
                        'width' => 200,
                        'height' => null,
                        'name' => '200_',
                        'destination' => $dirs
                    ]
                ];

                $fileName = resizeImage($image, $size);
                if($fileName == 'error'){
                    echo 'errorPic';
                    return;
                }
                $newShots->pic = $fileName;
            }

            $newShots->userId = auth()->user()->id;
            $newShots->categoryId = $request->categoryId;
            $newShots->name = $request->name;
            $newShots->text = $request->text;
            $newShots->save();

            ShotsTagRelation::where('shotId', $newShots->id)->delete();
            $tags = json_decode($request->tag);
            foreach ($tags as $item){
                $tag = Tags::where('tag', $item)->first();
                if($tag == null){
                    $tag = new Tags();
                    $tag->tag = $item;
                    $tag->save();
                }
                $tagRel = new ShotsTagRelation();
                $tagRel->shotId = $newShots->id;
                $tagRel->tagId = $tag->id;
                $tagRel->save();
            }

            if($request->id == 0)
                event(new makeLog([
                    'subject' => 'new_our_shots',
                    'referenceId' => $newShots->id,
                    'referenceTable' => 'shots'
                ]));
            else
                event(new makeLog([
                    'subject' => 'edit_our_shots',
                    'referenceId' => $newShots->id,
                    'referenceTable' => 'shots'
                ]));

            echo 'ok';
        }
        else
            echo 'nok';

        return;
    }

    public function adminOurShotsDelete(Request $request)
    {
        if(isset($request->id)){
            $shot = Shots::find($request->id);
            if($shot != null){
                ShotsTagRelation::where('shotId', $request->id)->delete();
                ShotsLike::where('shotId', $request->id)->delete();
                $location = __DIR__.'/../../../public/uploaded/ourShots';
                if(is_file($location.'/200_'.$shot->pic))
                    unlink($location.'/200_'.$shot->pic);

                if(is_file($location.'/500_'.$shot->pic))
                    unlink($location.'/500_'.$shot->pic);

                if(is_file($location.'/'.$shot->pic))
                    unlink($location.'/'.$shot->pic);

                event(new makeLog([
                    'subject' => 'delete_our_shots',
                    'referenceId' => $shot->id,
                    'referenceTable' => 'shots'
                ]));
                $shot->delete();
                echo 'ok';
            }
            else
                echo 'notFound';
        }
        else
            echo 'nok';

        return;
    }




    public function shotPage()
    {
        $category = ShotsCategory::where('sourceId', 0)->get();
        foreach ($category as $item){
            $lang = ShotsCategory::where('sourceId', $item->id)->where('lang', app()->getLocale())->first();
            if($lang != null)
                $item->name = $lang->name;
        }

        $shots = Shots::where('ourShot', 1)->get();
        foreach ($shots as $item){
            $item->youLike = 0;
            if(auth()->check()){
                $like = ShotsLike::where('shotId', $item->id)->where('userId', auth()->user()->id)->first();
                if($like != null)
                    $item->youLike = 1;
            }
            $item->pic200 = asset('uploaded/ourShots/200_' . $item->pic);
            $item->pic500 = asset('uploaded/ourShots/500_' . $item->pic);
            $item->pic    = asset('uploaded/ourShots/' . $item->pic);

            $item->tag = \DB::table('tags')
                ->join('shotsTagRelations', 'tags.id', '=', 'shotsTagRelations.tagId')
                ->where('shotsTagRelations.shotId', $item->id)
                ->select(['tags.tag'])->pluck('tag')->toArray();
        }

        return view('main.shotsPage', compact(['shots', 'category']));
    }

    public function likeShot(Request $request)
    {
        if(isset($request->id)) {
            $shot = Shots::find($request->id);
            if($shot != null) {
                $user = auth()->user();
                $shotLike = ShotsLike::where('shotId', $shot->id)->where('userId', $user->id)->first();
                if($shotLike == null){
                    $shotLike = new ShotsLike();
                    $shotLike->userId = $user->id;
                    $shotLike->shotId = $shot->id;
                    $shotLike->like = 1;
                    $shotLike->save();

                    $shot->like += 1;
                    $shot->save();

                    event(new makeLog([
                        'subject' => 'like_our_shots',
                        'referenceId' => $shot->id,
                        'referenceTable' => 'shots'
                    ]));

                    echo 'add';
                }
                else{
                    $shotLike->delete();
                    $shot->like -= 1;
                    $shot->save();
                    event(new makeLog([
                        'subject' => 'delete_like_our_shots',
                        'referenceId' => $shot->id,
                        'referenceTable' => 'shots'
                    ]));

                    echo 'delete';
                }
            }
            else
                echo 'notFound';
        }
        else
            echo 'nok';

        return;
    }
}
