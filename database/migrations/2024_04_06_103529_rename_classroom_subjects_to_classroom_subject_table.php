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
    Schema::rename('classroom_subjects', 'classroom_subject');
}

public function down()
{
    Schema::rename('classroom_subject', 'classroom_subjects');
}
};
