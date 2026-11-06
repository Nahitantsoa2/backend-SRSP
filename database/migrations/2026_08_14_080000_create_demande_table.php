<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('demande')) {
            Schema::create('demande', function (Blueprint $table) {
                $table->id('id_demande');
                $table->timestamp('date_soumission')->useCurrent();
                $table->enum('statut', ['soumise', 'en_cours', 'validee', 'rejetee', 'complement_demande'])->default('soumise');
                $table->timestamp('date_traitement')->nullable();
                $table->foreignId('id_fonctionnaire')->constrained('utilisateur', 'id_utilisateur')->onDelete('cascade');
                $table->foreignId('id_type_demarche')->constrained('type_demarche', 'id_type_demarche')->onDelete('cascade');
                $table->foreignId('id_chef_traitant')->nullable()->constrained('utilisateur', 'id_utilisateur')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('demande');
    }
};
