<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks as tracks;

/**
 * Class PodcastRenderer est une classe qui permet de rendre une piste de podcast
 */
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
            <h3>" . htmlspecialchars($this->podcastTrack->titre, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($this->podcastTrack->auteur, ENT_QUOTES, 'UTF-8') . "</h3>
            <audio controls>
                <source src='" . htmlspecialchars($this->podcastTrack->nom_du_fichier, ENT_QUOTES, 'UTF-8') . "' type='audio/mpeg'>
                Your browser doesn't support the audio tag.
            </audio>
        </div>
        ";
    }

    protected function renderLong(): string
    {
        return "
        <div classes='track-long'>
            <h3>" . htmlspecialchars($this->podcastTrack->titre, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($this->podcastTrack->auteur, ENT_QUOTES, 'UTF-8') . "</h3>
            <p><strong>Date :</strong> " . htmlspecialchars($this->podcastTrack->date, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Genre :</strong> " . htmlspecialchars($this->podcastTrack->genre, ENT_QUOTES, 'UTF-8') . "</p>
            <audio controls>
                <source src='" . htmlspecialchars($this->podcastTrack->nom_du_fichier, ENT_QUOTES, 'UTF-8') . "' type='audio/mpeg'>
                Your browser doesn't support the audio tag.
            </audio>
        </div>
        ";
    }
}