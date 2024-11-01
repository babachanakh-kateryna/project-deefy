<?php

namespace iutnc\deefy\audio\tracks;

/**
 * Class AlbumTrack est une classe qui represente une piste d'un album
 */
class AlbumTrack extends AudioTrack
{
    protected string $artiste;
    protected string $album;
    protected int $annee;
    protected int $numero_piste;
    protected string $genre;

    public function __construct($titre, $chemin_fichier, $album, $numero_piste, $duree = 0, $artiste = "Unknown Artist", $annee = 0, $genre = "Unknown Genre")
    {
        parent::__construct($titre, $chemin_fichier, $duree);
        $this->album = $album;
        $this->numero_piste = $numero_piste;
        $this->artiste = $artiste;
        $this->annee = $annee;
        $this->genre = $genre;
    }

    public function setArtiste(string $artiste): void
    {
        $this->artiste = $artiste;
    }

    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }
}
