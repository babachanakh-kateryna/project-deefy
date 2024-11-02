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
        $html = "<div class='alert alert-success text-center mt-3' role='alert'>You have been signed out.</div>";
        $html .= "<script>
                setTimeout(function() {
                    window.location.href = '?action=default';
                }, 2000); 
            </script>";

        return $html;
    }
}