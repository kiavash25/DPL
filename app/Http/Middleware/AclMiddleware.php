<?php

namespace App\Http\Middleware;

use App\models\Acl;
use Closure;
use http\Client\Curl\User;

class AclMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if(auth()->check()) {
            $user = auth()->user();

            if($user->level == 'superAdmin')
                return $next($request);

            if ($user->level == 'admin') {

                $language = Acl::where('userId', $user->id)->where('role', 'language')->where('value', app()->getLocale())->first();
                if($language == null) {
                    $language = Acl::where('userId', $user->id)->where('role', 'language')->first();
                    if($language == null) {
                        \Session::put('locale', 'en');
                        \App::setlocale('en');
                    }
                    else {
                        \Session::put('locale', $language->value);
                        \App::setlocale($language->value);
                    }
                }

                $check = Acl::where('userId', $user->id)->where('role', $role)->first();
                if ($check != null || $role == 'admin')
                    return $next($request);
            }
        }

        return redirect(url('/'));
    }
}
