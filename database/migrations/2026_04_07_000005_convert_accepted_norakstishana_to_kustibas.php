<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('Norakstishana') || ! Schema::hasTable('inventara_kustiba')) {
            return;
        }

        // Atrod kustības veidu "Norakstīšana"
        $norakstisanaVeidsId = DB::table('kustibas_veidi')
            ->whereRaw('LOWER(nosaukums) LIKE ?', ['%norakst%'])
            ->value('kustibas_veids_id');

        if (! $norakstisanaVeidsId) {
            return;
        }

        // Atrod visus akceptētus norakstīšanas, kuriem vēl nav izveidots kustības ieraksts
        $acceptedNorakstishanas = DB::table('Norakstishana')
            ->where('akceptets', true)
            ->get();

        foreach ($acceptedNorakstishanas as $norakstishana) {
            $documentRef = '[NORAKSTISHANA:' . $norakstishana->norakstishana_id . ']';

            $alreadyExists = DB::table('inventara_kustiba')
                ->where('piezimes', 'like', '%' . str_replace('[', '\[', str_replace(']', '\]', $documentRef)) . '%')
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            $inventars = DB::table('inventars')
                ->where('inventars_id', $norakstishana->inventara_id)
                ->first();

            if (! $inventars || empty($inventars->atbildigais_id)) {
                continue;
            }

            DB::table('inventara_kustiba')->insert([
                'datums' => $norakstishana->norDatums ?? Carbon::today()->toDateString(),
                'inventars_id' => $norakstishana->inventara_id,
                'atbildigais_lietotajs_id' => (int) $inventars->atbildigais_id,
                'Jatbildigais_lietotajs_id' => 0,
                'kustibas_veids_id' => (int) $norakstisanaVeidsId,
                'veca_telpa_id' => $inventars->telpas_id,
                'jauna_telpa_id' => null,
                'piezimes' => 'Automātiski izveidots no norakstīšanas pieteikuma. ' . $documentRef,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dzēš automātiski izveidotos ierakstus
        DB::table('inventara_kustiba')
            ->where('piezimes', 'like', '%Automātiski izveidots no norakstīšanas pieteikuma%')
            ->delete();
    }
};
