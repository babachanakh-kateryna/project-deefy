<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthProvider;
use iutnc\deefy\exception\AuthnException;

/**
 * Class SignInAction est une classe qui represente l'action de sign in
 */
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
<div class="full-screen-container d-flex justify-content-center align-items-center">
    <div class="card-sign-in p-4">
        <h3 class="text-center mb-4">Sign In</h3>
        <form method="post" action="?action=signin">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="passwd" class="form-label">Password:</label>
                <input type="password" id="passwd" name="passwd" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>
</div>
HTML;
    }

    private function processSignIn(): string
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $passwd = $_POST['passwd'];

        try {
            AuthProvider::signin($email, $passwd);
            return <<<HTML
<div class="alert alert-success text-center d-flex justify-content-center" role="alert">
    Successfully signed in as {$email}. Redirecting to Home...
</div>
<script>
    setTimeout(function() {
        window.location.href = '?action=default';
    }, 3000); 
</script>
HTML;

        } catch (AuthnException $e) {
            return <<<HTML
<div class="alert alert-danger text-center mt-3" role="alert">
    Error: {$e->getMessage()}
    <div class="text-center mt-2">
        <a href="?action=signin" class="btn btn-link">Try Again</a>
    </div>
</div>

HTML;
        }
    }
}
