<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('classroom_subject', function (Blueprint $table) {
        $table->renameColumn('classrooms_id', 'classroom_id');
        $table->renameColumn('subjects_id', 'subject_id');
        $table->string('description')->nullable();
    });
}

public function down()
{
    Schema::table('classroom_subject', function (Blueprint $table) {
        $table->renameColumn('classroom_id', 'classrooms_id');
        $table->renameColumn('subject_id', 'subjects_id');
        $table->dropColumn('description');
    });
}
};
