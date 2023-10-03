<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'email',
        'tipo',
        'recolhimento',
        'descricao',
        'banner',
        'logo',
        'inicio',
        'fim',
    ];

    public const TIPOS = array(
        'Congresso' => 'Congresso',
        'Encontro' => 'Encontro',
        'Seminário' => 'Seminário',
        'Mesa' => 'Mesa',
        'Simpósio' => 'Simpósio',
        'Painel' => 'Painel',
        'Fórum' => 'Fórum',
        'Conferência' => 'Conferência',
        'Jornada' => 'Jornada',
        'Cursos' => 'Cursos',
        'Colóquio' => 'Colóquio',
        'Semana' => 'Semana',
        'Workshop' => 'Workshop',
        'Outro' => 'Outro',
    );

    public const RECOLHIMENTOS = array(
        'Apoiado' => 'Apoiado',
        'Gratuito' => 'Gratuito',
        'Pago' => 'Pago',
    );

    /**
     * Get the user that owns the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the areas for the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }
}
