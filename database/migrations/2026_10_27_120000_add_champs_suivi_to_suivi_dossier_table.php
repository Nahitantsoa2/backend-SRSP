<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suivi_dossier', function (Blueprint $table) {
            if (!Schema::hasColumn('suivi_dossier', 'localite')) {
                $table->string('localite')->nullable()->after('id');
            }
            if (!Schema::hasColumn('suivi_dossier', 'chap')) {
                $table->string('chap')->nullable()->after('localite');
            }
            if (!Schema::hasColumn('suivi_dossier', 'cmv')) {
                $table->string('cmv')->nullable()->after('im');
            }
        });
    }

    public function down(): void
    {
        Schema::table('suivi_dossier', function (Blueprint $table) {
            $table->dropColumn(['localite', 'chap', 'cmv']);
        });
    }
};
