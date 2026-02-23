<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Telpa;

class AtrasanasVieta extends Model
{
    protected $table = 'atrasanas_vieta';
    protected $primaryKey = 'atrasanas_vieta_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // allow mass assignment for these columns
    protected $fillable = ['nodala', 'telpas_id', 'stavs'];

    public function telpa()
    {
        return $this->belongsTo(Telpa::class, 'telpas_id', 'telpas_id');
    }
}
