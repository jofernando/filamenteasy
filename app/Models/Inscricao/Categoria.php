<?php

namespace App\Models\Inscricao;

use App\Casts\MoneyCast;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'valor',
    ];

    protected $casts = [
        'valor' => MoneyCast::class,
    ];

    /**
     * Get all of the questionarios for the Categoria.
     */
    public function questionarios(): MorphMany
    {
        return $this->morphMany(Questionario::class, 'questionavel');
    }

    /**
     * Get the evento that owns the Categoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class);
    }
}
