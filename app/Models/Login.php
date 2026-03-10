<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    /**
     * Use the existing Lietotajs table so login uses the same user store.
     */
    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajs_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'lietotajvards',
        'parole',
        'admina_tiesibas',
    ];
}
