<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Login extends Authenticatable
{
    use HasFactory, Notifiable;

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

    /**
     * Disable the default remember token column since it doesn't exist.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
