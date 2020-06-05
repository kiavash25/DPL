<?php

namespace App\Http\Controllers;

use App\models\Language;
use App\models\MainPageSetting;
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
                $lang->currencyName = $request->currencyName;
                $lang->currencySymbol = $request->currencySymbol;
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

    public function mainPage()
    {
        $aboutUs = MainPageSetting::where('header', 'aboutus')->where('lang', app()->getLocale())->first();
        if($aboutUs != null)
            $aboutUs->pic = asset('uploaded/mainPage/' . $aboutUs->pic);

        $center = MainPageSetting::where('header', '!=', 'aboutus')->where('lang', app()->getLocale())->get();
        foreach ($center as $pic)
            $pic->pic = asset('uploaded/mainPage/' . $pic->pic);

        return view('admin.setting.mainPageSetting', compact(['aboutUs', 'center']));
    }

    public function storeAboutUsPic(Request $request)
    {
        if(isset($request->header)){
            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $set = MainPageSetting::where('header', $request->header)->where('lang', app()->getLocale())->first();
                if($request->header == 'aboutus' && $set == null){
                    $set = new MainPageSetting();
                    $set->header = 'aboutus';
                    $set->text = '';
                    $set->lang = app()->getLocale();
                }

                if($set != null) {
                    $location = __DIR__ .'/../../../public/uploaded/mainPage';
                    if(!is_dir($location))
                        mkdir($location);

                    $fileName = time().$_FILES['pic']['name'];
                    move_uploaded_file($_FILES['pic']['tmp_name'], $location . '/' . $fileName);

                    $set->pic = $fileName;
                    $set->save();

                    echo json_encode(['status' => 'ok', 'url' => asset('uploaded/mainPage/' . $fileName)]);
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

    public function storeAboutUs(Request $request)
    {
        if(isset($request->id)){
            $set = MainPageSetting::where('header', $request->id)->where('lang', app()->getLocale())->first();
            if($set == null) {
                $set = new MainPageSetting();
                $set->header = 'aboutus';
                $set->pic = '';
                $set->lang = app()->getLocale();
            }

            if($set != null) {
                $set->text = $request->text;
                $set->save();

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok2']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function storeCenterHeaderPic(Request $request)
    {
        if(isset($request->id) && isset($request->header)){
            if($request->id != 0)
                $header = MainPageSetting::find($request->id);
            else{
                $header = new MainPageSetting();
                $header->lang = app()->getLocale();
                $header->text = '';
            }
            $header->header = $request->header;
            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $location = __DIR__ .'/../../../public/uploaded/mainPage';
                if(!is_dir($location))
                    mkdir($location);

                $fileName = time().$_FILES['pic']['name'];
                move_uploaded_file($_FILES['pic']['tmp_name'], $location . '/' . $fileName);
                $header->pic = $fileName;
            }

            $header->save();

            $header->pic = asset('uploaded/mainPage/' . $header->pic);

            echo json_encode(['status' => 'ok', 'result' => json_encode($header)]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteCenterHeaderPic(Request $request)
    {
        if(isset($request->id)){
            $center = MainPageSetting::find($request->id);
            $location = __DIR__ .'/../../../public/uploaded/mainPage/' . $center->pic;
            if(is_file($location))
                unlink($location);
            $center->delete();

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }
}
