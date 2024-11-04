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
- [Structure du projet](#structure-du-projet)

---

## Fonctionnalités

Voici un tableau de bord listant les fonctionnalités réalisées :

| Fonctionnalité                     | Description                                                                   | Détails de l'implémentation   |
|------------------------------------|-------------------------------------------------------------------------------|-------------------------------|
| Authentification utilisateur | Permet aux utilisateurs de se connecter et de se déconnecter. | Implémentée dans `SignInAction.php`, `SignOutAction.php`. Utilise `AuthProvider.php` pour la logique d’authentification.  |
| Inscription utilisateur	 | Création d’un compte utilisateur avec le rôle STANDARD.	| Implémentée dans `AddUserAction.php`, avec vérification des informations fournies par l'utilisateur et stockage sécurisé des mots de passe. |
| Gestion de playlists	       | Les utilisateurs peuvent créer de nouvelles playlists, les consulter et y ajouter des pistes.| Implémentée dans `AddPlaylistAction.php`, `DisplayUserPlaylistsAction.php` et `AddTrackAction.php`. Les playlists sont stockées en session et dans la base de données.  |
| Gestion des pistes | Les utilisateurs peuvent afficher les détails de la piste et écouter de la musique. | Les informations sur les pistes sont gérées avec `AlbumTrack.php`, `PodcastTrack.php`, `AudioTrack.php`. |
| Ajout de pistes à une playlist | Permet d’ajouter de nouvelles pistes dans une playlist affichée.	 | Formulaire d’ajout de pistes dans `AddTrackAction.php`. Les pistes sont ajoutées dans la base et associées à la playlist courante. |
| Sélection aléatoire de couvertures de playlists et de pistes| Attribue aléatoirement une image à chaque playlist et piste parmi celles disponibles dans le dossier images. | Implémenté dans `DisplayUserPlaylistsAction.php` et `DefaultAction.php` avec la fonction `getRandomImagePath`.  |
| Sélection aléatoire de pistes sur la page d'accueil | Affiche une sélection aléatoire de pistes musicales sur la page d'accueil pour offrir *Top Hits Today* et *Top Podcasts Today*. | Réalisé avec la methode `getRandomTracksByType` dans la classe `DeefyRepository.php` |
| Afficher la playlist courante | Affiche la playlist en cours stockée en session. | Rendu dans `DisplayPlaylistAction.php ` avec `AudioListRenderer.php`, `AlbumTrackRenderer.php`, `PodcastRenderer.php`. Utilise les sessions pour récupérer la playlist courante. |
| Accès restreint aux playlists | Seul le propriétaire de la playlist ou un utilisateur avec le rôle ADMIN peut afficher ou ajouter des pistes à une playlist. | Contrôle d'accès géré dans `Authz.php` et `DisplayPlaylistAction.php` avec vérification des permissions. |
| Lecteur audio |Permet la lecture de musique avec une barre de progression et un affichage du temps. Le lecteur apparaît uniquement pendant la lecture d'une piste et disparaît à la fin de la musique. | Réalisé dans la classe `Dispatcher.php` avec JavaScript. Le lecteur affiche le temps écoulé et la durée totale de la piste, et permet de changer le temps de lecture avec la barre de progression. Les boutons "précédent" et "suivant" ne sont pas implémentés. |
| Écoute de musique | L'utilisateur peut écouter la musique sur la page d'accueil (Top Hits Today et Top Podcasts Today), sur la page d'une playlist spécifique, ainsi que depuis le menu de navigation si un playlist courant est stocké en session. | La lecture est gérée par le lecteur audio intégré. Les chemins des fichiers MP3 sont récupérés depuis la base de données et les fichiers audio sont stockés dans le dossier `music`. |
| Gestion des erreurs et validation | Gère les erreurs comme les fichiers manquants et les valeurs de propriétés non valides.  |  Exceptions personnalisées dans le dossier exception (`InvalidPropertyNameException.php`, `InvalidPropertyValueException.php`, `AuthnException.php`). |
| Rendu des détails des pistes	| Affiche des vues compactes et détaillées des informations sur les pistes.	| Rendu via  `AlbumTrackRenderer.php`, `PodcastRenderer.php` avec les méthodes renderCompact et renderLong. |
| Sécurité des données | Assure le stockage sécurisé des mots de passe et la protection contre les attaques XSS et SQL injection. | Mots de passe hachés avec un algorithme sécurisé. Protection XSS via htmlspecialchars et requêtes SQL préparées pour éviter les injections SQL. |
| Intégration de la base de données | Utilise un modèle de dépôt pour gérer les playlists et les données des pistes.	 | Implémenté dans `DeefyRepository.php` en utilisant des requêtes pour stocker et récupérer les données de playlist. |
| Design réactif | Assure que l’interface utilisateur est accessible | Styles dans styles.css, utilisant Bootstrap pour les mises en page réactives. |

---

## Installation et configuration

### Prérequis

- **PHP** (version 7.4+)
- **MySQL** pour la gestion de la base de données.
- **Serveur web** (Apache, Nginx, ou autre).
- **Composer** pour gérer les dépendances PHP.

### Étapes d'installation

1. **Cloner le dépôt Git :** Téléchargez le projet sur votre ordinateur.
2. **Installer les dépendances :** Ouvrez le terminal dans le dossier du projet et exécutez les commandes suivantes :
```composer require twbs/bootstrap:5.3.3``` pour installer Bootstrap, et ```composer require james-heinrich/getid3``` pour ajouter la bibliothèque getID3, utilisée pour connaître la durée d'un fichier mp3.
4. **Configurer la base de données :** Importez le script database.sql situé dans le dossier _conf/_ pour créer et remplir la base de données.
5. **Configurer le serveur Web.**
6. **Démarrer l'application.**


---

## Script de base de données
   
La base de données d'origine a été modifiée. Le script projetdeefy.sql dans le dossier conf contient toutes les commandes SQL nécessaires pour créer et remplir la base de données avec les tables et les données de test nécessaires.

## Informations pour le test

Pour tester l'application, utilisez les identifiants fournis dans la base de données de test. Voici les informations d'accès :

- **Utilisateur Standard**

Email : user1@mail.com

Mot de passe : user1

- **Administrateur**

Email : admin@example.com

Mot de passe : admin

## Structure du projet

Voici un aperçu de la structure du projet :

```
project-deefy/
├── conf/
│   ├── config.php              # Configuration de la base de données
│   └── database.sql            # Script de création/remplissage de la BD
├── css/
│   └── styles.css              # Styles de l'application
├── figma_outils/               # Images pour l'interface
├── images/                     # Couvertures aléatoires pour les playlists et pistes
├── music/                      # Music
├── src/
│   ├── classes/
│   │   ├── action/             # Actions de l'application
│   │   ├── audio/              # Classes audio (pistes, playlists)
│   │   ├── auth/               # Authentification et autorisation
│   │   ├── dispatch/           # Gestion de l'application
│   │   ├── exception/          # Gestion des exceptions
│   │   ├── render/             # Rendu de l'interface utilisateur
│   │   └── repository/         # Accès aux données de la BD
├── vendor/                     # Dépendances Composer
├── index.php                   # Point d'entrée de l'application
└── README.md                   # Documentation du projet
```
