<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'provider_id',
        'name',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    public function services()
{
    return $this->hasMany(Service::class);
}
}