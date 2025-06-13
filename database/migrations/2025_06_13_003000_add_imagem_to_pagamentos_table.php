    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('pagamentos', function (Blueprint $table) {
                $table->string('imagem')->nullable()->after('data_pagamento');
            });
        }

        public function down()
        {
            Schema::table('pagamentos', function (Blueprint $table) {
                $table->dropColumn('imagem');
            });
        }
    };
    