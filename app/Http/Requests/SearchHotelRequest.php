<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchHotelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'check_in' => ['nullable', 'date', 'after_or_equal:today'],
            'check_out' => ['nullable', 'date', 'required_with:check_in', 'after:check_in'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:20'],
        ];
    }
}
