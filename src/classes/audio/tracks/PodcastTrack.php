<?php

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack
{
    protected string $auteur;
    protected string $date;
    protected string $genre;

    public function __construct(string $titre, string $chemin, int $duree = 0, string $auteur = "Inconnu", string $date = "Inconnue", string $genre = "Inconnu")
    {
        parent::__construct($titre, $chemin, $duree);
        $this->auteur = $auteur;
        $this->date = $date;
        $this->genre = $genre;
    }

    // Getters
    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    // Setters
    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }
}