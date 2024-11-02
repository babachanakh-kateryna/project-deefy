<?php

namespace iutnc\deefy\render;


use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

/**
 * Class AudioListRenderer est une classe qui permet de rendre une liste de pistes audio
 */
class AudioListRenderer implements Renderer
{
    private AudioList $audioList;

    public function __construct(AudioList $audioList)
    {
        $this->audioList = $audioList;
    }


    public function render(int $type): string
    {
        return $this->afficher();
    }

    private function afficher(): string
    {
        $html = "<div class='playlist-container'>";
//        $html .= "<h3 class='playlist-title'>" . htmlspecialchars($this->audioList->nom, ENT_QUOTES, 'UTF-8') . "</h3>";
        $html .= "<div class='row'>";

        foreach ($this->audioList->pistes as $piste) {
            if ($piste instanceof AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } elseif ($piste instanceof PodcastTrack) {
                $renderer = new PodcastRenderer($piste);
            }

            $imagePath = $this->getRandomImagePath('images');
            $html .= <<<HTML
                <div class='card play-card playlist-content text-white p-3 mb-2' 
                data-filename='{$piste->nom_du_fichier}'
                data-title='{$piste->titre}'
                data-artist='{$piste->artiste}'
                >
                    <div class='d-flex justify-content-between align-items-center'>
                        <div class='col-1'>
                            <img src="{$imagePath}" class='card-img rounded me-3' alt='Album Cover'>
                        </div>
                        <div class='col'>
                            {$renderer->render(Renderer::LONG)}
                        </div>
                        <div class='col-0'>
                            <img src="/figma_outils/Shape.png" alt="Like" class="img-like">
                        </div>
                    </div>
                </div>
            HTML;
        }

        $html .= "</div>";

        $html .= "<div class='playlist-summary mt-4 text-white'>";
        $html .= "<p><strong>Number of tracks :</strong> " . htmlspecialchars((string)$this->audioList->nombrePistes, ENT_QUOTES, 'UTF-8') . "</p>";
        $html .= "<p><strong>Total duration :</strong> " . htmlspecialchars((string)$this->audioList->dureeTotale, ENT_QUOTES, 'UTF-8') . " seconds</p>";
        $html .= "</div>";

        return $html;
    }


    // Get a random image from the images directory
    private function getRandomImagePath(string $directory): string
    {
        // all image files (jpg, png, jpeg, gif)
        $images = glob($directory . '/*.{jpg,png,jpeg,gif}', GLOB_BRACE);

        // verify if the directory is empty
        if (empty($images)) {
            return 'images/default.jpg';
        }

        // return a random image
        return $images[array_rand($images)];
    }
}