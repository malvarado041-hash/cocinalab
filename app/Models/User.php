<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'codigo_empleado',
        'email',
        'password',
        'role',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSistemas(): bool
    {
        return $this->role === 'sistemas';
    }

    public function isPrivileged(): bool
    {
        return in_array($this->role, ['admin', 'sistemas'], true);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isActive(): bool
    {
        return $this->status === 'activo';
    }

    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
