<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journals';


    public static function deleteWithPics($id){
        $journal = Journal::find($id);
        if($journal == null)
            return false;
        else{
            JournalTag::where('journalId', $journal->id)->delete();
            $pics = JournalPic::where('journalId', $journal->id)->get();
            $location = __DIR__ . '../../public/uploaded/journal';
            foreach ($pics as $pic){
//                if(is_file($location . '/description/' . $pic->pic))
//                    unlink($location . '/description/' . $pic->pic);
                \File::delete('uploaded/journal/description/' . $pic->pic);
                $pic->delete();
            }
//            if(is_file($location . '/mainPics/' . $pic->pic))
//                unlink($location . '/mainPics/' . $pic->pic);
            \File::delete('uploaded/journal/mainPics/' . $journal->pic);
            $journal->delete();

            return true;
        }
    }
}
