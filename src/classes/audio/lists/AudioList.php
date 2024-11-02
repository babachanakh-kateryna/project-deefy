<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

/**
 * Class AudioList is a class that represents a list of audio tracks
 */
class AudioList
{
    protected string $nom;
    protected int $nombrePistes = 0;
    protected array $pistes = [];
    protected int $dureeTotale = 0;

    public function __construct(string $nom, array $pistes = [])
    {
        $this->nom = $nom;
        $this->nombrePistes = count($pistes);
        $this->pistes = $pistes;
        $this->dureeTotale = $this->calculerDureeTotale();
    }

    private function calculerDureeTotale(): int
    {
        $duree = 0;
        foreach ($this->pistes as $piste) {
            $duree += $piste->duree;
        }
        return $duree;
    }

    public function __get($property)
    {
        if(property_exists($this, $property)) {
            return $this->$property;
        } else {
            throw new InvalidPropertyNameException($property);
        }
    }
}