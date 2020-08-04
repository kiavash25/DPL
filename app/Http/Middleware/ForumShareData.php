<?php

namespace App\Http\Middleware;

use App\models\ForumCategory;
use App\models\ForumTopic;
use App\User;
use Closure;
use Illuminate\Support\Facades\View;

class ForumShareData
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
        $sideCategory = ForumCategory::where('lang', app()->getLocale())->select(['id', 'name'])->get();
        foreach ($sideCategory as $item)
            $item->topics = ForumTopic::where('categoryId', $item->id)->count();

        $recentlyTopic = ForumTopic::where('lang', app()->getLocale())->select(['id', 'userId', 'title', 'categoryId'])->orderByDesc('created_at')->take(5)->get();
        foreach ($recentlyTopic as $item) {
            $item->username = User::find($item->userId)->name;
            $item->category = ForumCategory::find($item->categoryId)->name;
        }

        View::Share(['sideCategory' => $sideCategory, 'recentlyTopic' => $recentlyTopic]);

        return $next($request);
    }
}
