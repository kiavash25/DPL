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
    public function mainPageJournal()
    {
        $today = Carbon::now()->format('Y-m-d');
        $mainSliderJournal = Journal::where('releaseDate' , '!=', 'draft')->where('lang', app()->getLocale())->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'pic', 'userId', 'releaseDate'])->orderByDesc('releaseDate')->take(5)->get();
        foreach ($mainSliderJournal as $item) {
            $item->username = User::find($item->userId);
            if($item->username != null)
                $item->username = $item->username->name;

            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
            $item->category = JournalCategory::find($item->categoryId);
            if($item->category != null)
                $item->category = $item->category->name;


            $item->date = Carbon::createFromFormat('Y-m-d', $item->releaseDate)->format('d F Y');
        }

        $showCategories = [];
        $categoryList = JournalCategory::where('viewOrder', '!=', 0)->where('lang', app()->getLocale())->orderByDesc('viewOrder')->get();
        foreach ($categoryList as $categ){
            $categ->journals = Journal::where('categoryId', $categ->id)->where('releaseDate' , '!=', 'draft')->where('releaseDate', '<=', $today)->where('lang', app()->getLocale())->select(['id', 'slug', 'name', 'summery', 'categoryId', 'pic', 'userId', 'releaseDate'])->orderByDesc('releaseDate')->take(5)->get();
            foreach ($categ->journals as $item) {
                $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
                $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
                if($item->category != null)
                    $item->category = $item->category->name;

                $item->username = User::find($item->userId);
                if($item->username != null)
                    $item->username = $item->username->name;
                $item->date = Carbon::createFromFormat('Y-m-d', $item->releaseDate)->format('d F Y');
            }

            if(count($categ->journals) > 0)
                array_push($showCategories,$categ);
        }


        if(count($mainSliderJournal) == 0)
            return redirect(url('/'));

        return view('journal.mainPageJournal', compact(['showCategories', 'mainSliderJournal']));
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
                $cateFit = JournalCategory::where('name', $value)->where('lang', app()->getLocale())->first();
                if($cateFit != null)
                    $query = ' categoryId = ' . $cateFit->id;
            }
            else if(isset($request->kind) && isset($request->value) && $request->kind == 'search'){
                $value = $request->value;
                $tags = Tags::where('tag', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $journalId = JournalTag::whereIn('tagId', $tags)->pluck('journalId')->toArray();

                $nameJournal = Journal::whereNotIn('id', $journalId)->where('lang', app()->getLocale())->where('name', 'LIKE', '%' . $value . '%')->pluck('id')->toArray();
                $journalId = array_merge($journalId, $nameJournal);

                $query = 'id IN (' . implode(",", $journalId) . ')';
            }


            if($query == '')
                $query = '1';

            $journals = Journal::whereRaw($query)->where('releaseDate' , '!=', 'draft')->where('lang', app()->getLocale())->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'summery', 'categoryId', 'releaseDate', 'pic', 'userId'])->orderByDesc('releaseDate')->skip($skip)->take($take)->get();

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

        $journal = Journal::where('id', $id)->first();
        if($journal == null || $journal->releaseDate > $today)
            return redirect(route('journal.index'));

        if(app()->getLocale() != $journal->lang){
            $url = route('journal.show', ['id' => $id, 'slug' => $slug]);
            return redirect(url('locale/'.$journal->lang.'?redirect='.$url));
        }

        $journal->pic = asset('uploaded/journal/mainPics/' . $journal->pic);
        $journal->category = JournalCategory::find($journal->categoryId);
        if($journal->category != null)
            $journal->category = $journal->category->name;

        $journal->date = Carbon::createFromFormat('Y-m-d', $journal->releaseDate)->format('d F Y');

        $journal->userPic = User::getUserPic($journal->userId);
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
