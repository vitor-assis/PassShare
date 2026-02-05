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
        Schema::table('secrets', function (Blueprint $table) {
            $table->integer('max_views')->default(1); // Total de visualizações permitidas
            $table->integer('current_views')->default(0); // Quantas vezes já foi visto
            $table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secrets', function (Blueprint $table) {
            //
        });
    }
};
