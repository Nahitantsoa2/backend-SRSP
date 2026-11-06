<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('service')) {
            Schema::create('service', function (Blueprint $table) {
                $table->id('id_service');
                $table->string('nom_service', 150);
                $table->text('description')->nullable();
            });

            // Insertion des services par défaut du projet
            $services = [
                ['id_service' => 1, 'nom_service' => 'Non affecté', 'description' => 'Service par défaut assigné à l\'inscription'],
                ['id_service' => 2, 'nom_service' => 'Direction des Ressources Humaines', 'description' => 'Gestion du personnel et des carrières'],
                ['id_service' => 3, 'nom_service' => 'Direction des Systèmes d\'Information', 'description' => 'Gestion informatique et numérique'],
            ];

            foreach ($services as $s) {
                DB::table('service')->insert($s);
            }

            // Réajustement de la séquence PostgreSQL pour les futurs INSERT
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("SELECT setval(pg_get_serial_sequence('service', 'id_service'), coalesce(max(id_service), 1)) FROM service;");
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
