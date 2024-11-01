<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use PDO;

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
        return $stmt->fetchAll(PDO::FETCH_OBJ);
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

        $playlist = new Playlist($data['nom']);
        $playlist->id = $data['id'];

        return $playlist;
    }

    // Methode pour sauvegarder une playlist vide
    public function saveEmptyPlaylist($playlist): Playlist
    {
        $stmt = $this->pdo->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $stmt->execute(['nom' => $playlist->nom]);
        $playlist->id = $this->pdo->lastInsertId();
        return $playlist;
    }
}
