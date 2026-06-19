<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Filière principale (obligatoire pour les étudiants, première filière pour les profs)
            $table->unsignedBigInteger('filiere_id')->nullable()->after('enseignant_id');
            // Niveau (obligatoire pour les étudiants)
            $table->unsignedBigInteger('niveau_id')->nullable()->after('filiere_id');
            // Filières multiples pour les professeurs (JSON)
            $table->json('filiere_ids')->nullable()->after('niveau_id');

            $table->foreign('filiere_id')->references('id')->on('filieres')->nullOnDelete();
            $table->foreign('niveau_id')->references('id')->on('niveaux')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['filiere_id']);
            $table->dropForeign(['niveau_id']);
            $table->dropColumn(['filiere_id', 'niveau_id', 'filiere_ids']);
        });
    }
};
