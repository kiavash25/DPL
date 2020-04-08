<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\Journal;
use App\models\JournalCategory;
use App\models\JournalTag;
use App\models\Tags;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class JournalController extends Controller
{
    public function __construct()
    {
        $allCategory = JournalCategory::all();

        $today = Carbon::now()->format('Y-m-d');

        $recentlyJournal = Journal::where('releaseDate' , '!=', 'draft')->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'categoryId', 'pic', 'releaseDate'])->orderByDesc('releaseDate')->take(3)->get();
        foreach ($recentlyJournal as $item){
            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
            $item->category = JournalCategory::find($item->categoryId);
            $item->date = Carbon::createFromFormat('Y-m-d', $item->releaseDate)->format('d F Y');
            if($item->category != null)
                $item->category = $item->category->name;
        }
//
//        $destCategory = DestinationCategory::all();
//        foreach ($destCategory as $item)
//            $item->destination = Destination::where('categoryId', $item->id)->get();
//
//        $activitiesList = Activity::all();

//        , 'destCategory' => $destCategory, 'activitiesList' => $activitiesList

        View::share(['allCategory' => $allCategory, 'recentlyJournal' => $recentlyJournal]);
    }

    public function mainPageJournal()
    {
        $today = Carbon::now()->format('Y-m-d');
        $mainSliderJournal = Journal::where('releaseDate' , '!=', 'draft')->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'pic'])->orderByDesc('releaseDate')->take(3)->get();
        foreach ($mainSliderJournal as $item) {
            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
            $item->category = JournalCategory::find($item->categoryId);
            if($item->category != null)
                $item->category = $item->category->name;
        }

        $journals = Journal::where('releaseDate' , '!=', 'draft')->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'pic'])->orderByDesc('releaseDate')->take(3)->get();
        foreach ($journals as $item) {
            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
            $item->category = JournalCategory::find($item->categoryId);
            if($item->category != null)
                $item->category = $item->category->name;
        }

        return view('journal.mainPageJournal', compact(['journals', 'mainSliderJournal']));
    }

    public function getElems(Request $request)
    {
        if(isset($request->take) && isset($request->skip)){
            $today = Carbon::now()->format('Y-m-d');
            $take = $request->take;
            $skip = $request->skip;
            $query = '';

            if(isset($request->kind) && isset($request->value) && $request->kind == 'category') {
                $value = $request->value;
                $cateFit = JournalCategory::where('name', $value)->first();
                if($cateFit != null)
                    $query = ' categoryId = ' . $cateFit->id;
            }
            else if(isset($request->kind) && isset($request->value) && $request->kind == 'search'){
                $value = $request->value;
                $tags = Tags::where('tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $journalId = JournalTag::whereIn('tagId', $tags)->pluck('journalId')->toArray();

                $nameJournal = Journal::whereNotIn('id', $journalId)->where('name', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $journalId = array_merge($journalId, $nameJournal);

                $query = 'id IN (' . implode(",", $journalId) . ')';
            }


            if($query == '')
                $query = '1';

            $journals = Journal::whereRaw($query)->where('releaseDate' , '!=', 'draft')->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'releaseDate', 'pic', 'userId'])->orderByDesc('releaseDate')->skip($skip)->take($take)->get();

            foreach ($journals as $item) {
                $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
                $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
                $item->date = Carbon::createFromFormat('Y-m-d', $item->releaseDate)->format('d F Y');
                $item->category = JournalCategory::find($item->categoryId);
                if($item->category != null) {
                    $item->category = $item->category->name;
                    $item->categoryUrl = route('journal.list', ['kind' => 'category', 'value' => $item->category]);
                }
                else{
                    $item->category = null;
                    $item->categoryUrl = '#';
                }
                $item->username = User::find($item->userId);
                if($item->username != null)
                    $item->username = $item->username->name;

            }

            echo json_encode(['status' => 'ok', 'result' => $journals]);
        }
        else
            echo json_encode(['status' => 'nok1']);
        return;
    }

    public function showJournalContent($id, $slug)
    {
        $today = Carbon::now()->format('Y-m-d');

        $journal = Journal::find($id);
        if($journal == null || $journal->releaseDate > $today)
            return redirect(route('journal.index'));

        $journal->pic = asset('uploaded/journal/mainPics/' . $journal->pic);
        $journal->category = JournalCategory::find($journal->categoryId);
        if($journal->category != null)
            $journal->category = $journal->category->name;

        $journal->date = Carbon::createFromFormat('Y-m-d', $journal->releaseDate)->format('d F Y');

        $journal->username = User::find($journal->userId);

        if($journal->username != null)
            $journal->username = $journal->username->name;

        $tags = JournalTag::where('journalId', $journal->id)->pluck('tagId')->toArray();
        if(count($tags) > 0)
            $journal->tag = Tags::whereIn('id', $tags)->pluck('tag')->toArray();

        $sideNavChoose = $journal->category;

        return view('journal.contentJournal', compact(['journal', 'sideNavChoose']));
    }


    public function listJournal($kind, $value = '')
    {
        $listKind = $kind;
        $listValue = $value;

        $sideNavChoose = $value;

        return view('journal.listJournal', compact(['listKind', 'listValue', 'sideNavChoose']));
    }
}
