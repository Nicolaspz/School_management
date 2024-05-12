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
        Schema::table('grade_subject', function (Blueprint $table) {
            // Remove a restrição de chave estrangeira
            $table->dropForeign('class_disciplinas_teachers_id_foreign');

            // Remove a coluna
            $table->dropColumn('teachers_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_subject', function (Blueprint $table) {
            //
        });
    }
};
