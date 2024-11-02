# Projet Deefy - Application de Musique 🎵

**Projet réalisé par :** BABACHANAKH Kateryna groupe S3A

## Description du projet
Deefy est une application Web de gestion musicale qui permet aux utilisateurs de gérer des playlists, d'écouter des pistes et de naviguer parmi leurs pistes préférées. Elle propose une interface pour ajouter des playlists, ajouter des pistes dans une playlist, et écouter la musique directement depuis l'application.

---

## Table des matières

- [Fonctionnalités](#fonctionnalités)
- [Installation et configuration](#installation-et-configuration)
- [Script de base de données](#script-de-base-de-données)
- [Informations pour le test](#informations-pour-le-test)
- [Structure du dépôt](#structure-du-dépôt)

---

## Fonctionnalités

Voici un tableau de bord listant les fonctionnalités réalisées :

| Fonctionnalité                     | Description                                                                   | Détails de l'implémentation   |
|------------------------------------|-------------------------------------------------------------------------------|-------------------------------|
| Authentification utilisateur | Permet aux utilisateurs de se connecter et de se déconnecter. | Implémentée dans SignInAction.php, SignOutAction.php. Utilise AuthProvider.php pour la logique d’authentification.  |
| Inscription utilisateur	 | Création d’un compte utilisateur avec le rôle STANDARD.	| Implémentée dans AddUserAction.php, avec vérification des informations fournies par l'utilisateur et stockage sécurisé des mots de passe. |
| Gestion de playlists	       | Les utilisateurs peuvent créer de nouvelles playlists, les consulter et y ajouter des pistes.| Implémentée dans AddPlaylistAction.php, DisplayUserPlaylistsAction.php et AddTrackAction.php. Les playlists sont stockées en session et dans la base de données.  |
| Gestion des pistes | Les utilisateurs peuvent afficher les détails de la piste et écouter de la musique. | Les informations sur les pistes sont gérées avec AlbumTrack.php, PodcastTrack.php, QudioTrack.php et affichées via AudioListRenderer.php, AlbumTrackRenderer.php, PodcastRenderer.php |
| Ajout de pistes à une playlist | Permet d’ajouter de nouvelles pistes dans une playlist affichée.	 | Formulaire d’ajout de pistes dans AddTrackAction.php. Les pistes sont ajoutées dans la base et associées à la playlist courante. |
| Sélection aléatoire de couvertures de playlists et de pistes| Attribue aléatoirement une image à chaque playlist et piste parmi celles disponibles dans le dossier images. | Implémenté dans DisplayUserPlaylistsAction.php et DefaultAction.php avec la fonction getRandomImagePath.  |
| Afficher la playlist courante | Affiche la playlist en cours stockée en session. | Rendu dans DisplayPlaylistAction.php avec AudioListRenderer.php, AlbumTrackRenderer.php, PodcastRenderer.php. Utilise les sessions pour récupérer la playlist courante. |
| Accès restreint aux playlists | Seul le propriétaire de la playlist ou un utilisateur avec le rôle ADMIN peut afficher ou ajouter des pistes à une playlist. | Contrôle d'accès géré dans Authz.php et DisplayPlaylistAction.php avec vérification des permissions. |
| Gestion des erreurs et validation | Gère les erreurs comme les fichiers manquants et les valeurs de propriétés non valides.  |  Exceptions personnalisées dans le dossier exception (InvalidPropertyNameException.php, InvalidPropertyValueException.php, AuthnException.php). |
| Rendu des détails des pistes	| Affiche des vues compactes et détaillées des informations sur les pistes.	| Rendu via AlbumTrackRenderer.php et PodcastRenderer.php avec les méthodes renderCompact et renderLong. |
| Sécurité des données | Assure le stockage sécurisé des mots de passe et la protection contre les attaques XSS et SQL injection. | Mots de passe hachés avec un algorithme sécurisé. Protection XSS via htmlspecialchars et requêtes SQL préparées pour éviter les injections SQL. |
| Intégration de la base de données | Utilise un modèle de dépôt pour gérer les playlists et les données des pistes.	 | Implémenté dans DeefyRepository.php en utilisant des requêtes pour stocker et récupérer les données de playlist. |
| Design réactif | Assure que l’interface utilisateur est accessible | Styles dans styles.css, utilisant Bootstrap pour les mises en page réactives. |

---

## Installation et configuration

### Prérequis

- **PHP** (version 7.4+)
- **MySQL** pour la gestion de la base de données.
- **Serveur web** (Apache, Nginx, ou autre).
- **Composer** pour gérer les dépendances PHP.

### Étapes d'installation

1. Cloner le dépôt Git
2. Installer les dépendances
3. Configurer la base de données
4. Configurer le serveur Web
5. Démarrer l'application

---

## Script de base de données
   
