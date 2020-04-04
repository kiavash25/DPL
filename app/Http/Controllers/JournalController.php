<?php

namespace App\Http\Controllers;

use App\models\Journal;
use App\models\JournalCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class JournalController extends Controller
{
    public function __construct()
    {
        $allCategory = JournalCategory::all();

        View::share(['allCategory' => $allCategory]);
    }

    public function mainPageJournal()
    {
        $today = Carbon::now()->format('Y-m-d');
        $journals = Journal::where('releaseDate' , '!=', 'draft')->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'pic'])->take(10)->get();
        foreach ($journals as $item) {
            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = url('/');
            $item->category = JournalCategory::find($item->categoryId);
            if($item->category != null)
                $item->category = $item->category->name;
        }

        return view('journal.mainPageJournal', compact(['journals']));
    }
}
