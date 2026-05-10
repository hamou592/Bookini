<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'provider_id'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles'
        );
    }

    public function hasRole($roles)
    {
        // SINGLE ROLE
        if (!is_array($roles)) {

            return $this->roles()
                ->where('name', $roles)
                ->exists();
        }

        // MULTIPLE ROLES
        return $this->roles()
            ->whereIn('name', $roles)
            ->exists();
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}