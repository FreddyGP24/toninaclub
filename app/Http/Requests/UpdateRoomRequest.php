<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ($this->user()?->isOwner() ?? false)
            || ($this->user()?->isAdmin() ?? false);
    }


    public function rules(): array
    {
        $hotelId = $this->route('hotel')?->id;
        $roomId = $this->route('room')?->id;

        return [
            'room_number' => [
                'required', 'string', 'max:20',
                Rule::unique('rooms', 'room_number')
                    ->where(fn ($query) => $query->where('hotel_id', $hotelId))
                    ->ignore($roomId),
            ],
            'type' => ['required', 'string', 'max:80'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'price_per_night' => ['required', 'numeric', 'min:1', 'max:999999.99'],
            'description' => ['nullable', 'string', 'max:1000'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string', 'max:80'],
            'active' => ['nullable', 'boolean'],
        ];
    }

}
