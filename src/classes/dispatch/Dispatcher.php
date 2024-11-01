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
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        echo <<<HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deefy</title>
</head>
<body>
   <h1>Deefy Music App</h1>
   <ul>
         <li><a href="?action=default">Home</a></li>
         <li><a href="?action=signin">Sign In</a></li>
         <li><a href="?action=add-user">Register</a></li>
         <li><a href="?action=display-user-playlists">Display My Playlists</a></li>
         <li><a href="?action=display-playlist">Display Current Playlist</a></li>
         <li><a href="?action=add-playlist">Add a Playlist</a></li>
         <li><a href="?action=add-track">Add a Track to the Playlist</a></li>
    </ul>
    $html
</body>
</html>
HEAD;
    }
}
