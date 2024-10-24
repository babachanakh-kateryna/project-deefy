<?php
//require_once 'src/classes/InvalidPropertyNameException.php';
//require_once 'src/classes/InvalidPropertyValueException.php';
//
//require_once 'src/classes/AudioTrack.php';
//require_once 'src/classes/AlbumTrack.php';
//require_once 'src/classes/PodcastTrack.php';
//
//require_once 'src/classes/AudioList.php';
//require_once 'src/classes/Album.php';
//require_once 'src/classes/Playlist.php';
//
//require_once 'src/classes/Renderer.php';
//require_once 'src/classes/AudioTrackRenderer.php';
//require_once 'src/classes/AlbumTrackRenderer.php';
//require_once 'src/classes/PodcastRenderer.php';
//require_once 'src/classes/AudioListRenderer.php';

//require_once 'src/loader/Psr4ClassLoader.php';

use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\render as render;
//use iutnc\deefy\loader as loader;

//$loader = new loader\Psr4ClassLoader('iutnc\\deefy', 'src/classes');
//$loader->register();

require_once 'vendor/autoload.php';

print "<h1>AudioList</h1>";
$podcast = new tracks\PodcastTrack("My Podcast", "", 555);
$piste1 = new tracks\AlbumTrack("Already Rich1", "music/already_rich.mp3", "Already Rich", 1, 145);
$piste2 = new tracks\AlbumTrack("Already Rich2", "music/already_rich.mp3", "Already Rich", 1, 145);
$piste3 = new tracks\AlbumTrack("Already Rich3", "music/already_rich.mp3", "Already Rich", 1, 145);
$piste4 = new tracks\AlbumTrack("Already Rich4", "music/already_rich.mp3", "Already Rich", 1, 145);
$playlist = new lists\Playlist("Ma playlist");
$playlist->ajouterPiste(new tracks\AlbumTrack("Already Rich5", "music/already_rich.mp3", "Already Rich", 1, 145));
$playlist->ajouterListePistes([$piste1, $piste2, $piste3, $piste4, $podcast]);
$renderer = new render\AudioListRenderer($playlist);
echo $renderer->render(1);