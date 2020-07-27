<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\View;


class ShareProfile
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
        $profile = null;
        if(auth()->check()){
            $user = auth()->user();
            $profile['pic'] = User::getUserPic($user->id);
        }
        else{
            $profile['pic'] = User::getUserPic(0);
        }

        View::share(['profile' => $profile]);

        return $next($request);
    }
}
