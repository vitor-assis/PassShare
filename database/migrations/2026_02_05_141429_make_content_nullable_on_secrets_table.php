<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('secrets', function (Blueprint $table) {
            // AQUI: Dizemos que a coluna 'content' agora aceita valor NULO
            $table->text('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('secrets', function (Blueprint $table) {
            // Se desfazer, volta a ser obrigatório
            $table->text('content')->nullable(false)->change();
        });
    }
};
