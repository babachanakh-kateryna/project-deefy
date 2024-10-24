<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks as tracks;

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
            <h3>{$this->albumTrack->titre} - {$this->albumTrack->artiste}</h3>
            <audio controls>
                <source src='{$this->albumTrack->nom_du_fichier}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio> 
        </div>
        ";
    }

    protected function renderLong(): string
    {
        return "
        <div>
            <h3>{$this->albumTrack->titre} - {$this->albumTrack->artiste}</h3>
            <p><strong>Album :</strong> {$this->albumTrack->album}</p>
            <p><strong>Année :</strong> {$this->albumTrack->annee}</p>
            <p><strong>Numéro de piste :</strong> {$this->albumTrack->numero_piste}</p>
            <p><strong>Genre :</strong> {$this->albumTrack->genre}</p>
            <p><strong>Durée :</strong> {$this->albumTrack->duree} secondes</p>
            <audio controls>
                <source src='{$this->albumTrack->nom_du_fichier}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio> 
        </div>
        ";
    }
}