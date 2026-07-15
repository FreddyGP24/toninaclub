<?php

namespace App\Services;

use App\Models\Hotel;
use App\Models\User;
use App\Repositories\HotelRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HotelService
{
    public function __construct(
        private readonly HotelRepository $hotels,
        private readonly GoogleMapsService $maps
    ) {
    }

    public function create(array $data, User $user): Hotel
    {
        $this->ensureCanCreate($user);
        $coordinates = $this->maps->geocode($this->buildAddress($data));

        return DB::transaction(fn () => $this->hotels->create([
            ...$data,
            'owner_id' => $user->id,
            'slug' => $this->uniqueSlug($data['name']),
            'country' => $data['country'] ?? 'México',
            ...$coordinates,
            'services' => $data['services'] ?? [],
            'active' => (bool) ($data['active'] ?? true),
        ]));
    }

    public function update(Hotel $hotel, array $data, User $user): Hotel
    {
        $this->ensureCanManage($user, $hotel);

        $addressChanged = collect([
            'address_line', 'city', 'state', 'postal_code', 'country',
        ])->contains(fn ($field) =>
            array_key_exists($field, $data)
            && $hotel->{$field} !== ($data[$field] ?? null)
        );

        if ($addressChanged) {
            $coordinates = $this->maps->geocode($this->buildAddress([
                'address_line' => $data['address_line'] ?? $hotel->address_line,
                'city' => $data['city'] ?? $hotel->city,
                'state' => $data['state'] ?? $hotel->state,
                'postal_code' => $data['postal_code'] ?? $hotel->postal_code,
                'country' => $data['country'] ?? $hotel->country,
            ]));
            $data = [...$data, ...$coordinates];
        }

        if (isset($data['name']) && $data['name'] !== $hotel->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $hotel->id);
        }

        $data['services'] = $data['services'] ?? [];

        return DB::transaction(fn () => $this->hotels->update($hotel, $data));
    }

    public function deactivate(Hotel $hotel, User $user): void
    {
        $this->ensureCanManage($user, $hotel);

        if ($this->hotels->hasFutureActiveReservations($hotel)) {
            throw ValidationException::withMessages([
                'hotel' => 'No puedes desactivar un hotel con reservaciones vigentes.',
            ]);
        }

        $this->hotels->update($hotel, ['active' => false]);
    }

    public function ensureCanManage(User $user, Hotel $hotel): void
    {
        abort_unless($user->isAdmin() || $hotel->owner_id === $user->id, 403);
    }

    private function ensureCanCreate(User $user): void
    {
        abort_unless($user->isAdmin() || $user->isOwner(), 403);
    }

    private function buildAddress(array $data): string
    {
        return collect([
            $data['address_line'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['postal_code'] ?? null,
            $data['country'] ?? 'México',
        ])->filter()->implode(', ');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $number = 2;

        while ($this->hotels->slugExists($slug, $ignoreId)) {
            $slug = "{$base}-{$number}";
            $number++;
        }

        return $slug;
    }
}
