<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategorijaModel;
use App\Models\Telpa;
use App\Models\Lietotajs;

class Inventar extends Model
{
    protected $table = 'inventars';
    protected $primaryKey = 'inventars_id';
    public $timestamps = false;

    protected $fillable = ['nosaukums', 'apraksts', 'nolietojums', 'statuss', 'kategorija_id', 'telpas_id', 'atbildigais_id'];

    public function kategorija()
    {
        return $this->belongsTo(KategorijaModel::class, 'kategorija_id', 'kategorija_id');
    }

    public function telpa()
    {
        return $this->belongsTo(Telpa::class, 'telpas_id', 'telpas_id');
    }

    public function atbildigais()
    {
        return $this->belongsTo(Lietotajs::class, 'atbildigais_id', 'lietotajs_id');
    }
}
