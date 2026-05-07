<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventar;
use App\Models\KustibasVeidi;
use App\Models\Lietotajs;
use App\Models\Telpa;

// Modelis inventāra kustību ierakstiem.
class InventaraKustiba extends Model
{
    protected $casts = [
        'apstiprinats' => 'boolean',
    ];

    // Lauki, kuriem atļauta masveida aizpilde.
    protected $fillable = ['datums', 'inventars_id', 'atbildigais_lietotajs_id', 'Jatbildigais_lietotajs_id', 'kustibas_veids_id', 'veca_telpa_id', 'jauna_telpa_id', 'piezimes', 'apstiprinats'];

    // Saite uz inventāru, kuram veikta kustība.
    public function inventars()
    {
        return $this->belongsTo(Inventar::class, 'inventars_id', 'inventars_id');
    }

    // Saite uz kustības veidu (piem., pārvietošana, norakstīšana).
    public function kustibasVeids()
    {
        return $this->belongsTo(KustibasVeidi::class, 'kustibas_veids_id', 'kustibas_veids_id');
    }

    // Saite uz lietotāju, kas atbild par kustību.
    public function lietotajs()
    {
        return $this->belongsTo(Lietotajs::class, 'atbildigais_lietotajs_id', 'lietotajs_id');
    }

    // Saite uz iepriekšējo telpu.
    public function vecaTelpa()
    {
        return $this->belongsTo(Telpa::class, 'veca_telpa_id', 'telpas_id');
    }

    // Saite uz jauno telpu.
    public function jaunaTelpa()
    {
        return $this->belongsTo(Telpa::class, 'jauna_telpa_id', 'telpas_id');
    }

    // Saite uz jauno atbildīgo lietotāju (Nodošanas gadījumā).
    public function jaunaisAtbildigais()
    {
        return $this->belongsTo(Lietotajs::class, 'Jatbildigais_lietotajs_id', 'lietotajs_id');
    }
}
