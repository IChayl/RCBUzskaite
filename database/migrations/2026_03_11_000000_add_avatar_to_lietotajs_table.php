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
        Schema::table('lietotajs', function (Blueprint $table) {
            if (!Schema::hasColumn('lietotajs', 'avatar')) {
                $table->string('avatar')->nullable()->after('parole');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lietotajs', function (Blueprint $table) {
            if (Schema::hasColumn('lietotajs', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });
    }
};
