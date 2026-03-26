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
        if (! Schema::hasTable('Norakstishana') || ! Schema::hasColumn('Norakstishana', 'apstiprinashanas_dat')) {
            return;
        }

        DB::statement('ALTER TABLE `Norakstishana` MODIFY `apstiprinashanas_dat` DATE NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('Norakstishana') || ! Schema::hasColumn('Norakstishana', 'apstiprinashanas_dat')) {
            return;
        }

        DB::statement('ALTER TABLE `Norakstishana` MODIFY `apstiprinashanas_dat` DATE NOT NULL');
    }
};