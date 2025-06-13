    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('despesas', function (Blueprint $table) {
                $table->string('categoria')->after('valor')->nullable();
                $table->string('imagem')->after('categoria')->nullable(); // Caminho para o comprovante
            });
        }

        public function down()
        {
            Schema::table('despesas', function (Blueprint $table) {
                $table->dropColumn('categoria');
                $table->dropColumn('imagem');
            });
        }
    };
    