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
        Schema::create('modalidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->dateTime('submissao_inicio');
            $table->dateTime('submissao_fim');
            $table->dateTime('avaliacao_inicio');
            $table->dateTime('avaliacao_fim');
            $table->dateTime('resultado');
            $table->foreignId('evento_id')->constrained();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidades');
    }
};
