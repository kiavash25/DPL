<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function listPackage()
    {
        return view('admin.package.listPackage');
    }

    public function newPackage()
    {
        return view('admin.package.newPackage');
    }

    public function editPackage($id)
    {
        return view('admin.package.newPackage');
    }
}
