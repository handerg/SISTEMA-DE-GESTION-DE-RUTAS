<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            if (! Schema::hasColumn('autos', 'foto_perfil')) {
                $table->string('foto_perfil')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('autos', function (Blueprint $table) {
            if (Schema::hasColumn('autos', 'foto_perfil')) {
                $table->dropColumn('foto_perfil');
            }
        });
    }
};
