<?php
namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use PDO;
use iutnc\deefy\exception\AuthnException;

/**
 * Class AuthProvider est une classe qui represente le fournisseur d'authentification
 */
class AuthProvider
{
    // methode pour se connecter
    public static function signin(string $email, string $passwd2check): void
    {
        $pdo = DeefyRepository::getInstance()->getPDO();

        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // verifier si l'utilisateur existe
        if (!$user) {
            throw new AuthnException("User not found with email $email");
        }

//        echo "User hashed password from DB: {$user['passwd']}";
//        echo "Entered password: $passwd2check";

        // verifier si le mot de passe est correct
        if (!password_verify($passwd2check, $user['passwd'])) {
            throw new AuthnException("Password verification failed for email $email");
        }
        //echo $user['id'];

        // stocker l'utilisateur dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $email;
    }

    // methode pour s'inscrire
    public static function register(string $email, string $pass): void
    {
        $pdo = DeefyRepository::getInstance()->getPDO();

        // verifier si l'email est corect
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Auth error: invalid user email");
        }

        // verifier si le mot de passe est minimum 10 caracteres
        if (strlen($pass) < 10) {
            throw new AuthnException("Auth error: password must be at least 10 characters long");
        }

        // verifier si l'utilisateur existe deja
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            throw new AuthnException("Auth error: an account with this email already exists");
        }

        // hasher le mot de passe
        $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);

        // ajouter l'utilisateur a la base de donnees avec le role 1
        $stmt = $pdo->prepare("INSERT INTO user (email, passwd, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $hash, 1]);
    }

    public static function getSignedInUser(): array
    {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Auth error: not signed in");
        }

        return $_SESSION['user'];
    }

}
