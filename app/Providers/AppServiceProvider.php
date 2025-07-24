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
    

    
public function boot()
{
    Carbon::setLocale('fr');

    Carbon::macro('diffCourtPourHumains', function () {
        $diff = $this->diffForHumans(now(), [
            'parts' => 1,
            'join' => true,
            'syntax' => Carbon::DIFF_RELATIVE_TO_NOW, // donne "il y a ..."
            'short' => true,
        ]);

        // Remplacer les abréviations anglaises par des versions françaises
        return str_replace(
            ['sec', 'min', 'h', 'd', 'w', 'mo', 'yr'],
            ['s', 'min', 'h', 'j', 'sem', 'mois', 'an'],
            $diff
        );
    });
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $userId = Auth::id();

            $countTicketsNonTraites = Ticket::where('technicien_id', $userId)
                ->whereIn('status', ['nouveau', 'en_cours'])
                ->count();

            // Liste complète pour afficher dans dropdown
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

