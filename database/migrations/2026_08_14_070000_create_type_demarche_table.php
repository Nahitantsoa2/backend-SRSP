<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('type_demarche')) {
            Schema::create('type_demarche', function (Blueprint $table) {
                $table->id('id_type_demarche');
                $table->string('libelle', 150);
                $table->text('description')->nullable();
                $table->foreignId('id_service')->constrained('service', 'id_service')->onDelete('cascade');
            });

            // Types de démarches officiels existants
            $types = [
                ['id_type_demarche' => 1, 'libelle' => 'Demande de pension', 'description' => 'Demande de pension', 'id_service' => 2],
                ['id_type_demarche' => 2, 'libelle' => 'Demande d\'allocation familiale', 'description' => 'Demande d\'allocation familiale', 'id_service' => 2],
                ['id_type_demarche' => 3, 'libelle' => 'Demande d\'avance sur salaire', 'description' => 'Demande d\'avance sur salaire', 'id_service' => 2],
                ['id_type_demarche' => 4, 'libelle' => 'Demande de prise en charge médicale', 'description' => 'Demande de prise en charge médicale', 'id_service' => 2],
                ['id_type_demarche' => 5, 'libelle' => 'Demande de complément d\'installation', 'description' => 'Demande d\'autorisation de percevoir le complément d\'installation', 'id_service' => 2],
                ['id_type_demarche' => 6, 'libelle' => 'Demande de Duplicata', 'description' => 'Demande de Duplicata (Avis d\'Emission / Ordre de Recette / Etat de décompte)', 'id_service' => 2],
                ['id_type_demarche' => 7, 'libelle' => 'Demande de Validation des services', 'description' => 'Demande de Validation des services précaires', 'id_service' => 2],
                ['id_type_demarche' => 8, 'libelle' => 'Demande de Bon de Caisse', 'description' => 'Demande de Bon de Caisse', 'id_service' => 2],
            ];

            foreach ($types as $t) {
                DB::table('type_demarche')->insert($t);
            }

            if (DB::getDriverName() === 'pgsql') {
                DB::statement("SELECT setval(pg_get_serial_sequence('type_demarche', 'id_type_demarche'), coalesce(max(id_type_demarche), 1)) FROM type_demarche;");
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('type_demarche');
    }
};
