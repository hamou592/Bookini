<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'provider_id',
        'department_id',
        'name',
        'price',
        'duration',
        'is_active',
    ];

    public $timestamps = false;

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}