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
            $table->string('apraksts', 200)->nullable();
            $table->string('statuss', 25)->nullable();
            $table->unsignedInteger('kategorija_id');
            $table->unsignedInteger('telpas_id');
            $table->unsignedInteger('atbildigais_id')->nullable();
            $table->string('inventara_numurs', 50)->nullable();
            $table->date('iegades_datums')->nullable();

            $table->foreign('kategorija_id')->references('kategorija_id')->on('kategorija')->onDelete('cascade');
            $table->foreign('telpas_id')->references('telpas_id')->on('telpa')->onDelete('cascade');
            $table->foreign('atbildigais_id')->references('lietotajs_id')->on('lietotajs')->onDelete('set null');
        });

        Schema::create('inventara_kustiba', function (Blueprint $table) {
            $table->increments('kustiba_id');
            $table->date('datums');
            $table->unsignedInteger('inventars_id');
            $table->unsignedInteger('atbildigais_lietotajs_id');
            $table->unsignedInteger('kustibas_veids_id')->nullable();
            $table->unsignedInteger('veca_telpa_id')->nullable();
            $table->unsignedInteger('jauna_telpa_id')->nullable();
            $table->string('piezimes', 255)->nullable();
            $table->string('dokuments', 255)->nullable();

            $table->foreign('inventars_id')->references('inventars_id')->on('inventars')->onDelete('cascade');
            $table->foreign('atbildigais_lietotajs_id')->references('lietotajs_id')->on('lietotajs')->onDelete('cascade');
            $table->foreign('kustibas_veids_id')->references('kustibas_veids_id')->on('kustibas_veidi')->onDelete('set null');
            $table->foreign('veca_telpa_id')->references('telpas_id')->on('telpa')->onDelete('set null');
            $table->foreign('jauna_telpa_id')->references('telpas_id')->on('telpa')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventara_kustiba');
        Schema::dropIfExists('inventars');
        Schema::dropIfExists('kustibas_veidi');
        Schema::dropIfExists('lietotajs');
        Schema::dropIfExists('telpa');
        Schema::dropIfExists('kategorija');
    }
};