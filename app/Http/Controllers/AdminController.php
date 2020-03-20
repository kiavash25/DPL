<?php

namespace App\Http\Controllers;

use App\models\City;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function adminIndex(){

        return view('admin.adminIndex');
    }


    public function addCity(Request $request)
    {
        if(isset($request->city) && $request->city != '' && isset($request->countryId)){
            $city = City::where('name', $request->city)->where('countryId', $request->countryId)->get();
            if($city == null || count($city) == 0){
                $city = new City();
                $city->name = $request->city;
                $city->countryId = $request->countryId;
                $city->save();

                echo json_encode(['ok', $city->id]);
            }
            else
                echo json_encode(['nok2']);
        }
        else
            echo json_encode(['nok']);

        return;
    }

}
