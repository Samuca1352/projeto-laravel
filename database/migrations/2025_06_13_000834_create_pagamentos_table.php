    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::create('pagamentos', function (Blueprint $table) {
                $table->id();
                // Conecta o pagamento à despesa correspondente
                $table->foreignId('despesa_id')->constrained()->onDelete('cascade');
                $table->date('data_pagamento');
                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('pagamentos');
        }
    };
    