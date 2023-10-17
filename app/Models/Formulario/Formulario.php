<?php

namespace App\Models\Formulario;

use App\Models\Modalidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formulario extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'modalidade_id'
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

    /**
     * Get the modalidade that owns the Formulario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modalidade(): BelongsTo
    {
        return $this->belongsTo(Modalidade::class);
    }
}
