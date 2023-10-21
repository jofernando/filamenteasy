<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trabalho extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'arquivo',
    ];

    /**
     * Get the area that owns the Trabalho
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the modalidade that owns the Trabalho
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modalidade(): BelongsTo
    {
        return $this->belongsTo(Modalidade::class);
    }

    /**
     * Get the user that owns the Trabalho
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The coautores that belong to the Trabalho
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coautores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'coautores');
    }
}
