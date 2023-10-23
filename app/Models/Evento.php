<?php

namespace App\Models;

use App\Models\Inscricao\Categoria;
use App\Models\Questionario\Questionario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use SoftDeletes, HasFactory;

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

    public function questionarios()
    {
        $cIds = $this->categorias()->pluck('id')->all();
        $mIds = $this->modalidades()->pluck('id')->all();
        return Questionario::where(function ($query) use ($cIds, $mIds)  {
            $query->where(function ($query) use ($cIds) {
                $query->whereIn('questionavel_id', $cIds)
                    ->where('questionavel_type', Categoria::class);
            })->orWhere(function ($query) use ($mIds) {
                $query->whereIn('questionavel_id', $mIds)
                    ->where('questionavel_type', Modalidade::class);
            });
        });
    }

    protected function getQuestionariosAttribute()
    {
        return $this->questionarios()->get();
    }

    /**
     * Get all of the trabalhos for the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function trabalhos(): HasManyThrough
    {
        return $this->hasManyThrough(Trabalho::class, Modalidade::class);
    }

    /**
     * Get all of the categorias for the Evento
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categorias(): HasMany
    {
        return $this->hasMany(Categoria::class);
    }
}
