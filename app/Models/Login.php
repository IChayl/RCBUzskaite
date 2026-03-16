<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Login extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
    * Izmanto esošo Lietotajs tabulu, lai pieteikšanās lietotu to pašu lietotāju glabātuvi.
     */
    protected $table = 'lietotajs';
    protected $primaryKey = 'lietotajs_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    /**
     * Atribūti, kuriem atļauta masveida piešķiršana.
     */
    protected $fillable = [
        'lietotajvards',
        'parole',
        'admina_tiesibas',
    ];

    /**
     * Atspējo noklusēto remember token kolonnu, jo tā neeksistē.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}
