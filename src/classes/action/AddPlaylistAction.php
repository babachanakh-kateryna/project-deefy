<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action
{

    public function execute(): string
    {
        $methode = $_SERVER['REQUEST_METHOD'];

        if ($methode === 'GET') {
            return $this->displayForm();
        } elseif ($methode === 'POST') {
            return $this->createPlaylist();
        }
        return "<div>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<form method="post" action="?action=add-playlist">
    <label for="name">Playlist Name :</label>
    <input type="text" id="name" name="name" required>
    <button type="submit">Create the Playlist</button>
</form>
HTML;
    }

    private function createPlaylist(): string
    {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);

        $repo = DeefyRepository::getInstance();
        $playlist = new Playlist($name);
        $playlist = $repo->saveEmptyPlaylist($playlist);

        // enregistre la playlist courante dans la session
        $_SESSION['current_playlist'] = $playlist;

        $html = "<div>Playlist '{$playlist->nom}' created and set as current playlist.</div>";
        $html .= '<a href="?action=add-track">Add a track to this playlist</a>';
        return $html;
    }
}