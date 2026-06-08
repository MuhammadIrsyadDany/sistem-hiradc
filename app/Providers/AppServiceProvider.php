<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;

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
        /*
        |--------------------------------------------------------------------------
        | FORCE HTTPS UNTUK NGROK
        |--------------------------------------------------------------------------
        */
        // URL::forceScheme('https');

        /*
        |--------------------------------------------------------------------------
        | Gate Permissions
        |--------------------------------------------------------------------------
        */
        try {
            Permission::get()->each(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission->name);
                });
            });
        } catch (\Exception $e) {
            // Silence error saat migration belum jalan
        }

        /*
        |--------------------------------------------------------------------------
        | View Composer Global Data
        |--------------------------------------------------------------------------
        */
        View::composer('*', function ($view) {

            if (auth()->check()) {

                $view->with([

                    'sidebarTemuanDraft' =>
                    \App\Models\Temuan::where('status', 'draft')->count(),

                    'sidebarProgramOverdue' =>
                    \App\Models\ProgramKerja::where('status', 'overdue')->count(),

                ]);
            }
        });
    }
}
