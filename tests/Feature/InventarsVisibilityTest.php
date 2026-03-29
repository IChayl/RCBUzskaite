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

    $this->admin = Lietotajs::query()->create([
        'lietotajvards' => 'admins',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => true,
        'vards' => 'Admin',
        'uzvards' => 'Lietotajs',
    ]);

    $this->atbildigais = Lietotajs::query()->create([
        'lietotajvards' => 'darbinieks',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => false,
        'vards' => 'Parasts',
        'uzvards' => 'Lietotajs',
    ]);

    $this->citsDarbinieks = Lietotajs::query()->create([
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

    DB::table('inventars')->insertGetId([
        'nosaukums' => 'Redzams monitors',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->atbildigais->lietotajs_id,
        'inventara_numurs' => 'INV-001',
        'iegades_datums' => '2026-03-01',
    ]);

    $hiddenInventarId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Norakstīts dators',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->atbildigais->lietotajs_id,
        'inventara_numurs' => 'INV-002',
        'iegades_datums' => '2026-03-02',
    ]);

    $pendingInventarId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Gaidošs portatīvais',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->atbildigais->lietotajs_id,
        'inventara_numurs' => 'INV-003',
        'iegades_datums' => '2026-03-03',
    ]);

    $otherUserInventarId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Svešs projektors',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->citsDarbinieks->lietotajs_id,
        'inventara_numurs' => 'INV-004',
        'iegades_datums' => '2026-03-04',
    ]);

    $otherUserWrittenOffInventarId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Svešs norakstīts printeris',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->citsDarbinieks->lietotajs_id,
        'inventara_numurs' => 'INV-005',
        'iegades_datums' => '2026-03-05',
    ]);

    DB::table('Norakstishana')->insert([
        [
            'inventara_id' => $hiddenInventarId,
            'norDatums' => '2026-03-20',
            'pieteikshanas_dat' => '2026-03-20',
            'apstiprinashanas_dat' => '2026-03-21',
            'akceptets' => true,
            'pieteica_lietotajs_id' => $this->atbildigais->lietotajs_id,
            'iemesls' => 'Salauzts',
            'talaka_riciba' => 'Utilizēt',
        ],
        [
            'inventara_id' => $pendingInventarId,
            'norDatums' => '2026-03-22',
            'pieteikshanas_dat' => '2026-03-22',
            'apstiprinashanas_dat' => null,
            'akceptets' => false,
            'pieteica_lietotajs_id' => $this->atbildigais->lietotajs_id,
            'iemesls' => 'Nolietots',
            'talaka_riciba' => 'Pārskatīt',
        ],
        [
            'inventara_id' => $otherUserWrittenOffInventarId,
            'norDatums' => '2026-03-23',
            'pieteikshanas_dat' => '2026-03-23',
            'apstiprinashanas_dat' => '2026-03-24',
            'akceptets' => true,
            'pieteica_lietotajs_id' => $this->citsDarbinieks->lietotajs_id,
            'iemesls' => 'Saplīsis',
            'talaka_riciba' => 'Izvest',
        ],
    ]);
});

it('shows only non-written-off inventory by default for admin', function () {
    $response = $this->actingAs($this->admin)->get('/inventars');

    $response->assertOk();
    $response->assertSee('Redzams monitors');
    $response->assertSee('Gaidošs portatīvais');
    $response->assertDontSee('Norakstīts dators');
});

it('shows all inventory for admin when requested', function () {
    $response = $this->actingAs($this->admin)->get('/inventars?inventory_status=all');

    $response->assertOk();
    $response->assertSee('Redzams monitors');
    $response->assertSee('Gaidošs portatīvais');
    $response->assertSee('Norakstīts dators');
});

it('shows only written-off inventory for admin when requested', function () {
    $response = $this->actingAs($this->admin)->get('/inventars?inventory_status=written_off');

    $response->assertOk();
    $response->assertDontSee('Redzams monitors');
    $response->assertDontSee('Gaidošs portatīvais');
    $response->assertSee('Norakstīts dators');
    $response->assertSee('Svešs norakstīts printeris');
});

it('shows only responsible active inventory by default for employee', function () {
    $response = $this->actingAs($this->atbildigais)->get('/inventars');

    $response->assertOk();
    $response->assertSee('Redzams monitors');
    $response->assertSee('Gaidošs portatīvais');
    $response->assertDontSee('Norakstīts dators');
    $response->assertDontSee('Svešs projektors');
    $response->assertDontSee('Svešs norakstīts printeris');
});

it('shows all active inventory for employee when all scope is requested', function () {
    $response = $this->actingAs($this->atbildigais)->get('/inventars?inventory_scope=all');

    $response->assertOk();
    $response->assertSee('Redzams monitors');
    $response->assertSee('Gaidošs portatīvais');
    $response->assertSee('Svešs projektors');
    $response->assertDontSee('Norakstīts dators');
    $response->assertDontSee('Svešs norakstīts printeris');
});

it('shows all written-off inventory for employee when requested', function () {
    $response = $this->actingAs($this->atbildigais)->get('/inventars?inventory_status=written_off');

    $response->assertOk();
    $response->assertDontSee('Redzams monitors');
    $response->assertDontSee('Gaidošs portatīvais');
    $response->assertDontSee('Svešs projektors');
    $response->assertSee('Norakstīts dators');
    $response->assertSee('Svešs norakstīts printeris');
});