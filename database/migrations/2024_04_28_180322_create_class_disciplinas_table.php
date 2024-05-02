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
        Schema::create('class_disciplinas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grades_id');
            $table->unsignedBigInteger('subjects_id');

            $table->foreign('grades_id')->references('id')->on('grades')->onDelete('cascade');
            $table->foreign('subjects_id')->references('id')->on('subjects')->onUpdate('cascade')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_disciplinas');
    }
};
