    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('despesas', function (Blueprint $table) {
                $table->dropColumn('imagem');
            });
        }

        public function down()
        {
            Schema::table('despesas', function (Blueprint $table) {
                $table->string('imagem')->nullable();
            });
        }
    };
    