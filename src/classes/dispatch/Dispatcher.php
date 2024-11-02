<?php
declare(strict_types=1);

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action as act;
use iutnc\deefy\render\Renderer;

/**
 * Class Dispatcher
 */
class Dispatcher
{
    private ?string $action = null;

    function __construct()
    {
        $this->action = isset($_GET['action']) ? htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8') : 'default';
    }

    public function run(): void
    {
        $html = '';
        switch ($this->action) {
            case 'default':
                $action = new act\DefaultAction();
                $html = $action->execute();
                break;
            case 'display-playlist':
                $action = new act\DisplayPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-playlist':
                $action = new act\AddPlaylistAction();
                $html = $action->execute();
                break;
            case 'add-track':
                $action = new act\AddTrackAction();
                $html = $action->execute();
                break;
            case 'add-user':
                $action = new act\AddUserAction();
                $html = $action->execute();
                break;
            case 'signin':
                $action = new act\SignInAction();
                $html = $action->execute();
                break;
            case 'display-user-playlists':
                $action = new act\DisplayUserPlaylistsAction();
                $html = $action->execute();
                break;
            case 'signout':
                $action = new act\SignOutAction();
                $html = $action->execute();
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        // verivier si l'utilisateur est connecte
        $isSignedIn = isset($_SESSION['user']);
        $username = $isSignedIn ? (is_array($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['name'] ?? 'Guest', ENT_QUOTES, 'UTF-8') : htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8')) : 'Guest';

        echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Deefy Music App<</title>
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <div class="ellipse-first"></div>
    <div class="ellipse-second"></div> 
    <div class="ellipse-third"></div> 

    <div class="sidebar">
        <h2>Deefy</h2>
HTML;

        // menu pour les utilisateurs non connectes
        if (!$isSignedIn) {
            echo <<<HTML
            <a href="?action=default">Home</a>
            <a href="?action=signin">Sign In</a>
            <a href="?action=add-user">Register</a></li>
HTML;
        } else {
            // menu pour les utilisateurs connectes
            echo <<<HTML
            <a href="?action=default">Home</a></li>
            <a href="?action=display-user-playlists">My Playlists</a>
            <a href="?action=add-playlist">Add a Playlist</a>
            <a href="?action=signout">Sign Out</a>
            HTML;

            // si on a current playlist, affiche les pistes (version compacte)
            if (isset($_SESSION['current_playlist'])) {
                $currentPlaylist = $_SESSION['current_playlist'];
                echo "<div class='current-playlist'><a href='?action=display-playlist'><h3>Current Playlist</h3></a>";

                foreach ($currentPlaylist->pistes as $track) {
                    // type de piste
                    if ($track instanceof \iutnc\deefy\audio\tracks\AlbumTrack) {
                        $renderer = new \iutnc\deefy\render\AlbumTrackRenderer($track);
                    } elseif ($track instanceof \iutnc\deefy\audio\tracks\PodcastTrack) {
                        $renderer = new \iutnc\deefy\render\PodcastRenderer($track);
                    } else {
                        continue;
                    }

                    echo "<div class='track-card play-card' data-filename='" . htmlspecialchars($track->nom_du_fichier, ENT_QUOTES, 'UTF-8') . "'>";
                    echo $renderer->render(\iutnc\deefy\render\Renderer::COMPACT);
                    echo "</div>";
                }

                echo "</div>";
            }

        }

        echo <<<HTML
    </div>
    <div class="content">
        $html
    </div>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const playButtons = document.querySelectorAll('.play-button');
        const trackCards = document.querySelectorAll('.play-card');
        
        let currentAudio = null;
        let currentButton = null;
    
        playButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filename = button.getAttribute('data-filename');
    
                // Si cliquer sur le meme bouton (piste en cours), alors arreter l'audio
                if (currentAudio && currentButton === button) {
                    if (!currentAudio.paused) {
                        currentAudio.pause();
                        button.innerHTML = '<img src="/figma_outils/Group3.png" alt="Play">';
                    } else {
                        currentAudio.play();
                        button.innerHTML = '<img src="/figma_outils/Group29.png" alt="Pause">';
                    }
                } else {
                    //  Arretez l'audio en cours s'il est deja en cours de lecture
                    if (currentAudio) {
                        currentAudio.pause();
                        currentButton.innerHTML = '<img src="/figma_outils/Group3.png" alt="Play">'; 
                    }
    
                    // un nouvel objet Audio pour lire le fichier
                    currentAudio = new Audio(filename);
                    currentAudio.play().catch(error => {
                        console.error("Error while playing audio:", error);
                    });
    
                    // Changer l'icone du bouton actuel en Pause
                    button.innerHTML = '<img src="/figma_outils/Group29.png" alt="Pause">'; 
                    currentButton = button;
    
                    // ecoutez la fin de l'evenement audio pour renvoyer l'icone Play
                    currentAudio.addEventListener('ended', () => {
                        button.innerHTML = '<img src="/figma_outils/Group3.png" alt="Play">'; 
                        currentAudio = null;
                        currentButton = null;
                    });
                }
            });
        });
        
        trackCards.forEach(card => {
            card.addEventListener('click', () => {
                const filename = card.getAttribute('data-filename');
                
                // Проверка на наличие файла
                if (!filename) {
                    console.error("Filename is missing for this track.");
                    return;
                }

                // Если уже воспроизводится аудио, то приостанавливаем его
               if (currentAudio && currentCard === card) {
                if (currentAudio.paused) {
                    currentAudio.play();
                    card.classList.add('playing');
                } else {
                    currentAudio.pause();
                    card.classList.remove('playing');
                }
            } else {
                // Останавливаем предыдущий трек, если был воспроизведен
                if (currentAudio) {
                    currentAudio.pause();
                    if (currentCard) currentCard.classList.remove('playing');
                }

                // Создаем новый объект Audio для воспроизведения
                currentAudio = new Audio(filename);
                currentAudio.play().then(() => {
                    card.classList.add('playing');
                }).catch(error => {
                    console.error("Error playing audio:", error);
                });

                // Сбрасываем состояние карточки трека после завершения
                currentAudio.addEventListener('ended', () => {
                    card.classList.remove('playing');
                    currentAudio = null;
                    currentCard = null;
                });

                // Устанавливаем текущую карточку
                currentCard = card;
            }
            });
        });
    });


    </script>
</body>
</html>
HTML;
    }

}
