<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('message')) {
            Schema::create('message', function (Blueprint $table) {
                $table->id('id_message');
                $table->text('contenu');
                $table->enum('expediteur', ['utilisateur', 'agent_ia'])->default('utilisateur');
                $table->timestamp('date_envoi')->useCurrent();
                $table->foreignId('id_conversation')->constrained('conversation', 'id_conversation')->onDelete('cascade');
                $table->foreignId('id_agent')->nullable()->constrained('agent_ia', 'id_agent')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('message');
    }
};
