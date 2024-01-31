<?php

namespace App\Providers;

use App\Models\Curriculo;
use App\Policies\CurriculoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
//use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Curriculo::class => CurriculoPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
