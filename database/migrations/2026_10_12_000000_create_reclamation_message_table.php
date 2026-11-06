<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reclamation_message')) {
            Schema::create('reclamation_message', function (Blueprint $table) {
                $table->id('id_message');
                $table->foreignId('id_reclamation')->constrained('reclamation', 'id_reclamation')->onDelete('cascade');
                $table->foreignId('id_utilisateur')->constrained('utilisateur', 'id_utilisateur')->onDelete('cascade');
                $table->text('contenu');
                $table->timestamp('date_envoi')->default(DB::raw('CURRENT_TIMESTAMP'));
            });
        }

        // Reprise de l'historique existant : description (fonctionnaire) + réponse (administration)
        $admin = DB::selectOne("select id_utilisateur from utilisateur where role = 'administrateur' order by id_utilisateur limit 1");
        $adminId = $admin?->id_utilisateur;

        foreach (DB::table('reclamation')->orderBy('id_reclamation')->get() as $r) {
            if ($r->description !== null && trim($r->description) !== '') {
                DB::table('reclamation_message')->insert([
                    'id_reclamation' => $r->id_reclamation,
                    'id_utilisateur' => $r->id_fonctionnaire,
                    'contenu' => $r->description,
                    'date_envoi' => $r->date_creation ?? now(),
                ]);
            }

            if ($r->reponse !== null && trim($r->reponse) !== '') {
                $auteur = $r->id_chef_traitant ?: $adminId;
                if ($auteur) {
                    DB::table('reclamation_message')->insert([
                        'id_reclamation' => $r->id_reclamation,
                        'id_utilisateur' => $auteur,
                        'contenu' => $r->reponse,
                        'date_envoi' => $r->date_reponse ?? now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamation_message');
    }
};
