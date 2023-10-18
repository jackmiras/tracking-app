<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class TrackingData extends Model
{
    use HasFactory;

    protected $fillable = [
        'datetime',
        'ip_address',
        'location',
        'os',
        'device',
        'referrer',
        'url',
        'language',
    ];

    public function location(): HasOne
    {
        return $this->hasOne(Location::class);
    }
}
