<?php

namespace App\Models;

use App\Models\Formulario\Formulario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

    /**
     * Get all of the modalidades for the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modalidades(): HasMany
    {
        return $this->hasMany(Modalidade::class);
    }

    /**
     * Get all of the arquivos for the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function arquivos(): HasMany
    {
        return $this->hasMany(Arquivo::class);
    }

    public function revisores()
    {
        $id = $this->id;
        return User::where(function ($query) use($id) {
            $query->whereHas('areas_revisaveis.evento', function ($query) use ($id) {
                $query->where('eventos.id', $id);
            })->orWhereHas('modalidades_revisaveis.evento', function ($query) use ($id) {
                $query->where('eventos.id', $id);
            });
        });
    }

    protected function getRevisoresAttribute()
    {
        return $this->revisores()->get();
    }

    /**
     * Get all of the formularios for the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function formularios(): HasManyThrough
    {
        return $this->hasManyThrough(Formulario::class, Modalidade::class);
    }
}
