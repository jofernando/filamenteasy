<?php

namespace App\Models;

use App\Models\Questionario\Questionario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submissao_inicio' => 'datetime:d/m/Y H:i',
        'submissao_fim' => 'datetime:d/m/Y H:i',
        'avaliacao_inicio' => 'datetime:d/m/Y H:i',
        'avaliacao_fim' => 'datetime:d/m/Y H:i',
        'resultado' => 'datetime:d/m/Y H:i',
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
        return $this->morphToMany(User::class, 'revisavel', 'revisores');
    }

    /**
     * Get all of the questionarios for the Modalidade.
     */
    public function questionarios(): MorphMany
    {
        return $this->morphMany(Questionario::class, 'questionavel');
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
