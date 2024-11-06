<?php

namespace iutnc\deefy\auth;

// Class User est une classe qui represente l'utilisateur
class User
{
    protected int $id;
    protected string $email;
    protected int $role;

    public function __construct(int $id, string $email, int $role)
    {
        $this->id = $id;
        $this->email = $email;
        $this->role = $role;
    }


    // verifier si l'utilisateur est admin
    public function isAdmin(): bool
    {
        return $this->role === Authz::ROLE_ADMIN;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    // convertir l'objet User en tableau
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role
        ];
    }

}