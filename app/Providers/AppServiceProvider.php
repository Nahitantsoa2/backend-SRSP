<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        $this->verifierConnexionBaseDeDonnees();
    }

    private function verifierConnexionBaseDeDonnees(): void
    {
        $connexion = config('database.default');
        $base = config("database.connections.{$connexion}.database");

        try {
            DB::connection()->getPdo();

            $message = "[BD] Backend connecte a la base de donnees : OUI ({$connexion} / {$base})";
            Log::info($message);
        } catch (Throwable $e) {
            $message = "[BD] Backend connecte a la base de donnees : NON ({$connexion} / {$base}) - {$e->getMessage()}";
            Log::error($message);
        }

        $flux = @fopen('php://stderr', 'w');
        if ($flux !== false) {
            fwrite($flux, $message.PHP_EOL);
            fclose($flux);
        }
    }
}
