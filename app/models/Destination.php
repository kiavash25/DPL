<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $guarded = [];
    protected $table = 'destinations';

    public static function deleteWithPic($id){
        $dest = Destination::find($id);
        if($dest != null){
            $destTitlesId = DestinationCategoryTitleText::where('destId', $dest->id)->pluck('id')->toArray();
            foreach ($destTitlesId as $id)
                DestinationCategoryTitleText::deleteWithPic($id);

            DestinationTagRelation::where('destId', $dest->id)->delete();
            DestinationPic::where('destId', $dest->id)->delete();

            $location = __DIR__ . '/../../public/uploaded/destination/' . $dest->id;
            emptyFolder($location);

            Destination::where('langSource', $dest->id)->update(['langSource' => 0]);

            $dest->delete();

            return true;
        }
        else
            return false;

    }
}
