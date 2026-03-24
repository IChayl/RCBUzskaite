<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventar;
use App\Models\Lietotajs;

// Modelis norakstīšanas ierakstiem.
class Norakstishana extends Model
{
    protected $table = 'Norakstishana';
    protected $primaryKey = 'norakstishana_id';
    public $timestamps = false;

    // Lauki, kuriem atļauta masveida aizpilde.
    protected $fillable = [
        'inventara_id',
        'norDatums',
        'pieteikuma_datums',
        'apstiprinasanas_datums',
        'akceptets',
        'pieteica_lietotajs_id',
        'iemesls',
        'talaka_riciba',
    ];

    protected $casts = [
        'norDatums' => 'date:Y-m-d',
        'pieteikuma_datums' => 'date:Y-m-d',
        'apstiprinasanas_datums' => 'date:Y-m-d',
        'akceptets' => 'boolean',
    ];

    // Saite uz inventāru, kas norakstīts.
    public function inventars()
    {
        return $this->belongsTo(Inventar::class, 'inventara_id', 'inventars_id');
    }

    // Saite uz lietotāju, kurš iesniedza pieteikumu.
    public function pieteicejs()
    {
        return $this->belongsTo(Lietotajs::class, 'pieteica_lietotajs_id', 'lietotajs_id');
    }
}
