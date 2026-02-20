<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lietotajs extends Model
{
    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajs_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['lietotajvards', 'parole', 'admina_tiesibas'];
}
