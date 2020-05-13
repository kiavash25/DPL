<?php

namespace App\Http\Middleware;

use App\models\Language;
use Closure;

class Localization
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
        if(\Session::has('locale'))
        {
            $state = Language::where('symbol', \Session::get('locale'))->first();
            if((isset($state->state) && $state->state == 1) || \Session::get('locale') == 'en')
                \App::setlocale(\Session::get('locale'));
            else
                \App::setlocale('en');
        }
        return $next($request);
    }
}
