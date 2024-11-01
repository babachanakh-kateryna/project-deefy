<?php
namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use PDO;
use iutnc\deefy\exception\AuthnException;

class AuthProvider
{
    // methode pour se connecter
    public static function signin(string $email, string $passwd2check): void
    {
        $pdo = DeefyRepository::getInstance()->getPDO();

        $stmt = $pdo->prepare("SELECT passwd FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$user) {
            throw new AuthnException("User not found with email $email");
        }

//        echo "User hashed password from DB: {$user['passwd']}";
//        echo "Entered password: $passwd2check";

        if (!password_verify($passwd2check, $user['passwd'])) {
            throw new AuthnException("Password verification failed for email $email");
        }

        $_SESSION['user'] = $email;
    }

}
