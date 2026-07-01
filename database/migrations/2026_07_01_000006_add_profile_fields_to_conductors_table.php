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
        Schema::table('conductors', function (Blueprint $table) {
            if (! Schema::hasColumn('conductors', 'edad')) {
                $table->unsignedTinyInteger('edad')->nullable()->after('telefono');
            }
            if (! Schema::hasColumn('conductors', 'sexo')) {
                $table->string('sexo')->nullable()->after('edad');
            }
            if (! Schema::hasColumn('conductors', 'tipo_sangre')) {
                $table->string('tipo_sangre')->nullable()->after('sexo');
            }
            if (! Schema::hasColumn('conductors', 'tipo_licencia')) {
                $table->string('tipo_licencia')->nullable()->after('tipo_sangre');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conductors', function (Blueprint $table) {
            if (Schema::hasColumn('conductors', 'tipo_licencia')) {
                $table->dropColumn('tipo_licencia');
            }
            if (Schema::hasColumn('conductors', 'tipo_sangre')) {
                $table->dropColumn('tipo_sangre');
            }
            if (Schema::hasColumn('conductors', 'sexo')) {
                $table->dropColumn('sexo');
            }
            if (Schema::hasColumn('conductors', 'edad')) {
                $table->dropColumn('edad');
            }
        });
    }
};
