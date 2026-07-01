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
            if (! Schema::hasColumn('autos', 'kilometraje')) {
                $table->string('kilometraje')->nullable()->after('anio');
            }
            if (! Schema::hasColumn('autos', 'color')) {
                $table->string('color')->nullable()->after('kilometraje');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            if (Schema::hasColumn('autos', 'color')) {
                $table->dropColumn('color');
            }
            if (Schema::hasColumn('autos', 'kilometraje')) {
                $table->dropColumn('kilometraje');
            }
        });
    }
};
