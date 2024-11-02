<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

/**
 * Class DisplayUserPlaylistsAction est une classe qui represente les playlists de l'utilisateur
 */
class DisplayUserPlaylistsAction extends Action
{
    public function execute(): string
    {
        if (!isset($_SESSION['user'])) {
            return "<div class='alert alert-danger'>Error: Not authenticated</div>";
        }

        $userId = $_SESSION['user']['id'];
        $repo = DeefyRepository::getInstance();

        try {
            $playlists = $repo->findPlaylistsByUserId($userId);

            $html = "<div class='container my-3'>";

            if (empty($playlists)) {
                return "<div class='alert alert-info text-center'>You have no playlists.</div></div>";
            }

            $html .= "<h2 class='text-white text-center mb-4'>Your Playlists</h2>";

            $html .= "<div class='row'>";
            foreach ($playlists as $playlist) {
                $safePlaylistId = htmlspecialchars((string)$playlist->id, ENT_QUOTES, 'UTF-8');
                $safePlaylistName = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');

                $imagePath = $this->getRandomImagePath('images');

                $html .= <<<HTML
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card text-white bg-dark h-100">
                        <img src="$imagePath" class="card-img-top" alt="Playlist Cover">
                        <div class="card-body">
                            <h5 class="card-title">$safePlaylistName</h5>
                            <a href="?action=display-playlist&id={$safePlaylistId}" class="btn btn-primary w-100">View Playlist</a>
                        </div>
                    </div>
                </div>
HTML;
            }
            $html .= "</div></div>";

            return $html;

        } catch (\Exception $e) {
            return "<div class='alert alert-danger text-center my-4'>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
        }
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