<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('secrets', function (Blueprint $table) {
            $table->string('sender')->nullable(); // Nome do remetente (Opcional)
        });
    }

    public function down()
    {
        Schema::table('secrets', function (Blueprint $table) {
            $table->dropColumn('sender');
        });
    }
};
