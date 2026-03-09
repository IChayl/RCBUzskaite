<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KustibasVeidi extends Model
{
    protected $table = 'kustibas_veidi';
    protected $primaryKey = 'kustibas_veids_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['nosaukums', 'apraksts'];
}
