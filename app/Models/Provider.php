<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name',
        'type',
        'phone',
        'logo',
        'subscription_status',
    'subscription_start_at',
    'subscription_expires_at',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function getSubscriptionExpiresAtAttribute()
{
    return $this->subscription_start_at
        ? \Carbon\Carbon::parse($this->subscription_start_at)->addDays(30)
        : null;
}
}
