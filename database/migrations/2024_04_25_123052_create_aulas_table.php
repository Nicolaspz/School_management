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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->string('sumario');
            $table->string('title')->nullable();
            $table->foreignId('classrooms_id')->constrained('classrooms')->onDelete('cascade');
            //$table->foreignId('students_id')->constrained('students')->onDelete('cascade');
            $table->date('data');
            //$table->boolean('falta')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
