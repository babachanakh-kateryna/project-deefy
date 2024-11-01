<?php
declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action as act;

class Dispatcher
{
    private ?string $action = null;

    function __construct()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : 'default';
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
        $username = $isSignedIn ? $_SESSION['user'] : '';

        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deefy</title>
    <style>
        /* default */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .header .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .header .nav-links li {
            display: inline;
        }
        .header .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .header .nav-links a:hover {
            text-decoration: underline;
        }
        .welcome-message {
            font-size: 16px;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Deefy Music App</h1>
        <ul class="nav-links">
HTML;

        // menu pour les utilisateurs non connectes
        if (!$isSignedIn) {
            echo <<<HTML
            <li><a href="?action=default">Home</a></li>
            <li><a href="?action=signin">Sign In</a></li>
            <li><a href="?action=add-user">Register</a></li>
            <li><a href="?action=display-user-playlists">Display My Playlists</a></li>
            <li><a href="?action=display-playlist">Display Current Playlist</a></li>
            <li><a href="?action=add-playlist">Add a Playlist</a></li>
HTML;
        } else {
            // menu pour les utilisateurs connectes
            echo <<<HTML
            <li><a href="?action=default">Home</a></li>
            <li><a href="?action=display-user-playlists">Display My Playlists</a></li>
            <li><a href="?action=display-playlist">Display Current Playlist</a></li>
            <li><a href="?action=add-playlist">Add a Playlist</a></li>
HTML;

            // afficher le nom de l'utilisateur et le lien de deconnexion
            echo <<<HTML
            <li class="welcome-message">Hello, $username!</li>
            <li><a href="?action=signout">Sign Out</a></li>
HTML;
        }

        echo <<<HTML
        </ul>
    </div>
    <div class="content">
        $html
    </div>
</body>
</html>
HTML;
    }

}
