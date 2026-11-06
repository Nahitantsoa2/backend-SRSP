<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('conversation')) {
            Schema::create('conversation', function (Blueprint $table) {
                $table->id('id_conversation');
                $table->timestamp('date_debut')->useCurrent();
                $table->foreignId('id_utilisateur')->constrained('utilisateur', 'id_utilisateur')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation');
    }
};
