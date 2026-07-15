<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    public function register(array $data): User
    {
        return $this->users->create([
            'name' => $data['name'],
            'email' => Str::lower($data['email']),
            'role' => UserRole::from($data['role'])->value,
            'active' => true,
            'password' => $data['password'],
        ]);
    }

    public function authenticate(
        string $email,
        string $password,
        bool $remember,
        string $ipAddress
    ): void {
        $email = Str::lower(trim($email));
        $key = Str::transliterate($email . '|' . $ipAddress);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Demasiados intentos. Intenta de nuevo en {$seconds} segundos.",
            ]);
        }

        if (! Auth::attempt([
            'email' => $email,
            'password' => $password,
            'active' => true,
        ], $remember)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => 'El correo o la contraseña son incorrectos.',
            ]);
        }

        RateLimiter::clear($key);
    }
}
