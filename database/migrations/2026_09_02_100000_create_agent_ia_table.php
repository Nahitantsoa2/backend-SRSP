<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('agent_ia')) {
            Schema::create('agent_ia', function (Blueprint $table) {
                $table->id('id_agent');
                $table->string('nom_agent', 100);
                $table->enum('type_agent', ['assistance', 'orientation', 'suivi'])->default('assistance');
                $table->text('description')->nullable();
            });

            $agents = [
                ['id_agent' => 1, 'nom_agent' => 'Agent Assistance', 'type_agent' => 'assistance', 'description' => 'Répond aux questions des fonctionnaires en langage naturel'],
                ['id_agent' => 2, 'nom_agent' => 'Agent Orientation', 'type_agent' => 'orientation', 'description' => 'Oriente le fonctionnaire vers le bon service ou document'],
                ['id_agent' => 3, 'nom_agent' => 'Agent Suivi', 'type_agent' => 'suivi', 'description' => 'Assure le suivi de l\'état d\'avancement des demandes'],
            ];

            foreach ($agents as $a) {
                DB::table('agent_ia')->insert($a);
            }

            if (DB::getDriverName() === 'pgsql') {
                DB::statement("SELECT setval(pg_get_serial_sequence('agent_ia', 'id_agent'), coalesce(max(id_agent), 1)) FROM agent_ia;");
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_ia');
    }
};
