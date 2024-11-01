<?php

namespace iutnc\deefy\audio\lists;

/**
 * Class Album est une classe qui represente un album
 */
class Album extends AudioList
{
    private string $artiste;
    private string $dateSortie;

    public function __construct(string $nom, array $pistes)
    {
        parent::__construct($nom, $pistes);
    }

    public function setArtiste(string $artiste): void
    {
        $this->artiste = $artiste;
    }

    public function setDateSortie(string $dateSortie): void
    {
        $this->dateSortie = $dateSortie;
    }
}