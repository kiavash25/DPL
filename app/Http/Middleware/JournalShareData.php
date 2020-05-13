<?php

namespace App\Http\Middleware;

use App\models\Journal;
use App\models\JournalCategory;
use App\models\Language;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\View;

class JournalShareData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allCategory = JournalCategory::where('lang', app()->getLocale())->get();

        $today = Carbon::now()->format('Y-m-d');

        $recentlyJournal = Journal::where('releaseDate' , '!=', 'draft')->where('lang', app()->getLocale())->where('releaseDate', '<=', $today)->select(['id', 'slug', 'name', 'categoryId', 'pic', 'releaseDate'])->orderByDesc('releaseDate')->take(3)->get();
        foreach ($recentlyJournal as $item){
            $item->pic = asset('uploaded/journal/mainPics/' . $item->pic);
            $item->url = route('journal.show', ['id' => $item->id, $item->slug]);
            $item->category = JournalCategory::find($item->categoryId);
            $item->date = Carbon::createFromFormat('Y-m-d', $item->releaseDate)->format('d F Y');
            if($item->category != null)
                $item->category = $item->category->name;
        }

        $languages = Language::all();
        View::share(['allCategory' => $allCategory, 'recentlyJournal' => $recentlyJournal, 'languages' => $languages]);

        return $next($request);
    }
}
