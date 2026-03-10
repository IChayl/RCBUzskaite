<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Lietotajs extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajs_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['lietotajvards', 'parole', 'admina_tiesibas'];

    /**
     * Disable the default remember token column since it doesn't exist.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
