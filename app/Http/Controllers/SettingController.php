<?php

namespace App\Http\Controllers;

use App\models\Language;
use App\models\MainPageSlider;
use Couchbase\TermRangeSearchQuery;
use DemeterChain\Main;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SettingController extends Controller
{
    public function mainPageSlider()
    {
        $pics = MainPageSlider::orderByDesc('showNumber')->get();
        foreach ($pics as $pic)
            $pic->pic = asset('images/MainSliderPics/' . $pic->pic);

        return view('admin.setting.mainPageSlider', compact(['pics']));
    }

    public function mainPageSliderStore(Request $request)
    {
        $msg = '';
        if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
            $location = __DIR__ . '/../../../public/images/MainSliderPics';

            if(!is_dir($location))
                mkdir($location);

            $image = $request->file('pic');
            $size = [
                [
                    'width' => null,
                    'height' => 450,
                    'name' => '',
                    'destination' => 'images/MainSliderPics'
                ]
            ];
            $fileName = resizeImage($image, $size);
            if($fileName != 'error'){
                $maxNumber = MainPageSlider::orderByDesc('showNumber')->first()->showNumber;
                $pic = new MainPageSlider();
                $pic->pic = $fileName;
                $pic->showNumber = $maxNumber+1;
                $pic->save();
            }
            else
                $msg = '?error=error2';
        }
        else
            $msg = '?error=error1';

         return redirect(route('admin.setting.mainPageSlider') . $msg);
    }

    public function mainPageSliderChangeNumber(Request $request)
    {
        if(isset($request->id) && isset($request->newNumber)){
            $npic = MainPageSlider::find($request->id);
            $lPic = MainPageSlider::where('showNumber', $request->newNumber)->first();
            if($npic != null && $lPic != null){
                $lPic->showNumber = $npic->showNumber;
                $lPic->save();

                $npic->showNumber = $request->newNumber;
                $npic->save();

                echo json_encode(['status' => 'ok', 'prev' => $lPic->showNumber]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function mainPageSliderDelete(Request $request)
    {
        if(isset($request->id)){
            $pic = MainPageSlider::find($request->id);
            $location = __DIR__ . '/../../../public/images/MainSliderPics/' . $pic->pic;
            if(is_file($location))
                unlink($location);
            $pic->delete();
            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function languagePage()
    {
        $lang = Language::all();
        return view('admin.setting.language', compact(['lang']));
    }

    public function storeLanguage(Request $request)
    {
        if(isset($request->name) && isset($request->id) && isset($request->symbol)){
            $check = Language::where('name', $request->name)->where('id', '!=', $request->id)->first();
            if($check == null){
                if($request->id == 0)
                    $lang = new Language();
                else {
                    $lang = Language::find($request->id);
                    if($lang == null){
                        echo json_encode(['status' => 'nok1']);
                        return;
                    }
                }

                $lang->name = $request->name;
                $lang->symbol = $request->symbol;
                $lang->direction = $request->dir;
                $lang->state = $request->state;
                $lang->save();

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;

    }

    public function deleteLanguage(Request $request){

    }
}
