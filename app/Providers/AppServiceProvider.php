<?php

namespace App\Providers;

use App\Models\Norakstishana;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Nav nepieciešams reģistrēt pakalpojumus.
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('inc.header', function ($view): void {
            $pendingNorakstishanaCount = 0;

            if (Auth::check() && Auth::user()->admina_tiesibas) {
                $pendingNorakstishanaCount = Norakstishana::query()
                    ->where('akceptets', false)
                    ->count();
            }

            $view->with('pendingNorakstishanaCount', $pendingNorakstishanaCount);
        });
    }
}
