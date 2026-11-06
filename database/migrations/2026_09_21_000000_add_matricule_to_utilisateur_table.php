<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            if (!Schema::hasColumn('utilisateur', 'matricule')) {
                $table->string('matricule', 30)->nullable()->after('id_utilisateur');
            }
        });
    }

    public function down(): void
    {
        Schema::table('utilisateur', function (Blueprint $table) {
            if (Schema::hasColumn('utilisateur', 'matricule')) {
                $table->dropColumn('matricule');
            }
        });
    }
};
