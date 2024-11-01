<?php

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\repository\DeefyRepository;

DeefyRepository::setConfig(__DIR__ . '\conf\db.config.ini');

$repo = DeefyRepository::getInstance();

//$playlists = $repo->findAllPlaylists();
//foreach ($playlists as $pl) {
//    print "Playlist: " . $pl->nom . " (ID: " . $pl->id . ")\n <br>";
//}
//
//
//$pl = new PlayList('test');
//$pl = $repo->saveEmptyPlaylist($pl);
//print "playlist  : " . $pl->nom . ":". $pl->id . "\n";
//
//try {
//    $playlist = $repo->findPlaylistById(2);
//    echo "Found playlist : " . $playlist->nom . " (ID: " . $playlist->id . ")\n <br>";
//} catch (Exception $e) {
//    echo "Error : " . $e->getMessage() . "\n <br>";
//}
//
//$track = new \iutnc\deefy\audio\tracks\PodcastTrack('test', 'music/already_rich.mp3', 10, 'auteur', '2021-01-01', 'genre');;
//$track = $repo->savePodcastTrack($track);
//
//print "track 2 : " . $track->titre . " : ". get_class($track). "\n";
//$repo->addTrackToPlaylist($pl->id, $track->id);
