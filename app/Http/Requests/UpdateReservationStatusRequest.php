<?php

namespace App\Http\Requests;

use App\Enums\ReservationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ($this->user()?->isOwner() ?? false)
            || ($this->user()?->isAdmin() ?? false);
    }

    public function rules(): array
    {
        return ['status' => ['required', Rule::enum(ReservationStatus::class)]];
    }
}
