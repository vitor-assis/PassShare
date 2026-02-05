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
            $table->string('type')->default('text'); // Vai ser 'text' ou 'file'
            $table->string('file_path')->nullable(); // Onde o arquivo está salvo
            $table->string('file_name')->nullable(); // O nome original (ex: contrato.pdf)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secret', function (Blueprint $table) {
            //
        });
    }
};
