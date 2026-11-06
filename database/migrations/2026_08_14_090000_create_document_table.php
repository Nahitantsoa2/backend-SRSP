<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('document')) {
            Schema::create('document', function (Blueprint $table) {
                $table->id('id_document');
                $table->string('nom_fichier', 255);
                $table->string('chemin_fichier', 500);
                $table->timestamp('date_upload')->useCurrent();
                $table->foreignId('id_demande')->constrained('demande', 'id_demande')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};
