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
        Schema::create('modifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emploi_id')->constrained('emplois_du_temps')->onDelete('cascade');
            $table->string('ancien_jour')->nullable();
            $table->time('ancienne_heure')->nullable();
            $table->string('nouveau_jour')->nullable();
            $table->time('nouvelle_heure')->nullable();
            $table->string('motif')->nullable();
            $table->timestamp('date_modif')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifications');
    }
};
