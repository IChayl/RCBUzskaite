<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\KategorijaModel;
use App\Models\AtrasanasVieta;

class Inventar extends Model
{
    protected $table = 'inventars';
    protected $primaryKey = 'inventars_id';
    public $timestamps = false;

    protected $fillable = ['nosaukums', 'apraksts', 'nolietojums', 'statuss', 'kategorija_id', 'atrasanas_vieta_id'];

    public function kategorija()
    {
        return $this->belongsTo(KategorijaModel::class, 'kategorija_id', 'kategorija_id');
    }

    public function vieta()
    {
        return $this->belongsTo(AtrasanasVieta::class, 'atrasanas_vieta_id', 'atrasanas_vieta_id');
    }
}
