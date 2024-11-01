<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Class AddPlaylist est une classe qui represente l'action d'ajout d'une playlist
 */
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

    // cree une playlist et l'associe a l'utilisateur courant
    private function createPlaylist(): string
    {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);

        $repo = DeefyRepository::getInstance();
        $playlist = new Playlist($name);
        $playlist = $repo->saveEmptyPlaylist($playlist);

        // si l'utilisateur est authentifie, lie la playlist a l'utilisateur
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $repo->linkUserToPlaylist($userId, $playlist->id);
        } else {
            return "<div>Error: To add a playlist you must first <a href='?action=signin'>log in</a> or <a href='?action=add-user'>register</a>.</div>";
        }


        // enregistre la playlist courante dans la session
        $_SESSION['current_playlist'] = $playlist;

        $safePlaylistName = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');
        $html = "<div>Playlist '{$safePlaylistName}' created and set as current playlist.</div>";
        $html .= '<a href="?action=add-track">Add a track to this playlist</a>';
        return $html;
    }
}