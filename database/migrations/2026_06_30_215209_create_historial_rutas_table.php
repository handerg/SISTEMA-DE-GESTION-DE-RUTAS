<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historial_rutas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conductor_id')->nullable();
            $table->string('titulo')->nullable();
            $table->text('detalle')->nullable();
            $table->timestamps();

            $table->foreign('conductor_id')->references('id')->on('conductors')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_rutas');
    }
};
