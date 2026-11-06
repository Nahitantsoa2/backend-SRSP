<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reclamation')) {
            Schema::create('reclamation', function (Blueprint $table) {
                $table->id('id_reclamation');
                $table->string('objet', 200);
                $table->text('description');
                $table->timestamp('date_creation')->useCurrent();
                $table->enum('statut', ['ouverte', 'en_cours', 'repondue', 'cloturee'])->default('ouverte');
                $table->timestamp('date_reponse')->nullable();
                $table->text('reponse')->nullable();
                $table->foreignId('id_fonctionnaire')->constrained('utilisateur', 'id_utilisateur')->onDelete('cascade');
                $table->foreignId('id_demande')->nullable()->constrained('demande', 'id_demande')->nullOnDelete();
                $table->foreignId('id_chef_traitant')->nullable()->constrained('utilisateur', 'id_utilisateur')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamation');
    }
};
