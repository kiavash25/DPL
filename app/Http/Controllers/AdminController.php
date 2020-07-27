<?php

namespace App\Http\Controllers;

use App\models\City;
use App\models\Destination;
use App\models\Journal;
use App\models\Package;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function adminIndex()
    {
        $destinationCount = Destination::where('lang', app()->getLocale())->count();
        $packageCount = Package::where('lang', app()->getLocale())->count();
        $journalCount = Journal::where('lang', app()->getLocale())->count();

        return view('profile.admin.adminIndex', compact(['destinationCount', 'packageCount', 'journalCount']));
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
