<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToClassroomSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom_subject', function (Blueprint $table) {
            // Adiciona uma restrição única composta nas colunas 'classrooms_id' e 'subjects_id'
            $table->unique(['classroom_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classroom_subject', function (Blueprint $table) {
            // Remove a restrição única composta
            $table->dropUnique(['classroom_id', 'subject_id']);
        });
    }
}
