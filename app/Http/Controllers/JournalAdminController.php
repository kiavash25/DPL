<?php

namespace App\Http\Controllers;

use App\models\DestinationTagRelation;
use App\models\Journal;
use App\models\JournalCategory;
use App\models\JournalPic;
use App\models\JournalPicLimbo;
use App\models\JournalTag;
use App\models\Tags;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Exception;

class JournalAdminController extends Controller
{
    public function indexCategory()
    {
        $category = JournalCategory::all();
        return view('admin.journal.categoryIndex', compact(['category']));
    }

    public function storeCategory(Request $request)
    {
        if(isset($request->name)){
            $check = JournalCategory::where('name', $request->name)->first();
            if($check == null){
                $category = new JournalCategory();
                $category->name = $request->name;
                $category->save();

                echo json_encode(['status' => 'ok', 'result' => $category->id]);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function editCategory(Request $request){
        if(isset($request->id) && isset($request->name) && $request->name != ''){
            $check = JournalCategory::where('name', $request->name)->where('id', '!=', $request->id)->first();
            if($check == null){
                $category = JournalCategory::find($request->id);
                if($category != null){
                    $category->name = $request->name;
                    $category->save();

                    echo json_encode(['status' => 'ok']);
                }
                else
                    echo json_encode(['status' => 'nok2']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }


    public function indexJournal()
    {
        $this->deleteLimboPic();

        $journal = Journal::all();
        foreach ($journal as $item){
            $item->category = JournalCategory::find($item->categoryId);
            if($item->releaseDate == null)
                $item->releaseDate = 'draft';
            $item->user = User::find($item->userId);
        }

        return view('admin.journal.listJournal', compact(['journal']));
    }

    public function newJournal()
    {
        $kind = 'new';
        $category = JournalCategory::all();
        $code = random_int(10000, 999999);

        return view('admin.journal.newJournal', compact(['kind', 'category', 'code']));
    }

    public function editJournal($id)
    {
        $kind = 'edit';
        $journal = Journal::find($id);
        if($journal == null)
            return redirect(route('admin.journal.list'));

        if($journal->releaseDate != 'draft') {
            if ($journal->releaseDate > Carbon::now()->format('Y-m-d'))
                $journal->releaseDateType = 'future';
            else
                $journal->releaseDateType = 'now';
        }

        $journal->pic = asset('uploaded/journal/mainPics/' .$journal->pic);

        $tags = JournalTag::where('journalId', $id)->pluck('tagId')->toArray();
        if(count($tags) != 0)
            $journal->tags = Tags::whereIn('id', $tags)->pluck('tag')->toArray();
        else
            $journal->tags = [];

        $category = JournalCategory::all();
        $code = 0;

        return view('admin.journal.newJournal', compact(['journal', 'kind', 'code', 'category']));
    }

    public function storeDescriptionImgJournal(Request $request)
    {
        $id = json_decode($request->data)->id;
        $code = json_decode($request->data)->code;

        if( $_FILES['file'] && $_FILES['file']['error'] == 0){
            $fileName = time() . $_FILES['file']['name'];
            $location = __DIR__ . '/../../../public/uploaded/journal/description';
            if (!file_exists($location))
                mkdir($location);

            $location .= '/' . $fileName;
            if (compressImage($_FILES['file']['tmp_name'], $location, 80)) {
                if($id != 0){
                    $journal = Journal::find($id);
                    if($journal == null){
                        unlink($location);
                        echo false;
                    }
                    else{
                        $journalPic = new JournalPic();
                        $journalPic->journalId = $journal->id;
                        $journalPic->pic = $fileName;
                        $journalPic->save();
                    }
                }
                else if($code != 0){
                    $journalPic = new JournalPic();
                    $journalPic->code = $code;
                    $journalPic->pic = $fileName;
                    $journalPic->save();
                }
                else{
                    unlink($location);
                    echo false;
                }

                echo json_encode(['url' => asset('uploaded/journal/description/' . $fileName)]);
            }
            else
                echo false;
        }
        else
            echo false;

        return;
    }

    public function storeJournal(Request $request)
    {
        if(isset($request->code) && isset($request->id) && isset($request->name) && isset($request->categoryId)){
            if($request->code == 0 && $request->id != 0){
                $kind = 'edit';
                $journal = Journal::find($request->id);
                if($journal == null){
                    echo json_encode(['status' => 'nok2']);
                    return;
                }
            }
            else if($request->id == 0 && $request->code != 0){
                $kind = 'new';
                $journal = new Journal();
                $journal->userId = Auth::user()->id;
                if(!isset($_FILES['pic']) || $_FILES['pic']['error'] != 0){
                    echo json_encode(['status' => 'nok3']);
                    return;
                }
            }
            else{
                echo json_encode(['status' => 'nok1']);
                return;
            }

            $journal->name = $request->name;
            $journal->slug = makeSlug($request->name);
            $journal->categoryId= $request->categoryId;
            $journal->summery = $request->summery;
            $journal->text = $request->description;
            if($request->releaseDateType == 'now')
                $journal->releaseDate = Carbon::now()->format('Y-m-d');
            else if($request->releaseDateType == 'future' && isset($request->releaseDate) && $request->releaseDate != null)
                $journal->releaseDate = $request->releaseDate;
            $journal->save();

            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                $fileName = time() . $_FILES['pic']['name'];

                $location = __DIR__ . '/../../../public/uploaded/journal/';
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/mainPics';
                if(!file_exists($location))
                    mkdir($location);

                $location .= '/' . $fileName;

                if(compressImage($_FILES['pic']['tmp_name'], $location, 80)){
                    \File::delete('uploaded/journal/mainPics/' . $journal->pic);
                    $journal->pic = $fileName;
                    $journal->save();
                }
            }

            $tags = json_decode($request->tags);
            $query = '';
            foreach ($tags as $tag){
                $t = Tags::where('tag', $tag)->first();
                if($t == null){
                    $t = new Tags();
                    $t->tag = $tag;
                    $t->save();
                }

                if($query != '')
                    $query .= ' ,';
                $query .= '(Null, ' . $journal->id . ', ' . $t->id . ')';
            }

            JournalTag::where('journalId', $journal->id)->delete();
            if($query != '')
                \DB::select('INSERT INTO journalTags (id, journalId, tagId) VALUES ' . $query);

            if($kind == 'new' && $request->code != 0)
                JournalPic::where('code', $request->code)->update(['journalId' => $journal->id, 'code' => null]);

            $journalPics = JournalPic::where('journalId', $journal->id)->get();
            foreach ($journalPics as $pic){
                if(strpos($journal->text, $pic->pic) === false){
                    \File::delete('uploaded/journal/description/' . $pic->pic);
                    $pic->delete();
                }
            }

            echo json_encode(['status' => 'ok', 'result' => $journal->id]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    private function deleteLimboPic(){
        $limbo = JournalPic::whereNull('journalId')->get();
        foreach ($limbo as $item){
            $diff = Carbon::now()->diffInDays($item->created_at);
            if($diff > 0){
                \File::delete('uploaded/journal/description/' . $item->pic);
                $item->delete();
            }
        }

        $location = __DIR__ . '/../../../public/uploaded/journalsLimbo';
        if(is_dir($location)){
            $scan = scandir($location);
            foreach ($scan as $item) {
                if($item != '.' && $item != '..'){
                    try {
                        rmdir($location . '/' . $item);
                    }
                    catch (\Exception $exception){
                        continue;
                    }
                }
            }
        }

    }

    public function deleteJournal(Request $request)
    {
        if(isset($request->id)){
            if(Journal::deleteWithPics($request->id))
                echo json_encode(['status' => 'ok']);
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

}
