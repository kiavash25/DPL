<?php

namespace App\Http\Controllers;

use App\models\City;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\Package;
use App\models\Tags;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function forgetPass()
    {
        $result = forgetPassEmail('kiavash', 'kiavashriddler@gmail.com', '4787287923047');
        dd($result);
    }

    public function findCity(Request $request)
    {
        if(isset($request->city) && isset($request->countryId)){
            $cities = City::where('countryId', $request->countryId)->where('name', 'like', '%' . $request->city . '%')->get();
            echo json_encode(['ok', $cities]);
        }
        else
            echo json_encode(['nok']);

        return;
    }

    public function findTag(Request $request)
    {
        if(isset($request->tag) && $request->tag != ''){
            $tags = Tags::where('tag', 'like', '%' . $request->tag . '%')->get();
            echo json_encode(['ok', $tags]);
        }
        else
            echo json_encode(['nok']);

        return;
    }

    function findDestination(Request $request){
        if(isset($request->name) && $request->name != ''){
            $destination = Destination::where('name', 'LIKE', '%' . $request->name . '%')->where('lang', app()->getLocale())->get();
            echo json_encode(['status' => 'ok', 'result' => $destination]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function search(Request $request)
    {
        if(isset($request->value) && $request->value != ''){
            $value = $request->value;

            $destiantion = Destination::where('name', 'LIKE', '%' . $value . '%')->where('lang', app()->getLocale())->get();
            foreach ($destiantion as $item){
                $item->url = route('show.destination', ['slug' => $item->slug]);
                $item->kind = 'Destination';
            }

            $package = Package::where('name', 'LIKE', '%' . $value . '%')->where('lang', app()->getLocale())->get();
            foreach ($package as $item){
                $dest = Destination::find($item->destId);
                $item->url = route('show.package', ['slug' => $item->slug]);
                $item->kind = 'Package';
            }

            $result = [
                __('Destination') => $destiantion,
                __('Package') => $package
            ];

            echo json_encode(['status' => 'ok', 'result' => $result]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

}
