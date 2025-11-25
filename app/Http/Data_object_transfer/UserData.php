<?php

namespace App\Http\Data_object_transfer;

use App\Http\Requests\Auth\RegisterRequest;

class UserData
{
    public string $name;
    public string $email;
    public mixed $password;
    public string $phone;
    public string $role;
    public ?string $provider;

    public function __construct(string $name, string $email, mixed $password, string $phone, string $role = "user", ?string $provider)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->role = $role;
        $this->provider = $provider;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            preg_replace('/\s+/', ' ', trim($data['name'])),
            trim($data['email']),
            str_replace(' ', '', $data['password']),
            $data['phone'],
            $data['role'] ?? "user",
            $data['provider'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'role' => $this->role,
            'provider' => $this->provider,
        ];
    }
}
