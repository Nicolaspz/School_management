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
        Schema::create('psicologos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('departments_id')->constrained('departments')->onDelete('cascade');
            $table->text('address')->nullable();
            $table->string('profile');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psicologos');
    }
};
