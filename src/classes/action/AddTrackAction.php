<?php

namespace iutnc\deefy\action;

use getID3;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\repository\DeefyRepository;

/**
 * Class AddTrackAction represente une action pour ajouter une piste a une playlist
 */
class AddTrackAction extends Action
{
    public function execute(): string
    {
        $methode = $_SERVER['REQUEST_METHOD'];

        if ($methode  === 'GET') {
            return $this->displayForm();
        } elseif ($methode === 'POST') {
            return $this->addTrack();
        }

        return "<div class='alert alert-danger'>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<form method="post" action="?action=add-track" enctype="multipart/form-data" class="p-4 bg-dark text-light rounded">
    <div class="mb-3">
        <label for="type" class="form-label">Track Type:</label>
        <select id="type" name="type" class="form-select" required onchange="showFields()">
            <option value="">Select Type</option>
            <option value="P">Podcast</option>
            <option value="A">Album Track</option>
        </select>
    </div>

    <div id="podcastFields" class="add-track-fields">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" id="title" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author:</label>
            <input type="text" id="author" name="author" class="form-control">
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date:</label>
            <input type="text" id="date" name="date" class="form-control">
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre:</label>
            <input type="text" id="genre" name="genre" class="form-control">
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Audio File (.mp3):</label>
            <input type="file" id="file" name="userfile" accept=".mp3,audio/mpeg" class="form-control">
        </div>
    </div>

    <div id="albumFields" class="add-track-fields">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" id="title" name="title" class="form-control">
        </div>
        <div class="mb-3">
            <label for="genre" class="form-label">Genre:</label>
            <input type="text" id="genre" name="genre" class="form-control">
        </div>
        <div class="mb-3">
            <label for="artist" class="form-label">Artist:</label>
            <input type="text" id="artist" name="artist" class="form-control">
        </div>
        <div class="mb-3">
            <label for="albumTitle" class="form-label">Album Title:</label>
            <input type="text" id="albumTitle" name="albumTitle" class="form-control">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year:</label>
            <input type="number" id="year" name="year" class="form-control">
        </div>
        <div class="mb-3">
            <label for="trackNumber" class="form-label">Track Number:</label>
            <input type="number" id="trackNumber" name="trackNumber" class="form-control">
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Audio File (.mp3):</label>
            <input type="file" id="file" name="userfile" accept=".mp3,audio/mpeg" class="form-control">
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Add Track</button>
</form>

<script>
function showFields() {
    var type = document.getElementById("type").value;
    document.getElementById("podcastFields").style.display = (type === "P") ? "block" : "none";
    document.getElementById("albumFields").style.display = (type === "A") ? "block" : "none";
}
</script>
HTML;
    }


    private function addTrack(): string
    {
        $file = $_FILES['userfile'];
        $fileExtension = strtolower(substr($file['name'], -4));
        $playlist = $_SESSION['current_playlist'];
        $playlistId = $_SESSION['current_playlist']->id;

        if (!isset($playlist)) {
            return "<div class='alert alert-danger text-center mt-3'>Error: No current playlist found.</div>";
        }

        // verifier si l'utilisateur est le proprietaire de la playlist
        try {
            Authz::checkPlaylistOwner($playlistId);
        } catch (AuthnException $e) {
            return "<div class='alert alert-danger text-center mt-3'>Access denied: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
        }

        // Assurer qu'un fichier a ete telecharge
        if (!isset($file) || $file['error'] != UPLOAD_ERR_OK) {
            return "<div class='alert alert-danger text-center mt-3'>Error: No file uploaded.</div>";
        }

        // verifier le type de fichier et l'extension
        if ($fileExtension !== '.mp3' || $file['type'] !== 'audio/mpeg') {
            return "<div class='alert alert-danger text-center mt-3'>Error: Invalid file type. Only MP3 files are allowed.</div>";
        }

        // error if uploading PHP files
        if (strpos($file['name'], '.php') !== false) {
            return "<div class='alert alert-danger text-center mt-3'>Error: Uploading PHP files is not allowed.</div>";
        }

        // generer un nouveau nom de fichier et enregistrer le fichier
        $newFilename = uniqid() . '.mp3';
        $destination = __DIR__ . '/../../../music/' . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return "<div class='alert alert-danger text-center mt-3'>Error: Failed to save file.</div>";
        }

        // utiliser getID3 pour obtenir la duree du fichier
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($destination);
        $duration = isset($fileInfo['playtime_seconds']) ? (int)$fileInfo['playtime_seconds'] : 0;

        // verifier la duree du fichier
        if ($duration <= 0) {
            return "<div class='alert alert-danger text-center mt-3'>Error: The uploaded audio file has an invalid duration.</div>";
        }

        // file path PodcastTrack
        $repo = DeefyRepository::getInstance();
        $file_path = "music/" . $newFilename;

        // recuperer les donnees du formulaire
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);

        // creation d'un objet piste en fonction du type
        if ($type === 'P') {
            $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);

            // PodcastTrack
            $track = new tracks\PodcastTrack($title, $file_path, $duration);
            $track->setAuteur($author);
            $track->setDate($date);
            $track->setGenre($genre);

            $savedTrack = $repo->saveTrack($track, 'P');
        } elseif ($type === 'A') {
            $artist = filter_input(INPUT_POST, 'artist', FILTER_SANITIZE_SPECIAL_CHARS);
            $albumTitle = filter_input(INPUT_POST, 'albumTitle', FILTER_SANITIZE_SPECIAL_CHARS);
            $year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
            $trackNumber = filter_input(INPUT_POST, 'trackNumber', FILTER_VALIDATE_INT);

            // AlbumTrack
            $track = new tracks\AlbumTrack($title, $file_path, $albumTitle, $trackNumber, $duration, $artist, $year, $genre);

            $savedTrack = $repo->saveTrack($track, 'A');
        } else {
            return "<div class='alert alert-danger text-center mt-3'>Error: Invalid track type selected.</div>";
        }

        $repo->addTrackToPlaylist($playlist->id, $track->id, $playlist->nombrePistes + 1);

        $html = "<div class='alert alert-success text-center mt-3' role='alert'>Track successfully added! Reloading playlist...</div>";
        $html .= "<script>
                setTimeout(function() {
                    window.location.href = '?action=display-playlist&id=" . htmlspecialchars($playlist->id, ENT_QUOTES, 'UTF-8') . "';
                }, 2000);
              </script>";

        return $html;
    }
}
