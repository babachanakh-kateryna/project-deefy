<?php

namespace iutnc\deefy\action;

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
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>
    <label for="author">Author:</label>
    <input type="text" id="author" name="author">
    <label for="date">Date:</label>
    <input type="text" id="date" name="date">
    <label for="genre">Genre:</label>
    <input type="text" id="genre" name="genre">
    <label for="file">Audio File (.mp3):</label>
    <input type="file" id="file" name="userfile" accept=".mp3,audio/mpeg">
    <button type="submit">Add Track</button>
</form>
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
        $destination = __DIR__ . '/audio/music/' . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return "<div>Error: Failed to save file.</div>";
        }

        // file path PodcastTrack
        $repo = DeefyRepository::getInstance();
        $file_path = "audio/music/" . $newFilename;

        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
        $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_SPECIAL_CHARS);

        $track = new tracks\PodcastTrack($title, $file_path, 0);
        $track->setAuteur($author);
        $track->setDate($date);
        $track->setGenre($genre);
        $track = $repo->savePodcastTrack($track);

        $playlist = $_SESSION['current_playlist'];
        $repo->addTrackToPlaylist($playlist->id, $track->id, $playlist->nombrePistes + 1);

        $renderer = new render\AudioListRenderer($playlist);
        $html = $renderer->render(1);
        $html .= '<a href="?action=add-track">Add another track</a>';

        return $html;
    }
}
