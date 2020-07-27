<?php

namespace App\Http\Controllers;

use App\models\Award;
use App\models\Language;
use App\models\MainPageSetting;
use App\models\MainPageSlider;
use App\models\SupportUs;
use Couchbase\TermRangeSearchQuery;
use DemeterChain\Main;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class SettingController extends Controller
{
    public function mainPageSlider()
    {
        $pics = MainPageSlider::where('lang', app()->getLocale())->orderByDesc('showNumber')->get();
        foreach ($pics as $pic)
            $pic->pic = asset('images/MainSliderPics/' . $pic->pic);

        return view('profile.admin.setting.mainPageSlider', compact(['pics']));
    }

    public function mainPageSliderStore(Request $request)
    {
        if(isset($request->id)){
            if($request->id == 0 && isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $location = __DIR__ . '/../../../public/images/MainSliderPics';

                if(!is_dir($location))
                    mkdir($location);

                $image = $request->file('pic');
                $size = [
                    [
                        'width' => 1500,
                        'height' => null,
                        'name' => '',
                        'destination' => 'images/MainSliderPics'
                    ]
                ];
                $fileName = resizeImage($image, $size);
                if($fileName != 'error'){
                    $maxNumber = MainPageSlider::where('lang', app()->getLocale())->orderByDesc('showNumber')->first();

                    if($maxNumber == null)
                        $maxNumber = 1;
                    else
                        $maxNumber = $maxNumber->showNumber;
                    $slider = new MainPageSlider();
                    $slider->pic = $fileName;
                    $slider->showNumber = $maxNumber+1;
                    $slider->lang = app()->getLocale();
                    $slider->save();
                }
                else{
                    echo json_encode(['status' => 'nok3']);
                    return;
                }
            }
            else if($request->id != 0){
                $slider = MainPageSlider::find($request->id);
                $slider->text = $request->text;
                $slider->link = $request->link;
                $slider->showNumber = $request->number;

                if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                    $location = __DIR__ . '/../../../public/images/MainSliderPics';

                    if(!is_dir($location))
                        mkdir($location);

                    $image = $request->file('pic');
                    $size = [
                        [
                            'width' => 1500,
                            'height' => null,
                            'name' => '',
                            'destination' => 'images/MainSliderPics'
                        ]
                    ];
                    $fileName = resizeImage($image, $size);

                    if(is_file($location.'/'.$slider->pic))
                        unlink($location.'/'.$slider->pic);

                    $slider->pic = $fileName;
                }
                $slider->save();
            }
            else{
                echo json_encode(['status' => 'nok1']);
                return;
            }

            $slider->pic = URL::asset('images/MainSliderPics/'.$slider->pic);
            echo json_encode(['status' => 'ok', 'result' => $slider]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
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
        return view('profile.admin.setting.language', compact(['lang']));
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

        $supportUs = SupportUs::all();
        foreach ($supportUs as $item) {
            $item->pic = asset('uploaded/mainPage/' . $item->pic);
            if($item->link == null)
                $item->link = '#';
        }

        return view('profile.admin.setting.mainPageSetting', compact(['aboutUs', 'center', 'supportUs']));
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

    public function storeSupportUs(Request $request)
    {
        if(isset($request->id) && isset($request->name)){
            if($request->id != 0)
                $sup = SupportUs::find($request->id);
            else
                $sup = new SupportUs();

            $sup->name = $request->name;
            $sup->link = $request->link;

            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $location = __DIR__ .'/../../../public/uploaded/mainPage';
                if(!is_dir($location))
                    mkdir($location);

                $image = $request->file('pic');
                $size = [
                    [
                        'width' => null,
                        'height' => 150,
                        'name' => '',
                        'destination' => 'uploaded/mainPage'
                    ]
                ];
                $fileName = resizeImage($image, $size);
                $sup->pic = $fileName;
            }

            $sup->save();

            $sup->pic = asset('uploaded/mainPage/' . $sup->pic);

            if($sup->link == null)
                $sup->link = '#';

            echo json_encode(['status' => 'ok', 'result' => json_encode($sup)]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function deleteSupportUs(Request $request)
    {
        if(isset($request->id)){
            $sup = SupportUs::find($request->id);
            $location = __DIR__ .'/../../../public/uploaded/mainPage/' . $sup->pic;
            if(is_file($location))
                unlink($location);
            $sup->delete();

            echo 'ok';
        }
        else
            echo 'nok';

        return;
    }

    public function awardSetting()
    {
        $awards = Award::where('lang', app()->getLocale())->get();
        foreach ($awards as $award)
            $award->pic = URL::asset('images/awards/'.$award->pic);

        return view('profile.admin.setting.awards', compact(['awards']));
    }

    public function awardStore(Request $request)
    {
        if(isset($request->id) && isset($request->name)){
            if($request->id == 0 && isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $location = __DIR__ . '/../../../public/images/awards';

                if(!is_dir($location))
                    mkdir($location);

                $image = $request->file('pic');
                $size = [
                    [
                        'width' => 700,
                        'height' => null,
                        'name' => '',
                        'destination' => 'images/awards'
                    ]
                ];
                $fileName = resizeImage($image, $size);
                if($fileName != 'error'){
                    $award = new Award();
                    $award->pic = $fileName;
                    $award->name = $request->name;
                    $award->link = $request->link;
                    $award->lang = app()->getLocale();
                    $award->save();
                }
                else{
                    echo json_encode(['status' => 'nok3']);
                    return;
                }
            }
            else if($request->id != 0){
                $award = Award::find($request->id);
                $award->name = $request->name;
                $award->link = $request->link;

                if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                    $location = __DIR__ . '/../../../public/images/awards';

                    if(!is_dir($location))
                        mkdir($location);

                    $image = $request->file('pic');
                    $size = [
                        [
                            'width' => 700,
                            'height' => null,
                            'name' => '',
                            'destination' => 'images/awards'
                        ]
                    ];
                    $fileName = resizeImage($image, $size);

                    if(is_file($location.'/'.$award->pic))
                        unlink($location.'/'.$award->pic);

                    $award->pic = $fileName;
                }
                $award->save();
            }
            else{
                echo json_encode(['status' => 'nok1']);
                return;
            }

            $award->pic = URL::asset('images/awards/'.$award->pic);
            echo json_encode(['status' => 'ok', 'result' => $award]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function awardDelete(Request $request)
    {
        if(isset($request->id)){
            $pic = Award::find($request->id);
            $location = __DIR__ . '/../../../public/images/awards/' . $pic->pic;
            if(is_file($location))
                unlink($location);
            $pic->delete();
            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }
}
