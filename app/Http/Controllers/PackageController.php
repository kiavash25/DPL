<?php

namespace App\Http\Controllers;

use App\models\Countries;
use App\models\Destination;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function listPackage()
    {
        return view('admin.package.listPackage');
    }

    public function newPackage()
    {
        $kind = 'new';
        $destinations = Destination::all()->groupBy('countryId');

        foreach ($destinations as $key => $item)
            $item->country = Countries::find($key);

        $allDestination = Destination::all();

        return view('admin.package.newPackage', compact(['kind', 'destinations', 'allDestination']));
    }

    public function editPackage($id)
    {
        return view('admin.package.newPackage');
    }
}
