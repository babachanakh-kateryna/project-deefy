<?php

namespace iutnc\deefy\action;

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
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="age">Age:</label>
    <input type="number" id="age" name="age" required min="0" max="150">
    
    <button type="submit">Register</button>
</form>
HTML;
    }

    private function processForm(): string
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_NUMBER_INT);

        return "<div>Name: $name, Email: $email, Age: $age years</div>";
    }
}
