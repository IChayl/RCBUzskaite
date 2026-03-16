<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telpa extends Model
{
    protected $table = 'telpa';
    protected $primaryKey = 'telpas_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // Atļauj masveida piešķiršanu šīm kolonnām
    protected $fillable = ['nosaukums', 'izmeri', 'numurs', 'stavs'];
}
