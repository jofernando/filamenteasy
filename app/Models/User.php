<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all of the eventos for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }

    /**
     * Pega todas as areas que o usuário é responsável por revisar.
     */
    public function areas_revisaveis(): MorphToMany
    {
        return $this->morphedByMany(Area::class, 'revisavel', 'revisores');
    }

    /**
     * Pega todas as modalidades que o usuário é responsável por revisar.
     */
    public function modalidades_revisaveis(): MorphToMany
    {
        return $this->morphedByMany(Modalidade::class, 'revisavel', 'revisores');
    }

    /**
     * The cotrabalhos that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cotrabalhos(): BelongsToMany
    {
        return $this->belongsToMany(Trabalho::class, 'coautores');
    }
}
