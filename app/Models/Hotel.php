<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'slug', 'description', 'phone', 'email',
        'address_line', 'city', 'state', 'postal_code', 'country',
        'formatted_address', 'latitude', 'longitude', 'google_place_id',
        'services', 'active',
    ];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
