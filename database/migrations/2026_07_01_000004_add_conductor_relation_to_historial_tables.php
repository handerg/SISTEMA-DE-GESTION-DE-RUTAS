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
        Schema::table('historial_rutas', function (Blueprint $table) {
            if (! Schema::hasColumn('historial_rutas', 'conductor_id')) {
                $table->unsignedBigInteger('conductor_id')->nullable()->after('id');
                $table->foreign('conductor_id')->references('id')->on('conductors')->nullOnDelete();
            }
            if (! Schema::hasColumn('historial_rutas', 'titulo')) {
                $table->string('titulo')->nullable()->after('conductor_id');
            }
            if (! Schema::hasColumn('historial_rutas', 'detalle')) {
                $table->text('detalle')->nullable()->after('titulo');
            }
        });

        Schema::table('historial_servicios', function (Blueprint $table) {
            if (! Schema::hasColumn('historial_servicios', 'conductor_id')) {
                $table->unsignedBigInteger('conductor_id')->nullable()->after('id');
                $table->foreign('conductor_id')->references('id')->on('conductors')->nullOnDelete();
            }
            if (! Schema::hasColumn('historial_servicios', 'titulo')) {
                $table->string('titulo')->nullable()->after('conductor_id');
            }
            if (! Schema::hasColumn('historial_servicios', 'detalle')) {
                $table->text('detalle')->nullable()->after('titulo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historial_rutas', function (Blueprint $table) {
            if (Schema::hasColumn('historial_rutas', 'conductor_id')) {
                $table->dropForeign(['conductor_id']);
                $table->dropColumn('conductor_id');
            }
            if (Schema::hasColumn('historial_rutas', 'titulo')) {
                $table->dropColumn('titulo');
            }
            if (Schema::hasColumn('historial_rutas', 'detalle')) {
                $table->dropColumn('detalle');
            }
        });

        Schema::table('historial_servicios', function (Blueprint $table) {
            if (Schema::hasColumn('historial_servicios', 'conductor_id')) {
                $table->dropForeign(['conductor_id']);
                $table->dropColumn('conductor_id');
            }
            if (Schema::hasColumn('historial_servicios', 'titulo')) {
                $table->dropColumn('titulo');
            }
            if (Schema::hasColumn('historial_servicios', 'detalle')) {
                $table->dropColumn('detalle');
            }
        });
    }
};
