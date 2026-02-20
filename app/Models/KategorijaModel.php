<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategorijaModel extends Model
{
   protected $table = 'kategorija';
   protected $primaryKey = 'kategorija_id';
   public $incrementing = true;
   protected $keyType = 'int';
   public $timestamps = false;

   // allow mass assignment for these columns
   protected $fillable = ['nosaukums', 'apraksts'];
}
