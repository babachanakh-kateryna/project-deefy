<?php
namespace iutnc\deefy\auth;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Class Authz est une classe qui represente l'autorisation
 */
class Authz
{
    const ROLE_ADMIN = 100;

    // verifie le role de l'utilisateur authentifie est conforme
    public static function checkRole(User $user, int $requiredRole): void
    {
        // si l'utilisateur n'est pas admin et n'a pas le role requis
        if ($user->getRole() !== $requiredRole && !$user->isAdmin()) {
            throw new AuthnException("Access denied: insufficient role");
        }
    }

    // verifie si l'utilisateur authentifie est le proprietaire de la playlist ou admin
    public static function checkPlaylistOwner(User $user, int $playlistId): void
    {
        // si l'utilisateur est admin
        if ($user->isAdmin()) {
            return;
        }

        $repo = DeefyRepository::getInstance();
        $isOwner = $repo->isPlaylistOwner($user->getId(), $playlistId);

        if (!$isOwner) {
            throw new AuthnException("you do not own this playlist");
        }
    }
}