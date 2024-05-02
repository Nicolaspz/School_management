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
        Schema::table('classroom_subject', function (Blueprint $table) {
            /*
           // Remover a coluna classrooms_id
           $table->dropForeign('classroom_subjects_subjects_id_foreign'); // Isso remove a restrição de chave estrangeira
           $table->dropColumn('classroom_id'); // Isso remove a coluna

           // Adicionar uma nova coluna para relacionar com grades
          $table->unsignedBigInteger('grades_id');
           $table->foreign('grades_id')->references('id')->on('grades')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classroom_subject', function (Blueprint $table) {
            //
        });
    }
};
