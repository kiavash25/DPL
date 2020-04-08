<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class DestinationCategoryTitleText extends Model
{
    protected $table = 'destinationCategoryTitleTexts';
    public $timestamps = false;

    public static function deleteWithPic($id){
        $item = DestinationCategoryTitleText::find($id);
        if($item != null) {
            $destination = Destination::find($item->destId);
            if ($destination != null) {
                $location = __DIR__ . '/../../public/uploaded/destination/' . $destination->id;
                if (is_dir($location)) {
                    $location .= '/' . $item->titleId;
                    if (is_dir($location)) {
                        $dirs = scandir($location);
                        foreach ($dirs as $dir) {
                            if (is_file($location . '/' . $dir))
                                unlink($location . '/' . $dir);
                        }
                        try {
                            rmdir($location);
                        } catch (\Exception $exception) {
                        }
                    }
                }
            }
            $item->delete();
            return true;
        }
        else
            return false;
    }
}
