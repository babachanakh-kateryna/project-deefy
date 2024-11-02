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
        <div class='row'>
            <div class='col-6'>
                <h2 class='card-title'>" . htmlspecialchars($this->podcastTrack->titre, ENT_QUOTES, 'UTF-8') . "</h2>
                <h6 class='card-text'>" . htmlspecialchars($this->podcastTrack->auteur, ENT_QUOTES, 'UTF-8')  . "</h6>
            </div>
            <div class='col-4'>
                <h2 class='card-title'>" . htmlspecialchars($this->podcastTrack->genre, ENT_QUOTES, 'UTF-8') . "</h2>
                <h6 class='card-text'>" . htmlspecialchars($this->podcastTrack->date, ENT_QUOTES, 'UTF-8')  . "</h6>
            </div>
            <div class='col d-flex align-items-center'>
                <h2 class='card-title'>" . htmlspecialchars($this->podcastTrack->formatDuration(), ENT_QUOTES, 'UTF-8') . "</h2>
            </div>
        </div>
        ";
    }
}