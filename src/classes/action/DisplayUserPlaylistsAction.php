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
        if (!isset($_SESSION['user_id'])) {
            return "<div>Error: Not authenticated</div>";
        }

        $userId = $_SESSION['user_id'];
        $repo = DeefyRepository::getInstance();

        try {
            $playlists = $repo->findPlaylistsByUserId($userId);
            if (empty($playlists)) {
                return "<div>You have no playlists</div>";
            }

            $html = "<h3>Your Playlists:</h3><ul>";
            foreach ($playlists as $playlist) {
                $safePlaylistId = htmlspecialchars((string)$playlist->id, ENT_QUOTES, 'UTF-8');
                $safePlaylistName = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');
                $html .= "<li><a href=\"?action=display-playlist&id={$safePlaylistId}\">{$safePlaylistName}</a></li>";            }
            $html .= "</ul>";

            return $html;

        } catch (\Exception $e) {
            return "<div>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
        }
    }
}