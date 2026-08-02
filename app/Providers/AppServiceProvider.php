<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Institution;
use App\Policies\InstitutionPolicy;
use Illuminate\Support\Facades\Gate;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
        {
            Gate::policy(
                Institution::class,
                InstitutionPolicy::class
            );
        }
    }
