<?php

namespace iutnc\deefy\render;


use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

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
        $html .= "<h3>{$this->audioList->nom} :</h3>";
        foreach ($this->audioList->pistes as $piste) {
            if ($piste instanceof AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } elseif ($piste instanceof PodcastTrack) {
                $renderer = new PodcastRenderer($piste);
            }
            $html .= $renderer->render(Renderer::COMPACT);
        }

        $html .= "<p><strong>Number of tracks :</strong> {$this->audioList->nombrePistes}</p>";
        $html .= "<p><strong>Total duration :</strong> {$this->audioList->dureeTotale} secondes</p>";
        $html .= "</div>";
        return $html;
    }
}