<?php

namespace App\Http\Controllers;

use App\models\City;
use App\models\Tags;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
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
}
