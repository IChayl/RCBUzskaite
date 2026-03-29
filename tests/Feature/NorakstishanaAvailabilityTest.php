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

    $this->darbinieks = Lietotajs::query()->create([
        'lietotajvards' => 'darbinieks',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => false,
        'vards' => 'Parasts',
        'uzvards' => 'Lietotajs',
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

    $this->activeInventarId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Aktīvs dators',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->darbinieks->lietotajs_id,
        'inventara_numurs' => 'INV-100',
        'iegades_datums' => '2026-03-01',
    ]);

    $this->writtenOffInventarId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Jau norakstīts dators',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $telpaId,
        'atbildigais_id' => $this->darbinieks->lietotajs_id,
        'inventara_numurs' => 'INV-101',
        'iegades_datums' => '2026-03-02',
    ]);

    DB::table('Norakstishana')->insert([
        'inventara_id' => $this->writtenOffInventarId,
        'norDatums' => '2026-03-20',
        'pieteikshanas_dat' => '2026-03-20',
        'apstiprinashanas_dat' => '2026-03-21',
        'akceptets' => true,
        'pieteica_lietotajs_id' => $this->darbinieks->lietotajs_id,
        'iemesls' => 'Salauzts',
        'talaka_riciba' => 'Utilizēt',
    ]);
});

it('excludes already written-off inventory from the create form', function () {
    $response = $this->actingAs($this->admin)->get('/norakstishana/create');

    $response->assertOk();
    $response->assertSee('Aktīvs dators');
    $response->assertDontSee('Jau norakstīts dators');
});

it('rejects creating a write-off for already written-off inventory', function () {
    $response = $this->actingAs($this->admin)->post('/norakstishana', [
        'inventara_id' => $this->writtenOffInventarId,
        'norDatums' => '2026-03-25',
        'iemesls' => 'Atkārtots mēģinājums',
        'talaka_riciba' => 'Nedrīkst saglabāt',
    ]);

    $response->assertSessionHasErrors(['inventara_id']);

    expect(DB::table('Norakstishana')->where('inventara_id', $this->writtenOffInventarId)->count())->toBe(1);
});

it('rejects accepting a pending write-off if the inventory is already written off', function () {
    $pendingId = DB::table('Norakstishana')->insertGetId([
        'inventara_id' => $this->writtenOffInventarId,
        'norDatums' => '2026-03-26',
        'pieteikshanas_dat' => '2026-03-26',
        'apstiprinashanas_dat' => null,
        'akceptets' => false,
        'pieteica_lietotajs_id' => $this->darbinieks->lietotajs_id,
        'iemesls' => 'Dublikāts',
        'talaka_riciba' => 'Mēģināt akceptēt',
    ]);

    $response = $this->actingAs($this->admin)->post("/norakstishana/{$pendingId}/accept");

    $response->assertSessionHasErrors(['inventara_id']);

    expect(DB::table('Norakstishana')->where('norakstishana_id', $pendingId)->value('akceptets'))->toBe(0);
});