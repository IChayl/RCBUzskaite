<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventar;
use App\Models\AtrasanasVieta;
use App\Models\Lietotajs;

class InventaraKustiba extends Model
{
    protected $table = 'inventara_kustiba';
    protected $primaryKey = 'kustiba_id';
    public $timestamps = false;

    protected $fillable = ['datums', 'inventars_id', 'no_atrasanas_vietas_id', 'uz_atrasanas_vietas_id', 'atbildigais_lietotajs_id'];

    public function inventars()
    {
        return $this->belongsTo(Inventar::class, 'inventars_id', 'inventars_id');
    }

    public function noVieta()
    {
        return $this->belongsTo(AtrasanasVieta::class, 'no_atrasanas_vietas_id', 'atrasanas_vieta_id');
    }

    public function uzVieta()
    {
        return $this->belongsTo(AtrasanasVieta::class, 'uz_atrasanas_vietas_id', 'atrasanas_vieta_id');
    }

    public function lietotajs()
    {
        return $this->belongsTo(Lietotajs::class, 'atbildigais_lietotajs_id', 'lietotajs_id');
    }
}
