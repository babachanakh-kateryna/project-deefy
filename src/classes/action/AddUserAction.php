<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthProvider;
use iutnc\deefy\exception\AuthnException;

/**
 * Class AddUserAction est une classe qui represente l'action de registration
 */
class AddUserAction extends Action
{
    public function execute(): string
    {
        $methode = $_SERVER['REQUEST_METHOD'];

        if ($methode === 'GET') {
            return $this->displayForm();
        } elseif ($methode === 'POST') {
            return $this->processForm();
        }

        return "<div>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<form method="post" action="?action=add-user">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="passwd">Password:</label>
    <input type="password" id="passwd" name="passwd" required>

    <label for="passwd_confirm">Confirm Password:</label>
    <input type="password" id="passwd_confirm" name="passwd_confirm" required>
    
    <button type="submit">Register</button>
</form>
HTML;
    }

    private function processForm(): string
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $passwd = $_POST['passwd'];
        $passwd_confirm = $_POST['passwd_confirm'];

        // verifier que les mots de passe correspondent
        if ($passwd !== $passwd_confirm) {
            return "<div>Error: Passwords do not match.</div>";
        }

        try {
            AuthProvider::register($email, $passwd);
            return "<div>Account successfully created for $email. You can now <a href='?action=signin'>sign in</a>.</div>";
        } catch (AuthnException $e) {
            return "<div>Error: " . $e->getMessage() . "</div>";
        }
    }
}
