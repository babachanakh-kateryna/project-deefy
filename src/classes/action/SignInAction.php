<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthProvider;
use iutnc\deefy\exception\AuthnException;

class SignInAction extends Action
{
    public function execute(): string
    {
        $methode = $_SERVER['REQUEST_METHOD'];

        if ($methode === 'GET') {
            return $this->displayForm();
        } elseif ($methode === 'POST') {
            return $this->processSignIn();
        }
        return "<div>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<form method="post" action="?action=signin">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="passwd">Password:</label>
    <input type="password" id="passwd" name="passwd" required>
    
    <button type="submit">Sign In</button>
</form>
HTML;
    }

    private function processSignIn(): string
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $passwd = $_POST['passwd'];

        try {
            AuthProvider::signin($email, $passwd);
            return "<div>Successfully signed in as $email</div>";
        } catch (AuthnException $e) {
            return "<div>Error: " . $e->getMessage() . "</div>";
        }
    }
}
