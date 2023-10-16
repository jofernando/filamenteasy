<?php

namespace App\Models\Formulario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resposta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'resposta',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
        'mostrar_resposta_autor' => 'boolean',
    ];

    /**
     * Get the questao that owns the Resposta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questao(): BelongsTo
    {
        return $this->belongsTo(Questao::class);
    }

    /**
     * Get the user that owns the Resposta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the opcao that owns the Resposta
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opcao(): BelongsTo
    {
        return $this->belongsTo(Opcao::class);
    }
}
