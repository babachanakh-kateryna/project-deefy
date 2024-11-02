<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

/**
 * Class DefaultAction est une classe qui represente l'action par defaut
 */
class DefaultAction extends Action
{
    public function execute(): string
    {
        $repo = DeefyRepository::getInstance();

        // Get random top hits
        $topHits = $repo->getRandomTracksByType('A', 3); // Type 'A' for Album Tracks

        // Get random top podcasts
        $topPodcasts = $repo->getRandomTracksByType('P', 3); // Type 'P' for Podcasts

        $username = isset($_SESSION['user']) ? (is_array($_SESSION['user']) ? $_SESSION['user']['email'] : $_SESSION['user']) : 'Guest';

        $html = "<div class='container my-3'>";
        $html .= "<h3 class='mb-4'>Welcome, {$username}!</h3>";

        $html .= "<section class='section-header'><h2 class='mb-3'>Top Hits Today</h2>
        <img src='figma_outils/Group8.png' alt='Next Icon' class='next-icon'>
         </section>";

        $html .= "<div class='track-list row'>";

        foreach ($topHits as $track) {
            $html .= $this->renderTrackCard($track);
        }
        $html .= "</div>";

        $html .= "<section class='section-header'>
<h2 class='my-4'>Top Podcasts Today</h2>
<img src='figma_outils/Group8.png' alt='Next Icon' class='next-icon'>
         </section>";

        $html .= "<div class='track-list row'>";

        foreach ($topPodcasts as $track) {
            $html .= $this->renderTrackCard($track);
        }

        $html .= "</div></section>";

        $html .= "</div>";

        return $html;
    }

    private function renderTrackCard(array $track): string
    {
        $title = htmlspecialchars($track['titre'], ENT_QUOTES, 'UTF-8');
        $artist = htmlspecialchars($track['artiste_album'] ?? $track['auteur_podcast'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
        $genre = htmlspecialchars($track['genre'], ENT_QUOTES, 'UTF-8');
        $filename = htmlspecialchars($track['filename'], ENT_QUOTES, 'UTF-8');

        $imagePath = $this->getRandomImagePath('images');

        return <<<HTML
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card text-white h-100 d-flex justify-content-center">
                <div class="d-flex align-items-center p-3">
                    <img src="$imagePath" class="card-img rounded" alt="Album Cover">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1 mt-2">{$title}</h5>
                        <p class="card-text mb-1">{$artist} - {$genre}</p>
                        <button class="play-button d-flex justify-content-end">
                        <img src="/figma_outils/Group3.png" alt="Play">
                    </button>
                    </div>
                </div>
            </div>
        </div>
HTML;
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