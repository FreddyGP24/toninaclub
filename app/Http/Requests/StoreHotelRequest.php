<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ($this->user()?->isOwner() ?? false)
            || ($this->user()?->isAdmin() ?? false);
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\\-\\s()]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_line' => ['required', 'string', 'max:200'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'country' => ['required', 'string', 'max:100'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string', 'max:80'],
            'active' => ['nullable', 'boolean'],
        ];
    }

}
