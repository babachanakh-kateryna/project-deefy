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
            <div class='col'>
                <h5 class='card-title'>" . htmlspecialchars($this->podcastTrack->titre, ENT_QUOTES, 'UTF-8') . "</h5>
                <h6 class='card-text'>" . htmlspecialchars($this->podcastTrack->auteur, ENT_QUOTES, 'UTF-8')   . "</h6>
            </div>
        ";
    }

    protected function renderLong(): string
    {
        return "
        <div class='track-card mb-3'>
            <div class=''>
                <h5 class=''>" . htmlspecialchars($this->podcastTrack->titre, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($this->podcastTrack->auteur, ENT_QUOTES, 'UTF-8') . "</h5>
                <p class='card-text'><strong>Date:</strong> " . htmlspecialchars($this->podcastTrack->date, ENT_QUOTES, 'UTF-8') . "</p>
                <p class='card-text'><strong>Genre:</strong> " . htmlspecialchars($this->podcastTrack->genre, ENT_QUOTES, 'UTF-8') . "</p>
                <audio controls class='w-100'>
                    <source src='" . htmlspecialchars($this->podcastTrack->nom_du_fichier, ENT_QUOTES, 'UTF-8') . "' type='audio/mpeg'>
                    Your browser doesn't support the audio tag.
                </audio>
            </div>
        </div>
        ";
    }
}