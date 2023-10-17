<?php

namespace App\Models\Formulario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questao extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'tipo',
        'mostrar_resposta_autor',
        'formulario_id',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
        'mostrar_resposta_autor' => 'boolean',
    ];

    public const TIPOS = array(
        'DISCURSIVA' => 'Discursiva',
        'MULTIPLA_ESCOLHA_UNICA' => 'Multipla escolha única',
        'MULTIPLA_ESCOLHA' => 'Multipla escolha',
    );

    /**
     * Get all of the opcoes for the Questao
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function opcoes(): HasMany
    {
        return $this->hasMany(Opcao::class);
    }

    /**
     * Get the formulario that owns the Questao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formulario(): BelongsTo
    {
        return $this->belongsTo(Formulario::class);
    }
}
