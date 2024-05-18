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
        Schema::table('faltas', function (Blueprint $table) {
            // Verifique se a coluna 'teacher_id' não existe antes de tentar adicioná-la

                $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
                $table->boolean('justify')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notas', function (Blueprint $table) {
            //
        });
    }
};
