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
        Schema::table('autos', function (Blueprint $table) {
            if (! Schema::hasColumn('autos', 'placa')) {
                $table->string('placa')->nullable()->after('id');
            }
            if (! Schema::hasColumn('autos', 'marca')) {
                $table->string('marca')->nullable()->after('placa');
            }
            if (! Schema::hasColumn('autos', 'modelo')) {
                $table->string('modelo')->nullable()->after('marca');
            }
            if (! Schema::hasColumn('autos', 'anio')) {
                $table->string('anio')->nullable()->after('modelo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            if (Schema::hasColumn('autos', 'anio')) {
                $table->dropColumn('anio');
            }
            if (Schema::hasColumn('autos', 'modelo')) {
                $table->dropColumn('modelo');
            }
            if (Schema::hasColumn('autos', 'marca')) {
                $table->dropColumn('marca');
            }
            if (Schema::hasColumn('autos', 'placa')) {
                $table->dropColumn('placa');
            }
        });
    }
};
