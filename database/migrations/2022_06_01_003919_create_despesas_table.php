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
        // database/migrations/..._create_despesas_table.php
        // ..._create_despesas_table.php
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Garanta que esta linha existe!
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_vencimento');
            $table->string('status')->default('Pendente');
            $table->date('data_pagamento')->nullable();
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
        Schema::dropIfExists('events');
    }
};
