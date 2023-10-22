<?php

namespace App\Models\Questionario;

use App\Models\Modalidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionario extends Model
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
        'modalidade_id',
        'questionavel_id',
        'questionavel_type',
    ];

    /**
     * Get all of the questoes for the Questionario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questoes(): HasMany
    {
        return $this->hasMany(Questao::class);
    }

     /**
     * Get the parent questionavel model (modalidade or categoria).
     */
    public function questionavel(): MorphTo
    {
        return $this->morphTo();
    }
}
