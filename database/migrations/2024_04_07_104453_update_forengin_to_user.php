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
        Schema::table('users', function (Blueprint $table) {
            // Aumentar a coluna roles_id
            $table->unsignedBigInteger('roles_id')->nullable()->after('password');

            // Adicionar a chave estrangeira
            $table->foreign('roles_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remover a chave estrangeira
            $table->dropForeign(['roles_id']);

            // Reverter o aumento da coluna
            $table->dropColumn('roles_id');
        });
    }
};
