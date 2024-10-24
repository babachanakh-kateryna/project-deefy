<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\audio\tracks as tracks;

class PodcastRenderer extends AudioTrackRenderer
{
    private tracks\PodcastTrack $podcastTrack;

    public function __construct(tracks\PodcastTrack $a)
    {
        $this->podcastTrack = $a;
    }

    protected function renderCompact(): string
    {
        return "
        <div classes='track-compact'>
            <h3>{$this->podcastTrack->titre} - {$this->podcastTrack->auteur}</h3>
            <audio controls>
                <source src='{$this->podcastTrack->nom_du_fichier}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio>
        </div>
        ";
    }

    protected function renderLong(): string
    {
        return "
        <div classes='track-long'>
            <h3>{$this->podcastTrack->titre} - {$this->podcastTrack->auteur}</h3>
            <p><strong>Date :</strong> {$this->podcastTrack->date}</p>
            <p><strong>Genre :</strong> {$this->podcastTrack->genre}</p>
            <audio controls>
                <source src='{$this->podcastTrack->nom_du_fichier}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio>
        </div>
        ";
    }
}