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
        Schema::table('inventara_kustiba', function (Blueprint $table) {
            $table->boolean('apstiprinats')->default(false)->after('piezimes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventara_kustiba', function (Blueprint $table) {
            $table->dropColumn('apstiprinats');
        });
    }
};
