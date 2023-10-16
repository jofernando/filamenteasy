<?php

namespace App\Models\Formulario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opcao extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
    ];

    /**
     * Get the questao that owns the Opcao
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }
}
