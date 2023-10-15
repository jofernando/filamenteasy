<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arquivo extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
       'nome',
       'path',
       'visivel',
   ];

   /**
    * The attributes that should be cast.
    *
    * @var array
    */
   protected $casts = [
       'visivel' => 'boolean',
   ];

   /**
    * Get the evento that owns the Modulo
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function evento(): BelongsTo
   {
       return $this->belongsTo(Evento::class);
   }
}
