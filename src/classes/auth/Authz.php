<?php
namespace iutnc\deefy\auth;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

class Authz
{
    const ROLE_ADMIN = 100;

    // verifie le role de l'utilisateur authentifie est conforme
    public static function checkRole(int $requiredRole): void
    {
        $user = AuthProvider::getSignedInUser();

        if ($user['role'] !== $requiredRole && $user['role'] !== self::ROLE_ADMIN) {
            throw new AuthnException("Access denied: insufficient role");
        }
    }

    // verifie si l'utilisateur authentifie est le proprietaire de la playlist ou admin
    public static function checkPlaylistOwner(int $playlistId): void
    {
        $user = AuthProvider::getSignedInUser();
        $userId = $user['id'];
        $repo = DeefyRepository::getInstance();

        $stmt = $repo->getPDO()->prepare("
            SELECT 1 FROM user2playlist WHERE id_user = :userId AND id_pl = :playlistId
        ");
        $stmt->execute(['userId' => $userId, 'playlistId' => $playlistId]);

        if (!$stmt->fetch() && $user['role'] !== self::ROLE_ADMIN) {
            throw new AuthnException("Access denied: you do not own this playlist");
        }
    }
}