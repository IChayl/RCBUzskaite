<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\KategorijaModel;
use App\Models\Telpa;
use App\Models\Lietotajs;
use App\Models\Norakstishana;

// Modelis inventāra vienībām.
class Inventar extends Model
{
    protected $table = 'inventars';
    protected $primaryKey = 'inventars_id';
    public $timestamps = false;

    // Lauki, kuriem atļauta masveida aizpilde.
    protected $fillable = ['nosaukums', 'kategorija_id', 'telpas_id', 'atbildigais_id', 'inventara_numurs', 'iegades_datums'];

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

    // Saite uz inventāra norakstīšanas ierakstiem.
    public function norakstishanas()
    {
        return $this->hasMany(Norakstishana::class, 'inventara_id', 'inventars_id');
    }

    // Atstāj tikai inventāru, kam nav akceptēta norakstīšana.
    public function scopeWithoutAcceptedNorakstishana(Builder $query): Builder
    {
        return $query->whereDoesntHave('norakstishanas', function (Builder $subQuery) {
            $subQuery->where('akceptets', true);
        });
    }

    public function scopeOnlyAcceptedNorakstishana(Builder $query): Builder
    {
        return $query->whereHas('norakstishanas', function (Builder $subQuery) {
            $subQuery->where('akceptets', true);
        });
    }
}
