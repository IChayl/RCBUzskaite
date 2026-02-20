<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventaraKustiba extends Model
{
    protected $table = 'inventara_kustiba';
    protected $primaryKey = 'kustiba_id';
    public $timestamps = false;

    protected $fillable = ['datums', 'inventars_id', 'no_atrasanas_vietas_id', 'uz_atrasanas_vietas_id', 'atbildigais_lietotajs_id'];
}
