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

    protected $fillable = ['lietotajvards', 'parole', 'admina_tiesibas', 'avatar', 'vards', 'uzvards', 'epasts', 'telefons', 'amats', 'aktivs'];

    /**
     * Atspējo noklusēto remember token kolonnu, jo tā neeksistē.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
