<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class NatureFriend extends Model
{
    protected $table = 'natureFriends';

    public static function deleteWithPic($id){
        $nat = NatureFriend::find($id);
        if($nat != null){
            NatureFriendPic::where('natId', $nat->id)->delete();
            $location = __DIR__ . '/../../public/uploaded/natureFriend/' . $nat->id;
            emptyFolder($location);
            $nat->delete();
            return true;
        }
        else
            return false;

    }
}
