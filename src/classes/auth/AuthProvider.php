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

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // verifier si l'utilisateur existe
        if (!$user) {
            throw new AuthnException("User not found with email ". htmlspecialchars($email));
        }

//        echo "User hashed password from DB: {$user['passwd']}";
//        echo "Entered password: $passwd2check";

        // verifier si le mot de passe est correct
        if (!password_verify($passwd2check, $user['passwd'])) {
            throw new AuthnException("Password verification failed for email ". htmlspecialchars($email));
        }
        //echo $user['id'];

        if (!isset($user['role'])) {
            throw new AuthnException("Authentication error: role is undefined for this user.");
        }

        // stocker l'utilisateur dans la session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }

    // methode pour s'inscrire
    public static function register(string $email, string $pass): void
    {
        $pdo = DeefyRepository::getInstance()->getPDO();

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // verifier si l'email est corect
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthnException("Auth error: invalid user email");
        }

        // verifier si le mot de passe est minimum 10 caracteres
        if (strlen($pass) < 10) {
            throw new AuthnException("Auth error: password must be at least 10 characters long");
        }

        // verifier la force du mot de passe
        if (!self::checkPasswordStrength($pass)) {
            throw new AuthnException("Auth error: password is too weak");
        }

        // verifier si l'utilisateur existe deja
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            throw new AuthnException("Auth error: an account with this email already exists");
        }

        // hasher le mot de passe
        $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);

        // ajouter l'utilisateur a la base de donnees avec le role 1

        $stmt = $pdo->prepare("INSERT INTO user (email, passwd, role) VALUES (:email, :passwd, :role)");
        $stmt->execute(['email' => $email, 'passwd' => $hash, 'role' => 1]);
    }

    public static function getSignedInUser(): array
    {
        if (!isset($_SESSION['user'])) {
            throw new AuthnException("Auth error: not signed in");
        }

        return $_SESSION['user'];
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
