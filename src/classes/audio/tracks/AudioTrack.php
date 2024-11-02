<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

/**
 * Class AudioTrack est une classe qui represente une piste audio
 */
abstract class AudioTrack
{
    private string $titre;
    private int $duree;
    private string $nom_du_fichier;

    public function __construct(string $titre, string $chemin_fichier, $duree)
    {
        $this->titre = $titre;
        $this->nom_du_fichier = $chemin_fichier;
        $this->setDuree($duree);
    }

    public function __toString(): string
    {
        return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            throw new InvalidPropertyNameException($property);
        }
    }

    public function setDuree($d): void
    {
        if($d>0){
            $this->duree = $d;
        } else {
            throw new InvalidPropertyValueException("Duration must be greater than 0");
        }
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    // Format the duration in minutes and seconds
    public function formatDuration(): string
    {
        $minutes = floor($this->duree / 60);
        $seconds = str_pad($this->duree % 60, 2, '0', STR_PAD_LEFT);
        return "{$minutes}:{$seconds}";
    }

}