<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

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
                $html .= "<li><a href=\"?action=display-playlist&id={$playlist->id}\">{$playlist->nom}</a></li>";
            }
            $html .= "</ul>";

            return $html;

        } catch (\Exception $e) {
            return "<div>Error: " . $e->getMessage() . "</div>";
        }
    }
}