<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extratos', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('credencial');
            $table->uuid('cobranca_id');
            $table->string('movimentacao');
            $table->unsignedTinyInteger('tipo');
            $table->unsignedDouble('valor_cobranca');
            $table->unsignedDouble('valor_pago');
            $table->unsignedDouble('valor_extrato');
            $table->date('data_pagamento');
            $table->date('data_creditobanco');
            $table->date('data_creditocliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extratos');
    }
};
