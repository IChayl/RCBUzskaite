<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventar;

// Modelis norakstīšanas ierakstiem.
class Norakstishana extends Model
{
    protected $table = 'Norakstishana';
    protected $primaryKey = 'norakstishana_id';
    public $timestamps = false;

    // Lauki, kuriem atļauta masveida aizpilde.
    protected $fillable = ['inventara_id', 'norDatums', 'iemesls', 'talaka_riciba'];

    // Saite uz inventāru, kas norakstīts.
    public function inventars()
    {
        return $this->belongsTo(Inventar::class, 'inventara_id', 'inventars_id');
    }
}
