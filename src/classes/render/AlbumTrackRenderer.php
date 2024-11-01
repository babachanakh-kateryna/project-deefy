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
        <div>
            <h3>" . htmlspecialchars($this->albumTrack->titre, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($this->albumTrack->artiste, ENT_QUOTES, 'UTF-8') . "</h3>
            <audio controls>
                <source src='" . htmlspecialchars($this->albumTrack->nom_du_fichier, ENT_QUOTES, 'UTF-8') . "' type='audio/mpeg'>
                Your browser doesn't support the audio tag.
            </audio> 
        </div>
        ";
    }

    protected function renderLong(): string
    {
        return "
        <div>
            <h3>" . htmlspecialchars($this->albumTrack->titre, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($this->albumTrack->artiste, ENT_QUOTES, 'UTF-8') . "</h3>
            <p><strong>Album :</strong> " . htmlspecialchars($this->albumTrack->album, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Year :</strong> " . htmlspecialchars((string)$this->albumTrack->annee, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Track number :</strong> " . htmlspecialchars((string)$this->albumTrack->numero_piste, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Genre :</strong> " . htmlspecialchars($this->albumTrack->genre, ENT_QUOTES, 'UTF-8') . "</p>
            <p><strong>Duration :</strong> " . htmlspecialchars((string)$this->albumTrack->duree, ENT_QUOTES, 'UTF-8') . " secondes</p>
            <audio controls>
                <source src='" . htmlspecialchars($this->albumTrack->nom_du_fichier, ENT_QUOTES, 'UTF-8') . "' type='audio/mpeg'>
                Your browser doesn't support the audio tag.
            </audio> 
        </div>
        ";
    }
}