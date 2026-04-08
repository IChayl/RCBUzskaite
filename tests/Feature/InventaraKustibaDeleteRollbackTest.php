<?php

use App\Models\Lietotajs;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::dropIfExists('inventara_kustiba');
    Schema::dropIfExists('inventars');
    Schema::dropIfExists('kustibas_veidi');
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

    Schema::create('kustibas_veidi', function (Blueprint $table) {
        $table->increments('kustibas_veids_id');
        $table->string('nosaukums', 50);
        $table->string('apraksts', 200)->nullable();
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

    Schema::create('inventara_kustiba', function (Blueprint $table) {
        $table->increments('kustiba_id');
        $table->date('datums');
        $table->unsignedInteger('inventars_id');
        $table->unsignedInteger('atbildigais_lietotajs_id');
        $table->unsignedInteger('Jatbildigais_lietotajs_id')->nullable();
        $table->unsignedInteger('kustibas_veids_id')->nullable();
        $table->unsignedInteger('veca_telpa_id')->nullable();
        $table->unsignedInteger('jauna_telpa_id')->nullable();
        $table->string('piezimes', 255)->nullable();
        $table->string('dokuments', 255)->nullable();

        $table->foreign('inventars_id')->references('inventars_id')->on('inventars')->onDelete('cascade');
        $table->foreign('atbildigais_lietotajs_id')->references('lietotajs_id')->on('lietotajs')->onDelete('cascade');
        $table->foreign('Jatbildigais_lietotajs_id')->references('lietotajs_id')->on('lietotajs')->nullOnDelete();
        $table->foreign('kustibas_veids_id')->references('kustibas_veids_id')->on('kustibas_veidi')->nullOnDelete();
        $table->foreign('veca_telpa_id')->references('telpas_id')->on('telpa')->nullOnDelete();
        $table->foreign('jauna_telpa_id')->references('telpas_id')->on('telpa')->nullOnDelete();
    });

    $this->admin = Lietotajs::query()->create([
        'lietotajvards' => 'admins',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => true,
        'vards' => 'Admin',
        'uzvards' => 'Lietotajs',
    ]);

    $this->vecaisAtbildigais = Lietotajs::query()->create([
        'lietotajvards' => 'vecais',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => false,
        'vards' => 'Vecais',
        'uzvards' => 'Atbildigais',
    ]);

    $this->jaunaisAtbildigais = Lietotajs::query()->create([
        'lietotajvards' => 'jaunais',
        'parole' => bcrypt('secret'),
        'admina_tiesibas' => false,
        'vards' => 'Jaunais',
        'uzvards' => 'Atbildigais',
    ]);

    $kategorijaId = DB::table('kategorija')->insertGetId([
        'nosaukums' => 'Datori',
        'apraksts' => 'Testa kategorija',
    ]);

    $this->vecaTelpaId = DB::table('telpa')->insertGetId([
        'nosaukums' => '101. telpa',
        'izmeri' => '5x5',
        'numurs' => 101,
        'stavs' => 1,
    ]);

    $this->jaunaTelpaId = DB::table('telpa')->insertGetId([
        'nosaukums' => '202. telpa',
        'izmeri' => '6x6',
        'numurs' => 202,
        'stavs' => 2,
    ]);

    $this->inventarsId = DB::table('inventars')->insertGetId([
        'nosaukums' => 'Testa dators',
        'kategorija_id' => $kategorijaId,
        'telpas_id' => $this->vecaTelpaId,
        'atbildigais_id' => $this->vecaisAtbildigais->lietotajs_id,
        'inventara_numurs' => 'INV-DEL-001',
        'iegades_datums' => '2026-04-01',
    ]);

    $this->parvietosanaVeidsId = DB::table('kustibas_veidi')->insertGetId([
        'nosaukums' => 'Pārvietošana',
        'apraksts' => 'Inventāra pārvietošana starp telpām',
    ]);

    $this->nodosanaVeidsId = DB::table('kustibas_veidi')->insertGetId([
        'nosaukums' => 'Nodošana',
        'apraksts' => 'Atbildīgā maiņa',
    ]);

    $this->parvietosanaId = DB::table('inventara_kustiba')->insertGetId([
        'datums' => '2026-04-02',
        'inventars_id' => $this->inventarsId,
        'atbildigais_lietotajs_id' => $this->vecaisAtbildigais->lietotajs_id,
        'Jatbildigais_lietotajs_id' => 0,
        'kustibas_veids_id' => $this->parvietosanaVeidsId,
        'veca_telpa_id' => $this->vecaTelpaId,
        'jauna_telpa_id' => $this->jaunaTelpaId,
        'piezimes' => 'Pārvietots uz citu telpu',
    ]);

    $this->nodosanaId = DB::table('inventara_kustiba')->insertGetId([
        'datums' => '2026-04-03',
        'inventars_id' => $this->inventarsId,
        'atbildigais_lietotajs_id' => $this->vecaisAtbildigais->lietotajs_id,
        'Jatbildigais_lietotajs_id' => $this->jaunaisAtbildigais->lietotajs_id,
        'kustibas_veids_id' => $this->nodosanaVeidsId,
        'veca_telpa_id' => $this->jaunaTelpaId,
        'jauna_telpa_id' => null,
        'piezimes' => 'Nodots citam atbildīgajam',
    ]);

    DB::table('inventars')
        ->where('inventars_id', $this->inventarsId)
        ->update([
            'telpas_id' => $this->jaunaTelpaId,
            'atbildigais_id' => $this->jaunaisAtbildigais->lietotajs_id,
        ]);
});

it('reverts inventars state when deleting movements', function () {
    $this->actingAs($this->admin)
        ->get("/inventara_kustiba/{$this->nodosanaId}/delete")
        ->assertRedirect('/inventara_kustiba');

    $inventarsAfterNodosanaDelete = DB::table('inventars')
        ->where('inventars_id', $this->inventarsId)
        ->first();

    expect((int) $inventarsAfterNodosanaDelete->telpas_id)->toBe($this->jaunaTelpaId);
    expect((int) $inventarsAfterNodosanaDelete->atbildigais_id)->toBe($this->vecaisAtbildigais->lietotajs_id);

    $this->actingAs($this->admin)
        ->get("/inventara_kustiba/{$this->parvietosanaId}/delete")
        ->assertRedirect('/inventara_kustiba');

    $inventarsAfterParvietosanaDelete = DB::table('inventars')
        ->where('inventars_id', $this->inventarsId)
        ->first();

    expect((int) $inventarsAfterParvietosanaDelete->telpas_id)->toBe($this->vecaTelpaId);
    expect((int) $inventarsAfterParvietosanaDelete->atbildigais_id)->toBe($this->vecaisAtbildigais->lietotajs_id);
});