<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('message');
            $table->enum('type', ['info', 'warning', 'danger', 'success'])->default('info');
            $table->string('auteur')->nullable();         // Nom du prof/admin
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->nullOnDelete();
            $table->foreignId('niveau_id')->nullable()->constrained('niveaux')->nullOnDelete();
            $table->boolean('urgent')->default(false);    // Notification urgente
            $table->timestamp('expires_at')->nullable(); // Expiration automatique
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
