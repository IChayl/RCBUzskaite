<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtrasanasVieta extends Model
{
    protected $table = 'atrasanas_vieta';
    protected $primaryKey = 'atrasanas_vieta_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // allow mass assignment for these columns
    protected $fillable = ['nodala', 'telpas_id', 'stavs'];
}
