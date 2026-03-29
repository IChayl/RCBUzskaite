<?php

use App\Models\Lietotajs;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::dropIfExists('Norakstishana');
    Schema::dropIfExists('inventars');
    Schema::dropIfExists('lietotajs');
    Schema::dropIfExists('telpa');
    Schema::dropIfExists('kategorija');

    Schema::create('kategorija', function (Blueprint $table) {
        $table->increments('kategorija_id');
        $table->string('nosaukums', 50);
        $table->string('apraksts', 150)->nullable();
    });

    Schema::create('telpa', function (Blueprint $table) {
        $table->increments('telpas_id');
        $table->string('nosaukums', 50);
        $table->string('izmeri', 10)->nullable();
        $table->integer('numurs')->nullable();
        $table->integer('stavs');
    });

    Schema::create('lietotajs', function (Blueprint $table) {
        $table->increments('lietotajs_id');
        $table->string('lietotajvards', 20)->unique();
        $table->string('parole', 255);
        $table->boolean('admina_tiesibas')->default(false);
        $table->string('avatar', 255)->nullable();
        $table->string('vards', 50)->nullable();
        $table->string('uzvards', 50)->nullable();
        $table->string('epasts', 100)->nullable();
        $table->string('telefons', 20)->nullable();
        $table->string('amats', 50)->nullable();
        $table->boolean('aktivs')->default(true);
    });

    Schema::create('inventars', function (Blueprint $table) {
        $table->increments('inventars_id');
        $table->string('nosaukums', 30);
        $table->unsignedInteger('kategorija_id');
        $table->unsignedInteger('telpas_id');
        $table->unsignedInteger('atbildigais_id')->nullable();
        $table->string('inventara_numurs', 50)->nullable();
        $table->date('iegades_datums')->nullable();

        $table->foreign('kategorija_id')->references('kategorija_id')->on('kategorija');
        $table->foreign('telpas_id')->references('telpas_id')->on('telpa');
        $table->foreign('atbildigais_id')->references('lietotajs_id')->on('lietotajs');
    });

    Schema::create('Norakstishana', function (Blueprint $table) {
        $table->increments('norakstishana_id');
        $table->unsignedInteger('inventara_id');
        $table->date('norDatums');
        $table->date('pieteikshanas_dat')->nullable();
        $table->date('apstiprinashanas_dat')->nullable();
        $table->boolean('akceptets')->default(false);
        $table->unsignedInteger('pieteica_lietotajs_id')->nullable();
        $table->string('iemesls', 30);
        $table->string('talaka_riciba', 50);

        $table->foreign('inventara_id')->references('inventars_id')->on('inventars');
        $table->foreign('pieteica_lietotajs_id')->references('lietotajs_id')->on('lietotajs');
    });

    $this->darbinieks = Lietotajs::query()->create([
        'lietotajvards' => 'darbinieks',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => false,
        'vards' => 'Parasts',
        'uzvards' => 'Lietotajs',
    ]);

    $citsDarbinieks = Lietotajs::query()->create([
        'lietotajvards' => 'citsdarbinieks',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => false,
        'vards' => 'Cits',
        'uzvards' => 'Darbinieks',
    ]);

    $kategorijaId = DB::table('kategorija')->insertGetId([
        'nosaukums' => 'Datori',
        'apraksts' => 'Testa kategorija',
    ]);

    $telpaId = DB::table('telpa')->insertGetId([
        'nosaukums' => '101. telpa',
        'izmeri' => '5x5',
        'numurs' => 101,
        'stavs' => 1,
    ]);

    $manaInventaraId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Mans dators',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->darbinieks->lietotajs_id,
        'inventara_numurs' => 'INV-201',
        'iegades_datums' => '2026-03-01',
    ]);

    $citaInventaraId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Cita dators',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $citsDarbinieks->lietotajs_id,
        'inventara_numurs' => 'INV-202',
        'iegades_datums' => '2026-03-02',
    ]);

    DB::table('Norakstishana')->insert([
        [
            'inventara_id' => $manaInventaraId,
            'norDatums' => '2026-03-20',
            'pieteikshanas_dat' => '2026-03-20',
            'apstiprinashanas_dat' => null,
            'akceptets' => false,
            'pieteica_lietotajs_id' => $this->darbinieks->lietotajs_id,
            'iemesls' => 'Nolietots',
            'talaka_riciba' => 'Pārskatīt',
        ],
        [
            'inventara_id' => $citaInventaraId,
            'norDatums' => '2026-03-21',
            'pieteikshanas_dat' => '2026-03-21',
            'apstiprinashanas_dat' => '2026-03-22',
            'akceptets' => true,
            'pieteica_lietotajs_id' => $citsDarbinieks->lietotajs_id,
            'iemesls' => 'Salauzts',
            'talaka_riciba' => 'Utilizēt',
        ],
    ]);
});

it('shows all write-off rows for employee in the table', function () {
    $response = $this->actingAs($this->darbinieks)->get('/norakstishana');

    $response->assertOk();
    $response->assertSee('Mans dators');
    $response->assertSee('Cita dators');
    $response->assertSee('Nolietots');
    $response->assertSee('Salauzts');
});