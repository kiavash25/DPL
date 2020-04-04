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
            foreach ($pics as $pic){
                \File::delete('uploaded/journal/description/' . $pic->pic);
                $pic->delete();
            }
            \File::delete('uploaded/journal/mainPics/' . $journal->pic);
            $journal->delete();

            return true;
        }
    }
}
