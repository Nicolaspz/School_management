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
        //alterar onome da tabela, tirando o s, e classrooms_id tirar o s do classroom e o subject, isso via migrate
        Schema::create('classroom_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('classrooms_id');
            $table->unsignedBigInteger('subjects_id');

            $table->foreign('classrooms_id')->references('id')->on('classrooms')->onUpdate('cascade')
            ->onDelete('cascade');
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
        Schema::dropIfExists('classroom_subjects');
    }
};
