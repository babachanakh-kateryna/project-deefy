<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action
{
    public function execute(): string
    {
        // verifie si un id de playlist est fourni
        if (!isset($_GET['id'])) {
            // si la playlist courante est deja enregistree dans la session
            if (isset($_SESSION['current_playlist'])) {
                $playlist = $_SESSION['current_playlist'];
                $renderer = new AudioListRenderer($playlist);
                return "<h3>Current Playlist: {$playlist->nom}</h3>" . $renderer->render(1);
            } else {
                return "<div>Error: No playlist selected.</div>";
            }
        }

        $playlistId = (int) $_GET['id'];
        $repo = DeefyRepository::getInstance();

        try {
            $playlist = $repo->findPlaylistById($playlistId);

            // enregistre la playlist courante dans la session
            $_SESSION['current_playlist'] = $playlist;

            // la playlist courante
            $renderer = new AudioListRenderer($playlist);
            return "<h3>Current Playlist: {$playlist->nom}</h3>" . $renderer->render(1);

        } catch (\Exception $e) {
            return "<div>Error: " . $e->getMessage() . "</div>";
        }
    }
}