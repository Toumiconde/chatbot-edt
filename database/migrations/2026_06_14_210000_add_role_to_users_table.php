<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['chef', 'prof', 'etudiant'])->default('etudiant')->after('email');
            $table->foreignId('enseignant_id')->nullable()->after('role')
                  ->constrained('enseignants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['enseignant_id']);
            $table->dropColumn(['role', 'enseignant_id']);
        });
    }
};
