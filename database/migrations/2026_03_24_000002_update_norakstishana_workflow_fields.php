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
        if (! Schema::hasTable('Norakstishana')) {
            return;
        }

        Schema::table('Norakstishana', function (Blueprint $table) {
            if (! Schema::hasColumn('Norakstishana', 'pieteikuma_datums')) {
                $table->date('pieteikuma_datums')->nullable()->after('norDatums');
            }

            if (! Schema::hasColumn('Norakstishana', 'apstiprinasanas_datums')) {
                $table->date('apstiprinasanas_datums')->nullable()->after('pieteikuma_datums');
            }

            if (! Schema::hasColumn('Norakstishana', 'akceptets')) {
                $table->boolean('akceptets')->default(false)->after('apstiprinasanas_datums');
            }

            if (! Schema::hasColumn('Norakstishana', 'pieteica_lietotajs_id')) {
                $table->unsignedInteger('pieteica_lietotajs_id')->nullable()->after('akceptets');
                $table->foreign('pieteica_lietotajs_id')
                    ->references('lietotajs_id')
                    ->on('lietotajs')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('Norakstishana')) {
            return;
        }

        Schema::table('Norakstishana', function (Blueprint $table) {
            if (Schema::hasColumn('Norakstishana', 'pieteica_lietotajs_id')) {
                $table->dropForeign(['pieteica_lietotajs_id']);
                $table->dropColumn('pieteica_lietotajs_id');
            }

            if (Schema::hasColumn('Norakstishana', 'akceptets')) {
                $table->dropColumn('akceptets');
            }

            if (Schema::hasColumn('Norakstishana', 'apstiprinasanas_datums')) {
                $table->dropColumn('apstiprinasanas_datums');
            }

            if (Schema::hasColumn('Norakstishana', 'pieteikuma_datums')) {
                $table->dropColumn('pieteikuma_datums');
            }
        });
    }
};
