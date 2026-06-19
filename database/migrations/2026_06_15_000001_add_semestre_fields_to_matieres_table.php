<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            if (!Schema::hasColumn('matieres', 'filiere_id')) {
                $table->foreignId('filiere_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('matieres', 'niveau_id')) {
                $table->foreignId('niveau_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('matieres', 'semestre')) {
                $table->string('semestre')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matieres', function (Blueprint $table) {
            $table->dropForeign(['filiere_id']);
            $table->dropForeign(['niveau_id']);
            $table->dropColumn(['filiere_id', 'niveau_id', 'semestre']);
        });
    }
};
