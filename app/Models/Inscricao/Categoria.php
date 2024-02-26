<?php

namespace App\Models\Inscricao;

use App\Casts\MoneyCast;
use App\Models\Evento;
use App\Models\Questionario\Questionario;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;

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

    /**
     * Get all of the inscricoes for the Categoria
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }

    public function components()
    {
        $components = [];
        foreach ($this->questionarios as $questionario) {
            $questoes = [];
            foreach ($questionario->questoes as $questao) {
                if ($questao->tipo == 'DISCURSIVA') {
                    $questoes[] = TextInput::make($questao->id)
                        ->label(fn() => new HtmlString($questao->nome));
                } else {
                    $opcoes = $questao
                        ->opcoes
                        ->map(fn($opcao) => [$opcao->id => new HtmlString($opcao->nome)])
                        ->flatten()
                        ->all();
                    $questoes[] = Select::make($questao->id)
                        ->label(fn() => new HtmlString($questao->nome))
                        ->options($opcoes);
                }
            }
            $components[] = Section::make($questionario->nome)
                ->description(fn() => new HtmlString($questionario->descricao))
                ->schema($questoes);
        }
        return $components;
    }
}
