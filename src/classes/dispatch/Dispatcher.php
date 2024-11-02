<?php
declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action as act;

/**
 * Class Dispatcher
 */
class Dispatcher
{
    private ?string $action = null;

    function __construct()
    {
        $this->action = isset($_GET['action']) ? htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8') : 'default';
    }

    public function run(): void
    {
        $html = '';
        switch ($this->action) {
            case 'default':
                $action = new act\DefaultAction();
                $html = $action->execute();
                break;
            case 'display-playlist':
                $action = new act\DisplayPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-playlist':
                $action = new act\AddPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-track':
                $action = new act\AddTrackAction();
                $html = $action->execute();
                break;
            case 'add-user':
                $action = new act\AddUserAction();
                $html = $action->execute();
                break;
            case 'signin':
                $action = new act\SignInAction();
                $html = $action->execute();
                break;
            case 'display-user-playlists':
                $action = new act\DisplayUserPlaylistsAction();
                $html = $action->execute();
                break;
            case 'signout':
                $action = new act\SignOutAction();
                $html = $action->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        // verivier si l'utilisateur est connecte
        $isSignedIn = isset($_SESSION['user']);
        $username = $isSignedIn ? htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') : '';

        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deefy Music App<</title>
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <div class="ellipse-first"></div>
    <div class="ellipse-second"></div> 
    <div class="ellipse-third"></div> 

    <div class="sidebar">
        <h2>Deefy</h2>
HTML;

        // menu pour les utilisateurs non connectes
        if (!$isSignedIn) {
            echo <<<HTML
            <a href="?action=default">Home</a>
            <a href="?action=signin">Sign In</a>
            <a href="?action=add-user">Register</a></li>
HTML;
        } else {
            // menu pour les utilisateurs connectes
            echo <<<HTML
            <a href="?action=default">Home</a></li>
            <a href="?action=display-user-playlists">My Playlists</a>
            <a href="?action=display-playlist">Current Playlist</a>
            <a href="?action=add-playlist">Add a Playlist</a>
HTML;

            // afficher le nom de l'utilisateur et le lien de deconnexion
            echo <<<HTML
<!--            <li class="welcome-message">Hello, $username!</li>-->
            <a href="?action=signout">Sign Out</a>
HTML;
        }

        echo <<<HTML
    </div>
    <div class="content">
        $html
    </div>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
    }

}
