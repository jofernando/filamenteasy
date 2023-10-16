<?php

namespace App\Models\Formulario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Formulario extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * Get all of the questoes for the Formulario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class);
    }
}
