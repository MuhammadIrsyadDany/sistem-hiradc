<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Gate permissions
        try {
            \Spatie\Permission\Models\Permission::get()
                ->each(function ($permission) {
                    \Illuminate\Support\Facades\Gate::define(
                        $permission->name,
                        function ($user) use ($permission) {
                            return $user->hasPermissionTo($permission->name);
                        }
                    );
                });
        } catch (\Exception $e) {
            // Silence error saat migration belum jalan
        }

        // View Composer untuk data global
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (auth()->check()) {
                $view->with([
                    'sidebarTemuanDraft' => \App\Models\Temuan::where('status', 'draft')->count(),
                    'sidebarProgramOverdue' => \App\Models\ProgramKerja::where('status', 'overdue')->count(),
                ]);
            }
        });
    }
}
