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

        return "<div class='alert alert-danger'>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<div class="full-screen-container d-flex justify-content-center align-items-center">
    <div class="card-sign-in p-4 shadow-lg rounded" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4">Register</h3>
        <form method="post" action="?action=add-user">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="passwd" class="form-label">Password:</label>
                <input type="password" id="passwd" name="passwd" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="passwd_confirm" class="form-label">Confirm Password:</label>
                <input type="password" id="passwd_confirm" name="passwd_confirm" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</div>
HTML;
    }

    private function processForm(): string
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $passwd = $_POST['passwd'];
        $passwd_confirm = $_POST['passwd_confirm'];

        // verifier que les mots de passe correspondent
        if ($passwd !== $passwd_confirm) {
            return "<div class='alert alert-danger text-center mt-3'>Error: Passwords do not match.</div>";
        }

        try {
            AuthProvider::register($email, $passwd);
            return <<<HTML
<div class="alert alert-success text-center mt-3" role="alert">
    Account successfully created for {$email}. You can now <a href='?action=signin' class='alert-link'>sign in</a>.
</div>
HTML;
        } catch (AuthnException $e) {
            return "<div class='alert alert-danger text-center mt-3'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}
