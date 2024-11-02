<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks as tracks;

/**
 * Class AlbumTrackRenderer est une classe qui permet de rendre une piste d'un album
 */
class AlbumTrackRenderer extends AudioTrackRenderer
{
    private tracks\AlbumTrack $albumTrack;

    public function __construct(tracks\AlbumTrack $a)
    {
        $this->albumTrack = $a;
    }

    protected function renderCompact(): string
    {
        return "
            <div class='col'>
                <h2 class='card-title'>" . htmlspecialchars($this->albumTrack->titre, ENT_QUOTES, 'UTF-8') . "</h2>
                <h6 class='card-text'>" . htmlspecialchars($this->albumTrack->artiste, ENT_QUOTES, 'UTF-8')  . "</h6>
            </div>
        
        ";
    }

    protected function renderLong(): string
    {
        return "
        <div class='row'>
            <div class='col-6'>
                <h2 class='card-title'>" . htmlspecialchars($this->albumTrack->titre, ENT_QUOTES, 'UTF-8') . "</h2>
                <h6 class='card-text'>" . htmlspecialchars($this->albumTrack->artiste, ENT_QUOTES, 'UTF-8')  . "</h6>
            </div>
            <div class='col-4'>
                <h2 class='card-title'>" . htmlspecialchars($this->albumTrack->album, ENT_QUOTES, 'UTF-8') . "</h2>
                <h6 class='card-text'>" . htmlspecialchars($this->albumTrack->annee, ENT_QUOTES, 'UTF-8')  . "</h6>
            </div>
            <div class='col d-flex align-items-center'>
                <h2 class='card-title'>" . htmlspecialchars($this->albumTrack->formatDuration(), ENT_QUOTES, 'UTF-8')  . "</h2>
            </div>
        </div>
        
        ";
    }
}