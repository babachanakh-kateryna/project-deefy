<?php

namespace iutnc\deefy\action;

use getID3;
use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\render as render;
use iutnc\deefy\repository\DeefyRepository;

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

        return "<div>Invalid request method.</div>";
    }

    private function displayForm(): string
    {
        return <<<HTML
<form method="post" action="?action=add-track" enctype="multipart/form-data">
    <label for="type">Track Type :</label>
    <select id="type" name="type" required onchange="showFields()">
        <option value="">Select Type</option>
        <option value="P">Podcast</option>
        <option value="A">Album Track</option>
    </select>

    <div id="podcastFields" style="display:none;">
        <label for="title">Title :</label>
        <input type="text" id="title" name="title">

        <label for="author">Author :</label>
        <input type="text" id="author" name="author">

        <label for="date">Date :</label>
        <input type="text" id="date" name="date">

        <label for="genre">Genre :</label>
        <input type="text" id="genre" name="genre">
        
        <label for="file">Audio File (.mp3) :</label>
        <input type="file" id="file" name="userfile" accept=".mp3,audio/mpeg">
    </div>

    <div id="albumFields" style="display:none;">
        <label for="title">Title :</label>
        <input type="text" id="title" name="title">

        <label for="genre">Genre :</label>
        <input type="text" id="genre" name="genre">

        <label for="artist">Artist :</label>
        <input type="text" id="artist" name="artist">

        <label for="albumTitle">Album Title :</label>
        <input type="text" id="albumTitle" name="albumTitle">

        <label for="year">Year :</label>
        <input type="number" id="year" name="year">

        <label for="trackNumber">Track Number :</label>
        <input type="number" id="trackNumber" name="trackNumber">
        
        <label for="file">Audio File (.mp3) :</label>
        <input type="file" id="file" name="userfile" accept=".mp3,audio/mpeg">
    </div>

    <button type="submit">Add Track</button>
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
        if (!isset($_SESSION['current_playlist'])) {
            return "<div>Error: No current playlist found.</div>";
        }

        // Assurer qu'un fichier a ete telecharge
        if (!isset($_FILES['userfile']) || $_FILES['userfile']['error'] != UPLOAD_ERR_OK) {
            return "<div>Error: No file uploaded.</div>";
        }

        $file = $_FILES['userfile'];
        $fileExtension = strtolower(substr($file['name'], -4));

        // verifier le type de fichier et l'extension
        if ($fileExtension !== '.mp3' || $file['type'] !== 'audio/mpeg') {
            return "<div>Error: Invalid file type. Only MP3 files are allowed.</div>";
        }

        // error if uploading PHP files
        if (strpos($file['name'], '.php') !== false) {
            return "<div>Error: Uploading PHP files is not allowed.</div>";
        }

        // generer un nouveau nom de fichier et enregistrer le fichier
        $newFilename = uniqid() . '.mp3';
        $destination = __DIR__ . '/../../../music/' . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return "<div>Error: Failed to save file.</div>";
        }

        // utiliser getID3 pour obtenir la duree du fichier
        $getID3 = new getID3();
        $fileInfo = $getID3->analyze($destination);
        $duration = isset($fileInfo['playtime_seconds']) ? (int)$fileInfo['playtime_seconds'] : 0;

        // verifier la duree du fichier
        if ($duration <= 0) {
            return "<div>Error: The uploaded audio file has an invalid duration.</div>";
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
            return "<div>Error: Invalid track type selected.</div>";
        }

        $playlist = $_SESSION['current_playlist'];
        $repo->addTrackToPlaylist($playlist->id, $track->id, $playlist->nombrePistes + 1);

        $html = "<div>Track successfully added! Reloading playlist...</div>";
        $html .= "<script>
                setTimeout(function() {
                    window.location.href = '?action=display-playlist&id={$playlist->id}';
                }, 2000);
              </script>";

        return $html;
    }
}
