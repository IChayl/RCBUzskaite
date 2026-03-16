<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategorijaModel;
use App\Models\Telpa;
use App\Models\Lietotajs;

// Modelis inventāra vienībām.
class Inventar extends Model
{
    protected $table = 'inventars';
    protected $primaryKey = 'inventars_id';
    public $timestamps = false;

    // Lauki, kuriem atļauta masveida aizpilde.
    protected $fillable = ['nosaukums', 'apraksts', 'statuss', 'kategorija_id', 'telpas_id', 'atbildigais_id', 'inventara_numurs', 'iegades_datums'];

    // Saite uz kategoriju, kurai inventārs pieder.
    public function kategorija()
    {
        return $this->belongsTo(KategorijaModel::class, 'kategorija_id', 'kategorija_id');
    }

    // Saite uz telpu, kur inventārs atrodas.
    public function telpa()
    {
        return $this->belongsTo(Telpa::class, 'telpas_id', 'telpas_id');
    }

    // Saite uz atbildīgo lietotāju.
    public function atbildigais()
    {
        return $this->belongsTo(Lietotajs::class, 'atbildigais_id', 'lietotajs_id');
    }
}
