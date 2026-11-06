<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reclamation_message_masque')) {
            Schema::create('reclamation_message_masque', function (Blueprint $table) {
                $table->id('id_masque');
                $table->foreignId('id_message')->constrained('reclamation_message', 'id_message')->onDelete('cascade');
                $table->foreignId('id_utilisateur')->constrained('utilisateur', 'id_utilisateur')->onDelete('cascade');

                $table->unique(['id_message', 'id_utilisateur']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamation_message_masque');
    }
};
