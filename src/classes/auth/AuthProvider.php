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
        $repo = DeefyRepository::getInstance();
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $userData = $repo->getUserByEmail($email);

        // verifier si l'utilisateur existe
        if (!$userData) {
            throw new AuthnException("User not found with email ". htmlspecialchars($email));
        }

//        echo "User hashed password from DB: {$user['passwd']}";
//        echo "Entered password: $passwd2check";

        // verifier si le mot de passe est correct
        if (!password_verify($passwd2check, $userData['passwd'])) {
            throw new AuthnException("Password verification failed for email ". htmlspecialchars($email));
        }
        //echo $user['id'];

        if (!isset($userData['role'])) {
            throw new AuthnException("Authentication error: role is undefined for this user.");
        }

        // creer un objet User
        $user = new User($userData['id'], $userData['email'], $userData['role']);
        // stocker l'utilisateur dans la session
        $_SESSION['user'] = $user->toArray();
    }

    // methode pour s'inscrire
    public static function register(string $email, string $pass): void
    {
        $repo = DeefyRepository::getInstance();
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // verifier si l'email est corect
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Auth error: invalid user email");
        }

        // verifier la force du mot de passe
        if (!self::checkPasswordStrength($pass)) {
            throw new AuthnException("Auth error: password is too weak");
        }

        // verifier si l'utilisateur existe deja
        if ($repo->userExists($email)) {
            throw new AuthnException("Auth error: an account with this email already exists");
        }

        // hasher le mot de passe
        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);

        // ajouter l'utilisateur a la base de donnees avec le role 1
        $repo->addUser($email, $hash, 1);
    }

    public static function getSignedInUser(): ?User
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }

        $userData = $_SESSION['user'];
        return new User($userData['id'], $userData['email'], $userData['role']);
    }

    // verifier la force du mot de passe
    public static function checkPasswordStrength(string $pass, int $minimumLength = 10): bool
    {
        $length = (strlen($pass) >= $minimumLength);
        $digit = preg_match("#\d#", $pass);         // Au moins un chiffre
        $special = preg_match("#\W#", $pass);       // Au moins un caractere special
        $lower = preg_match("#[a-z]#", $pass);      // Au moins une lettre minuscule
        $upper = preg_match("#[A-Z]#", $pass);      // Au moins une lettre majuscule

        return $length && $digit && $special && $lower && $upper;
    }

}
