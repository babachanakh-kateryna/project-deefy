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
        return "<div class='alert alert-danger'>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<form method="post" action="?action=add-playlist" class="p-4 bg-dark text-light rounded">
    <div class="mb-3">
        <label for="name" class="form-label">Playlist Name:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Create the Playlist</button>
</form>
HTML;
    }


    // cree une playlist et l'associe a l'utilisateur courant
    private function createPlaylist(): string
    {
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            return "<div class='alert alert-danger text-center mt-3'>Error: Playlist name cannot be empty.</div>";
        }
        $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);

        $repo = DeefyRepository::getInstance();
        $playlist = new Playlist($name);
        $playlist = $repo->saveEmptyPlaylist($playlist);

        // si l'utilisateur est authentifie, lie la playlist a l'utilisateur
        $userId = $_SESSION['user']['id'];
        if ($userId) {
            $repo->linkUserToPlaylist($userId, $playlist->id);
        } else {
            return "<div class='alert alert-danger text-center mt-3'>Error: To add a playlist, you must first <a href='?action=signin' class='alert-link'>log in</a> or <a href='?action=add-user' class='alert-link'>register</a>.</div>";
        }


        // enregistre la playlist courante dans la session
        $_SESSION['current_playlist'] = $playlist;

        $safePlaylistName = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');
        $html = "<div class='alert alert-success text-center mt-3'>Playlist '{$safePlaylistName}' created and set as current playlist.</div>";
        $html .= "<div class='text-center mt-3'><a href='?action=add-track' class='btn btn-primary'>Add a track to this playlist</a></div>";
        return $html;
    }
}