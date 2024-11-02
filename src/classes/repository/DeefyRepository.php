<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use PDO;

/**
 * Class DeefyRepository est une classe qui permet de gerer les donnees de l'application
 */
class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $this->pdo = new PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance(): self
    {
        // Check if the configuration is set
        if (empty(self::$config)) {
            throw new \Exception("Database configuration is not set");
        }
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }

    public static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }

        if (!isset($conf['host'], $conf['dbname'], $conf['username'], $conf['password'])) {
            throw new \Exception("Configuration file is missing required database parameters.");
        }

        self::$config = [
            'dsn' => sprintf("mysql:host=%s;dbname=%s;charset=utf8", $conf['host'], $conf['dbname']),
            'user' => $conf['username'],
            'pass' => $conf['password'],
        ];
    }

    // Methode pour recuperer toutes les playlists
    public function findAllPlaylists(): array
    {
        $stmt = $this->pdo->query("SELECT id, nom FROM playlist");
        $playlistsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $playlists = [];
        foreach ($playlistsData as $data) {
            $playlist = new Playlist(htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8'));
            $playlist->id = $data['id'];
            $playlists[] = $playlist;
        }

        return $playlists;
    }

    // Methode pour recuperer une playlist par son id
    public function findPlaylistById(int $id): Playlist
    {
        $stmt = $this->pdo->prepare("SELECT id, nom FROM playlist WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            throw new \Exception("Playlist not found");
        }

        $playlist = new Playlist(htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8'));
        $playlist->id = $data['id'];

        // recuperer les pistes associees a la playlist
        $trackStmt = $this->pdo->prepare("
        SELECT t.*
        FROM track t
        INNER JOIN playlist2track p2t ON t.id = p2t.id_track
        WHERE p2t.id_pl = :id
        ORDER BY p2t.no_piste_dans_liste
    ");
        $trackStmt->execute(['id' => $id]);
        $tracksData = $trackStmt->fetchAll(PDO::FETCH_ASSOC);

        // ajouter les pistes a la playlist
        foreach ($tracksData as $trackData) {
            // if PodcastTrack
            if ($trackData['type'] === 'P') {
                $track = new PodcastTrack(
                    htmlspecialchars($trackData['titre'], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($trackData['filename'], ENT_QUOTES, 'UTF-8'),
                    $trackData['duree']
                );
                $track->setAuteur(htmlspecialchars($trackData['auteur_podcast'], ENT_QUOTES, 'UTF-8'));
                $track->setDate(htmlspecialchars($trackData['date_posdcast'], ENT_QUOTES, 'UTF-8'));
                $track->setGenre(htmlspecialchars($trackData['genre'], ENT_QUOTES, 'UTF-8'));
                $playlist->ajouterPiste($track);
            }
            // if AlbumTrack
            elseif ($trackData['type'] === 'A')  {

                $track = new AlbumTrack(
                    htmlspecialchars($trackData['titre'], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($trackData['filename'], ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($trackData['titre_album'], ENT_QUOTES, 'UTF-8'),
                    $trackData['numero_album'],
                    $trackData['duree'],
                    htmlspecialchars($trackData['artiste_album'], ENT_QUOTES, 'UTF-8'),
                    $trackData['annee_album'],
                    htmlspecialchars($trackData['genre'], ENT_QUOTES, 'UTF-8')
                );
                $track->setArtiste($trackData['artiste_album'] ?? 'Unknown Artist');
                $track->setAnnee($trackData['annee_album']  ?? 0); ;
                $track->setGenre($trackData['genre']);
                $playlist->ajouterPiste($track);
            }
        }

        return $playlist;
    }

    // Methode pour sauvegarder une playlist vide
    public function saveEmptyPlaylist($playlist): Playlist
    {
        $playlist->nom = htmlspecialchars($playlist->nom, ENT_QUOTES, 'UTF-8');
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $stmt->execute(['nom' => $playlist->nom]);
        $playlist->id = $this->pdo->lastInsertId();
        return $playlist;
    }

    // Methode pour sauvegarder une piste de podcast
    public function saveTrack(AudioTrack $track, string $type): AudioTrack
    {
        // common data pour tous les types de pistes
        $data = [
            'titre' => htmlspecialchars($track->__get('titre'), ENT_QUOTES, 'UTF-8'),
            'filename' => htmlspecialchars($track->__get('nom_du_fichier'), ENT_QUOTES, 'UTF-8'),
            'duree' => $track->getDuree(),
            'type' => $type,
            'auteur_podcast' => null,
            'date_posdcast' => null,
            'genre' => null,
            'artiste_album' => null,
            'titre_album' => null,
            'annee_album' => null,
            'numero_album' => null
        ];

        if ($type === 'P' && $track instanceof PodcastTrack) {
            $data['auteur_podcast'] = htmlspecialchars($track->getAuteur(), ENT_QUOTES, 'UTF-8');
            $data['date_posdcast'] = htmlspecialchars($track->getDate(), ENT_QUOTES, 'UTF-8');
            $data['genre'] = htmlspecialchars($track->getGenre(), ENT_QUOTES, 'UTF-8');
        } elseif ($type === 'A' && $track instanceof AlbumTrack) {
            $data['artiste_album'] = htmlspecialchars($track->artiste, ENT_QUOTES, 'UTF-8');
            $data['titre_album'] = htmlspecialchars($track->album, ENT_QUOTES, 'UTF-8');
            $data['annee_album'] = $track->annee;
            $data['numero_album'] = $track->numero_piste;
            $data['genre'] = htmlspecialchars($track->genre, ENT_QUOTES, 'UTF-8');
        }

        $stmt = $this->pdo->prepare("
        INSERT INTO track (titre, filename, duree, type, auteur_podcast, date_posdcast, genre, artiste_album, titre_album, annee_album, numero_album)
        VALUES (:titre, :filename, :duree, :type, :auteur_podcast, :date_posdcast, :genre, :artiste_album, :titre_album, :annee_album, :numero_album)
    ");
        $stmt->execute($data);

        $trackId = $this->pdo->lastInsertId();
        $track->id = $trackId;

        return $track;
    }

    // Methode pour ajouter une piste a une playlist
    public function addTrackToPlaylist(int $playlistId, int $trackId, int $noPisteDansListe): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste)
            VALUES (:id_pl, :id_track, :no_piste_dans_liste)
        ");

        $stmt->execute([
            'id_pl' => $playlistId,
            'id_track' => $trackId,
            'no_piste_dans_liste' => $noPisteDansListe
        ]);
    }

    // Methode pour recuperer toutes les playlists d'un utilisateur
    public function findPlaylistsByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.id, p.nom
            FROM playlist p
            INNER JOIN user2playlist u2p ON p.id = u2p.id_pl
            WHERE u2p.id_user = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        $playlistsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $playlists = [];
        foreach ($playlistsData as $data) {
            $playlist = new Playlist(htmlspecialchars($data['nom'], ENT_QUOTES, 'UTF-8'));
            $playlist->id = $data['id'];
            $playlists[] = $playlist;
        }

        return $playlists;
    }

    // Methode pour lier un utilisateur a une playlist
    public function linkUserToPlaylist(int $userId, int $playlistId): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (:id_user, :id_pl)");
        $stmt->execute(['id_user' => $userId, 'id_pl' => $playlistId]);
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    // Methode pour recuperer des pistes aleatoires par type
    public function getRandomTracksByType(string $type, int $limit): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM track WHERE type = :type ORDER BY RAND() LIMIT :limit");
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
