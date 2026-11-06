<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('historique_statut')) {
            Schema::create('historique_statut', function (Blueprint $table) {
                $table->id('id_historique');
                $table->string('ancien_statut', 50)->nullable();
                $table->string('nouveau_statut', 50);
                $table->timestamp('date_changement')->useCurrent();
                $table->foreignId('id_demande')->constrained('demande', 'id_demande')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_statut');
    }
};
