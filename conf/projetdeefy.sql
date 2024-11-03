
CREATE TABLE `playlist` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `playlist` (`id`, `nom`) VALUES
(1, 'Best of rock'),
(2, 'Musique classique'),
(3, 'Best of country music'),
(4, 'Best of Elvis Presley'),
(16, 'Bones Playlist'),
(19, 'French Podcasts'),
(20, 'French Podcasts'),
(21, 'French Podcasts'),
(22, 'AdminPlaylist');

-- --------------------------------------------------------


CREATE TABLE `playlist2track` (
  `id_pl` int(11) NOT NULL,
  `id_track` int(11) NOT NULL,
  `no_piste_dans_liste` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



INSERT INTO `playlist2track` (`id_pl`, `id_track`, `no_piste_dans_liste`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 20, 3),
(1, 21, 4),
(2, 3, 1),
(2, 4, 2),
(3, 5, 1),
(3, 6, 2),
(4, 7, 1),
(4, 8, 2),
(16, 17, 1),
(16, 18, 2),
(16, 23, 3),
(21, 22, 1),
(22, 24, 1);

-- --------------------------------------------------------


CREATE TABLE `track` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `genre` varchar(30) DEFAULT NULL,
  `duree` int(3) DEFAULT NULL,
  `filename` varchar(100) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `artiste_album` varchar(30) DEFAULT NULL,
  `titre_album` varchar(30) DEFAULT NULL,
  `annee_album` int(4) DEFAULT NULL,
  `numero_album` int(11) DEFAULT NULL,
  `auteur_podcast` varchar(100) DEFAULT NULL,
  `date_posdcast` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `track` (`id`, `titre`, `genre`, `duree`, `filename`, `type`, `artiste_album`, `titre_album`, `annee_album`, `numero_album`, `auteur_podcast`, `date_posdcast`) VALUES
(1, 'Wish You Were Here', 'rock', 334, 'music/pink_wish.mp3', 'A', 'Pink Floyd', 'Wish You Were Here', 1975, 1, NULL, NULL),
(2, 'Samba Pati', 'rock', 300, 'music/santana_abra.mp3', 'A', 'Santana', 'Abraxas', 1970, 1, NULL, NULL),
(3, 'Danube Bleu', 'musique classique', 300, 'music/straus_danube.mp3', 'A', 'Johann Strauss', 'Valses', 2000, 1, NULL, NULL),
(4, 'Lettre à Elise', 'musique classique', 400, 'music/beethoven_elise.mp3', 'A', 'Beethoven', 'Piano', 1966, 1, NULL, NULL),
(5, 'Annie song', 'country', 200, 'music/denver_annie.mp3', 'A', 'John Denver', 'Best of J. Denver', 2001, 1, NULL, NULL),
(6, 'Tequila sunrise', 'country', 300, 'music/eagles_teq.mp3', 'A', 'Eagles', 'Best of Eagles', 2007, 1, NULL, NULL),
(7, 'In the ghetto', 'country', 200, 'music/elvis_annie.mp3', 'A', 'Elvis Presley', 'Best of E. Presley', 2002, 1, NULL, NULL),
(8, 'La vie des papillons', 'docu', 200, 'music/papillons.mp3', 'P', NULL, NULL, NULL, NULL, 'Bolo', '2004-10-12'),
(9, 'La vie des libellules', 'docu', 200, 'music/libellules.mp3', 'P', NULL, NULL, NULL, NULL, 'Bolo', '2004-10-12'),
(17, 'HDMI', 'hip-hop', 137, 'music/672521ed33e5a.mp3', 'A', 'Bones', 'Rotten', 2014, 1, NULL, NULL),
(18, 'AirplaneMode', 'hip-hop', 94, 'music/672529b1751e8.mp3', 'A', 'Bones', 'FeelLikeDirt', 2019, 1, NULL, NULL),
(19, 'Summertime Sadness', 'hip-hop', 265, 'music/6725b484bf574.mp3', 'A', 'Lana del Rey', 'Born to Die', 2012, 1, NULL, NULL),
(20, 'SparrowsCreek', 'rock', 94, 'music/6726306940e1a.mp3', 'A', 'Bones', 'SparrowsCreek', 2014, 1, NULL, NULL),
(21, 'Hey Jude', 'rock', 489, 'music/672641f2d6911.mp3', 'A', 'The Beatles', 'Single', 1970, 1, NULL, NULL),
(22, 'How to Talk Easy', 'education', 106, 'music/67264df7045b1.mp3', 'P', NULL, NULL, NULL, NULL, 'French School', '2023-08-04'),
(23, 'LooseScrew', 'rock', 174, 'music/6726baef795db.mp3', 'A', 'Bones', 'FeelLikeDirt', 2019, 1, NULL, NULL),
(24, 'Young and Beautiful', 'pop', 238, 'music/6726bc10eeb57.mp3', 'A', 'Lana Del Rey', 'Single', 2013, 1, NULL, NULL);

-- --------------------------------------------------------


CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `passwd` varchar(256) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



INSERT INTO `user` (`id`, `email`, `passwd`, `role`) VALUES
(1, 'user1@mail.com', '$2y$12$e9DCiDKOGpVs9s.9u2ENEOiq7wGvx7sngyhPvKXo2mUbI3ulGWOdC', 1),
(2, 'user2@mail.com', '$2y$12$4EuAiwZCaMouBpquSVoiaOnQTQTconCP9rEev6DMiugDmqivxJ3AG', 1),
(3, 'user3@mail.com', '$2y$12$5dDqgRbmCN35XzhniJPJ1ejM5GIpBMzRizP730IDEHsSNAu24850S', 1),
(4, 'user4@mail.com', '$2y$12$ltC0A0zZkD87pZ8K0e6TYOJPJeN/GcTSkUbpqq0kBvx6XdpFqzzqq', 1),
(5, 'admin@mail.com', '$2y$12$JtV1W6MOy/kGILbNwGR2lOqBn8PAO3Z6MupGhXpmkeCXUPQ/wzD8a', 100),
(6, 'user5@mail.com', '$2y$12$au7UxF1bgkYsBWan5MOC7uUaWmhaNbHDn1iva3ZfWvi3tZOE08kyW', 1),
(7, 'user6@mail.com', '$2y$12$x/3sHPLz4G7nlZ456NrfHO9V14F0o0K/F21zQ2LG9yd1BoQRFUTeK', 1),
(8, 'user7@mail.com', '$2y$12$0D9ZqBfB.T/2IOnyPYZbHe02Ah.gW7xfwuVRAcDY1HGnlK0RBjOJ6', 1),
(9, 'user8@mail.com', '$2y$12$hdq0CEsPfZzLjSpHJYqODuU3WlEEM5B9ExZfpL9LtkQyttr/1WQBG', 1);

-- --------------------------------------------------------


CREATE TABLE `user2playlist` (
  `id_user` int(11) NOT NULL,
  `id_pl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `user2playlist` (`id_user`, `id_pl`) VALUES
(1, 1),
(1, 2),
(1, 16),
(1, 21),
(2, 3),
(3, 4),
(5, 22);


ALTER TABLE `playlist`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `playlist2track`
  ADD PRIMARY KEY (`id_pl`,`id_track`),
  ADD KEY `id_track` (`id_track`);

ALTER TABLE `track`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user2playlist`
  ADD PRIMARY KEY (`id_user`,`id_pl`),
  ADD KEY `id_pl` (`id_pl`);


ALTER TABLE `playlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `track`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;


ALTER TABLE `playlist2track`
  ADD CONSTRAINT `playlist2track_ibfk_1` FOREIGN KEY (`id_pl`) REFERENCES `playlist` (`id`),
  ADD CONSTRAINT `playlist2track_ibfk_2` FOREIGN KEY (`id_track`) REFERENCES `track` (`id`);

ALTER TABLE `user2playlist`
  ADD CONSTRAINT `user2playlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user2playlist_ibfk_2` FOREIGN KEY (`id_pl`) REFERENCES `playlist` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;
