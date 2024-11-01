<?php

namespace iutnc\deefy\action;

/**
 * Class SignOutAction is a class that represents the sign out action
 */
class SignOutAction extends Action
{
    public function execute(): string
    {
        session_destroy();
        return "<div>You have been signed out.</div><a href='?action=default'>Go to Home</a>";
    }
}