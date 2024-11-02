<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

/**
 *  Class DisplayPlaylistAction est une classe qui represente current playlist
 */
class DisplayPlaylistAction extends Action
{
    public function execute(): string
    {
        // verifie si un id de playlist est fourni
        if (!isset($_GET['id'])) {
            // si la playlist courante est deja enregistree dans la session
            if (isset($_SESSION['current_playlist'])) {
                $playlist = $_SESSION['current_playlist'];

                // verifie si l'utilisateur peut view playlist
                try {
                    Authz::checkPlaylistOwner($playlist->id);
                } catch (AuthnException $e) {
                    return "<div class='alert alert-danger'>Access denied: " . htmlspecialchars($e->getMessage()) . "</div>";
                }

                $renderer = new AudioListRenderer($playlist);
                $playlistName = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');

                return <<<HTML
                <div class="container mt-5">
                    <h2 class="text-white">Current Playlist: <strong>{$playlistName}</strong></h2>
                    <div class="playlist-content my-4">
                        {$renderer->render(1)}
                    </div>
                    <div class="mt-4">
                        <a href="?action=add-track" class="btn btn-primary">Add a new track to this playlist</a>
                    </div>
                </div>
HTML;
            } else {
                return "<div class='alert alert-warning'>Error: No playlist selected.</div>";
            }
        }

        $playlistId = (int) $_GET['id'];
        $repo = DeefyRepository::getInstance();

        try {
            // verifie si l'utilisateur peut view playlist
            Authz::checkPlaylistOwner($playlistId);

            $playlist = $repo->findPlaylistById($playlistId);

            // enregistre la playlist courante dans la session
            $_SESSION['current_playlist'] = $playlist;

            // la playlist courante
            $renderer = new AudioListRenderer($playlist);
            $playlistName = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');

            return <<<HTML
            <div class="container mt-5">
                <h2 class="text-white"><strong>{$playlistName}</strong></h2>
                <div class="playlist-content my-4">
                    {$renderer->render(1)}
                </div>
                <div class="mt-4">
                    <a href="?action=add-track" class="btn btn-primary">Add a new track to this playlist</a>
                </div>
            </div>
HTML;
        } catch (AuthnException $e) {
            return "<div class='alert alert-danger'>Access denied: " . htmlspecialchars($e->getMessage()) . "</div>";
        } catch (\Exception $e) {
            return "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}