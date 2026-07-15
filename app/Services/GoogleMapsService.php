<?php

namespace App\Services;

use App\Exceptions\GeocodingException;
use Illuminate\Support\Facades\Http;

class GoogleMapsService
{
    public function geocode(string $address): array
    {
        $apiKey = config('services.google_maps.server_key');

        if (! $apiKey) {
            throw new GeocodingException('No se configuró GOOGLE_MAPS_SERVER_KEY.');
        }

        $response = Http::acceptJson()
            ->timeout(12)
            ->retry(2, 300)
            ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $apiKey,
                'language' => 'es',
                'region' => 'mx',
            ]);

        if ($response->failed()) {
            throw new GeocodingException('Google Maps no respondió correctamente.');
        }

        $payload = $response->json();
        $status = $payload['status'] ?? 'UNKNOWN_ERROR';

        if ($status === 'ZERO_RESULTS') {
            throw new GeocodingException('No se encontró la dirección proporcionada.');
        }

        if ($status !== 'OK') {
            throw new GeocodingException(
                'Error de Google Maps: ' . ($payload['error_message'] ?? $status)
            );
        }

        $result = $payload['results'][0] ?? null;
        $location = $result['geometry']['location'] ?? null;

        if (! $result || ! $location) {
            throw new GeocodingException('Google Maps devolvió una respuesta incompleta.');
        }

        return [
            'formatted_address' => $result['formatted_address'],
            'latitude' => (float) $location['lat'],
            'longitude' => (float) $location['lng'],
            'google_place_id' => $result['place_id'] ?? null,
        ];
    }
}
