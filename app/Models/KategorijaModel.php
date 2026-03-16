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

   // Atļauj masveida piešķiršanu šīm kolonnām
   protected $fillable = ['nosaukums', 'apraksts'];
}
