<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get dashboard route based on user role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard', // Para implementação futura
            'user' => 'user.dashboard', // Para implementação futura
            default => 'admin.dashboard'
        };
    }

    /**
     * Relacionamento com roles (many-to-many)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Orçamentos criados pelo usuário
     */
    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class);
    }

    /**
     * Histórico de ações do usuário
     */
    public function historicoAcoes(): HasMany
    {
        return $this->hasMany(OrcamentoHistorico::class);
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission)->where('active', true);
        })->exists();
    }

    /**
     * Verifica se o usuário tem uma role específica
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->where('active', true)->exists();
    }

    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
}
