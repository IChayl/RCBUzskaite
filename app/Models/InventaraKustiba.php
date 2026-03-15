<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventar;
use App\Models\KustibasVeidi;
use App\Models\Lietotajs;
use App\Models\Telpa;

class InventaraKustiba extends Model
{
    protected $table = 'inventara_kustiba';
    protected $primaryKey = 'kustiba_id';
    public $timestamps = false;

    protected $fillable = ['datums', 'inventars_id', 'atbildigais_lietotajs_id', 'kustibas_veids_id', 'veca_telpa_id', 'jauna_telpa_id', 'piezimes', 'dokuments'];

    public function inventars()
    {
        return $this->belongsTo(Inventar::class, 'inventars_id', 'inventars_id');
    }

    public function kustibasVeids()
    {
        return $this->belongsTo(KustibasVeidi::class, 'kustibas_veids_id', 'kustibas_veids_id');
    }

    public function lietotajs()
    {
        return $this->belongsTo(Lietotajs::class, 'atbildigais_lietotajs_id', 'lietotajs_id');
    }

    public function vecaTelpa()
    {
        return $this->belongsTo(Telpa::class, 'veca_telpa_id', 'telpas_id');
    }

    public function jaunaTelpa()
    {
        return $this->belongsTo(Telpa::class, 'jauna_telpa_id', 'telpas_id');
    }
}
