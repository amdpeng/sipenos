<?php

namespace App\Providers;

use App\Filament\Resources\LatestNomorKeluarResource\Widgets\NomorKeluarOverview as WidgetsNomorKeluarOverview;
use App\Policies\NomorKeluarOverviewPolicy;
use App\Filament\Widgets\NomorKeluarOverview;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
