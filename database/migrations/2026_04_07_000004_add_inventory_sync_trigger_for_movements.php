<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        if (! Schema::hasTable('inventara_kustiba') || ! Schema::hasTable('inventars')) {
            return;
        }

        if (! Schema::hasColumn('inventara_kustiba', 'jauna_telpa_id') || ! Schema::hasColumn('inventara_kustiba', 'Jatbildigais_lietotajs_id')) {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_inventara_kustiba_after_insert_sync_inventars');

        DB::unprepared('
            CREATE TRIGGER trg_inventara_kustiba_after_insert_sync_inventars
            AFTER INSERT ON inventara_kustiba
            FOR EACH ROW
            BEGIN
                UPDATE inventars
                SET
                    telpas_id = CASE
                        WHEN NEW.jauna_telpa_id IS NOT NULL THEN NEW.jauna_telpa_id
                        ELSE telpas_id
                    END,
                    atbildigais_id = CASE
                        WHEN NEW.Jatbildigais_lietotajs_id IS NOT NULL AND NEW.Jatbildigais_lietotajs_id > 0 THEN NEW.Jatbildigais_lietotajs_id
                        ELSE atbildigais_id
                    END
                WHERE inventars_id = NEW.inventars_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_inventara_kustiba_after_insert_sync_inventars');
    }
};
