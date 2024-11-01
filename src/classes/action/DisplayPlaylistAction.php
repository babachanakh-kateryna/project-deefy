<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action
{
    public function execute(): string
    {
        $repo = DeefyRepository::getInstance();

        try {
            $playlist = $repo->findPlaylistById(2);

            $renderer = new AudioListRenderer($playlist);
            return $renderer->render(1);

        } catch (\Exception $e) {
            return "<div>Error: " . $e->getMessage() . "</div>";
        }
    }
}