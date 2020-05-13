<?php

namespace App\Http\Middleware;

use App\models\Activity;
use App\models\Destination;
use App\models\DestinationCategory;
use App\models\DestinationCategoryTitle;
use App\models\DestinationCategoryTitleText;
use App\models\Language;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\View;

class shareData
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
        $lang = app()->getLocale();
        $destCategory = DestinationCategory::where('lang', $lang)->get();
        foreach ($destCategory as $item) {
            $item->destination = Destination::where('categoryId', $item->id)->orderBy('name')->get();
            foreach ($item->destination as $dest){
                $titleId = DestinationCategoryTitleText::where('destId', $dest->id)->pluck('titleId')->toArray();
                $dest->titles = DestinationCategoryTitle::whereIn('id', $titleId)->pluck('name')->toArray();

                $dest->url = route('show.destination', ['slug' => $dest->slug]);
            }
        }

        $activitiesList = Activity::where('parent', 0)->where('lang', $lang)->get();
        foreach ($activitiesList as $item)
            $item->subAct = Activity::where('parent', $item->id)->where('lang', $lang)->get();

        $languages = Language::where('state', 1)->get();

        View::share(['destCategory' => $destCategory, 'activitiesList' => $activitiesList, 'languages' => $languages]);

        return $next($request);
    }
}
