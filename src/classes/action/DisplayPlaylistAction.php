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
                    return "<div>Access denied: " . $e->getMessage() . "</div>";
                }

                $renderer = new AudioListRenderer($playlist);
                return "<h3>Current Playlist: {$playlist->nom}</h3>"
                    . $renderer->render(1)
                    . '<br><a href="?action=add-track">Add a new track to this playlist</a>';
            } else {
                return "<div>Error: No playlist selected.</div>";
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
            return "<h3>Current Playlist: {$playlist->nom}</h3>"
                . $renderer->render(1)
                . '<br><a href="?action=add-track">Add a new track to this playlist</a>';

        } catch (AuthnException $e) {
            return "<div>Access denied: " . $e->getMessage() . "</div>";
        } catch (\Exception $e) {
            return "<div>Error: " . $e->getMessage() . "</div>";
        }
    }
}