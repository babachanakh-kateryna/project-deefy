<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render as render;
use iutnc\deefy\audio\lists\Playlist;

class DisplayPlaylistAction extends Action
{

    public function execute(): string
    {
        if(isset($_SESSION['playlist']) && $_SESSION['playlist'] instanceof Playlist) {
            $playlist = $_SESSION['playlist'];

            $renderer = new render\AudioListRenderer($playlist);
            return $renderer->render(1);
        }

        return "<div>Erreur : aucune playlist n'a été trouvée.</div>";
    }
}