<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

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
        // Définir la langue française pour Carbon
        Carbon::setLocale('fr');

        // Macro personnalisée pour une durée courte et localisée
        Carbon::macro('diffCourtPourHumains', function () {
            /** @var \Carbon\Carbon $this */
            $diff = $this->diffForHumans(now(), [
                'parts' => 1,
                'join' => true,
                'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                'short' => true,
            ]);

            // Traduction des abréviations anglaises vers le français
            return str_replace(
                ['sec', 'min', 'h', 'd', 'w', 'mo', 'yr'],
                ['s', 'min', 'h', 'j', 'sem', 'mois', 'an'],
                $diff
            );
        });

        // Injecter les tickets non traités dans toutes les vues
        if (!app()->runningInConsole()) {
            View::composer('*', function ($view) {
                if (Auth::check()) {
                    $userId = Auth::id();

                    $countTicketsNonTraites = Ticket::where('technicien_id', $userId)
                        ->whereIn('status', ['nouveau', 'en_cours'])
                        ->count();

                    $ticketsNonTraites = Ticket::where('technicien_id', $userId)
                        ->whereIn('status', ['nouveau', 'en_cours'])
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $view->with(compact('countTicketsNonTraites', 'ticketsNonTraites'));
                } else {
                    $view->with([
                        'countTicketsNonTraites' => 0,
                        'ticketsNonTraites' => collect()
                    ]);
                }
            });
        }
    }
}
