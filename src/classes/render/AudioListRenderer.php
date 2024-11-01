<?php

namespace iutnc\deefy\render;


use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

/**
 * Class AudioListRenderer est une classe qui permet de rendre une liste de pistes audio
 */
class AudioListRenderer implements Renderer
{
    private AudioList $audioList;

    public function __construct(AudioList $audioList)
    {
        $this->audioList = $audioList;
    }


    public function render(int $type): string
    {
        return $this->afficher();
    }

    private function afficher(): string
    {
        $html = "<div>";
        $html .= "<h3>" . htmlspecialchars($this->audioList->nom, ENT_QUOTES, 'UTF-8') . " :</h3>";
        foreach ($this->audioList->pistes as $piste) {
            if ($piste instanceof AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } elseif ($piste instanceof PodcastTrack) {
                $renderer = new PodcastRenderer($piste);
            }
            $html .= $renderer->render(Renderer::COMPACT);
        }

        $html .= "<p><strong>Number of tracks :</strong> " . htmlspecialchars((string)$this->audioList->nombrePistes, ENT_QUOTES, 'UTF-8') . "</p>";
        $html .= "<p><strong>Total duration :</strong> " . htmlspecialchars((string)$this->audioList->dureeTotale, ENT_QUOTES, 'UTF-8') . " secondes</p>";
        $html .= "</div>";
        return $html;
    }
}