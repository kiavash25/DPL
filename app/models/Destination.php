<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinations';

    public static function deleteWithPic($id){
        $dest = Destination::find($id);
        if($dest != null){
            $destTitlesId = DestinationCategoryTitleText::where('destId', $dest->id)->pluck('id')->toArray();
            foreach ($destTitlesId as $id)
                DestinationCategoryTitleText::deleteWithPic($id);

            $location = __DIR__ . '/../../public/uploaded/destination/' . $dest->id;

            DestinationTagRelation::where('destId', $dest->id)->delete();
            $pics = DestinationPic::where('destId', $dest->id)->get();
            foreach ($pics as $item){
                \File::delete('uploaded/destination/' . $item->destId . '/' . $item->pic);
                $item->delete();
            }

            if($dest->video != null)
                \File::delete('uploaded/destination/' . $dest->id . '/' . $dest->video);
            if($dest->podcast != null)
                \File::delete('uploaded/destination/' . $dest->id . '/' . $dest->podcast);
            if($dest->pic != null)
                \File::delete('uploaded/destination/' . $dest->id . '/' . $dest->pic);

            try {
                rmdir($location);
            }
            catch (\Exception $exception){}

            $dest->delete();

            return true;
        }
        else
            return false;

    }
}
