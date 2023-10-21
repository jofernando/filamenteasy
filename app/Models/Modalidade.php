<?php

namespace App\Models;

use App\Models\Formulario\Formulario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modalidade extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'submissao_inicio',
        'submissao_fim',
        'avaliacao_inicio',
        'avaliacao_fim',
        'resultado',
    ];

    /**
     * Get the evento that owns the Modalidade
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }

    /**
     * Get all of the revisores for the Modalidade.
     */
    public function revisores(): MorphToMany
    {
        return $this->morphToMany(User::class, 'revisores');
    }

    /**
     * Get the formulario associated with the Modalidade
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function formulario(): HasOne
    {
        return $this->hasOne(Formulario::class);
    }

    /**
     * Get all of the trabalhos for the Modalidade
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trabalhos(): HasMany
    {
        return $this->hasMany(Trabalho::class);
    }
}
