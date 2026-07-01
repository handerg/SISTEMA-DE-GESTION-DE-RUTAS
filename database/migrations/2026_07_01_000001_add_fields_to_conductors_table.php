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
            if (! Schema::hasColumn('conductors', 'nombre')) {
                $table->string('nombre')->nullable()->after('id');
            }
            if (! Schema::hasColumn('conductors', 'email')) {
                $table->string('email')->nullable()->unique()->after('nombre');
            }
            if (! Schema::hasColumn('conductors', 'telefono')) {
                $table->string('telefono')->nullable()->after('email');
            }
            if (! Schema::hasColumn('conductors', 'auto_id')) {
                $table->unsignedBigInteger('auto_id')->nullable()->after('telefono');
                $table->foreign('auto_id')->references('id')->on('autos')->nullOnDelete();
            }
            if (! Schema::hasColumn('conductors', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('auto_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conductors', function (Blueprint $table) {
            if (Schema::hasColumn('conductors', 'auto_id')) {
                $table->dropForeign(['auto_id']);
                $table->dropColumn('auto_id');
            }
            if (Schema::hasColumn('conductors', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('conductors', 'telefono')) {
                $table->dropColumn('telefono');
            }
            if (Schema::hasColumn('conductors', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('conductors', 'nombre')) {
                $table->dropColumn('nombre');
            }
        });
    }
};
