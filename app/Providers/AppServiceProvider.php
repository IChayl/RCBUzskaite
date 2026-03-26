<?php

namespace App\Providers;

use App\Models\Norakstishana;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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

        Blade::directive('lvDate', function ($expression): string {
            return "<?php echo blank({$expression}) ? '-' : e(" . Carbon::class . "::parse({$expression})->locale('lv')->translatedFormat('j. F Y')); ?>";
        });

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
