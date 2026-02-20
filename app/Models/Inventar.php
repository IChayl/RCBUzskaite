<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventar extends Model
{
    protected $table = 'inventars';
    protected $primaryKey = 'inventars_id';
    public $timestamps = false;

    protected $fillable = ['nosaukums', 'apraksts', 'nolietojums', 'statuss', 'kategorija_id', 'atrasanas_vieta_id'];
}
