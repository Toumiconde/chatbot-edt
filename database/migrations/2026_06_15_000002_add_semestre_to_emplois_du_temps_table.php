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
        // Add semestre to emplois_du_temps if missing
        Schema::table('emplois_du_temps', function (Blueprint $table) {
            if (!Schema::hasColumn('emplois_du_temps', 'semestre')) {
                $table->string('semestre')->nullable()->after('niveau_id');
            }
        });

        // Add Samedi to the jour enum if needed (also fixes enum issue)
        // Note: MySQL requires rebuilding the enum column to add values
        // We use a raw statement to be safe
        try {
            \Illuminate\Support\Facades\DB::statement(
                "ALTER TABLE `emplois_du_temps` MODIFY `jour` ENUM('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi') NULL"
            );
        } catch (\Exception $e) {
            // Already correct or column doesn't support modification
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emplois_du_temps', function (Blueprint $table) {
            if (Schema::hasColumn('emplois_du_temps', 'semestre')) {
                $table->dropColumn('semestre');
            }
        });
    }
};
