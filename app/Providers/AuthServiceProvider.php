<?php

namespace App\Providers;

use App\models\Acl;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('canSee', function ($user, $role){
           $acl = Acl::where('userId', $user->id)->where('role', $role)->first();
           if($acl != null || $user->level == 'superAdmin')
               return true;
           else
               return false;
        });
    }
}
