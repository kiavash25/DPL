<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Acl extends Model
{
//        $aclList = [
    //        'activity',
    //        'destination',
    //        'package',
    //        'journal',
    //        'setting',
    //        'userAccess',
//        ];
    protected $table = 'acls';
    public $timestamps = false;
}
