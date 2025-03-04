
CREATE TABLE `paiement` (
  `id` int(11) NOT NULL,
  `montant` double NOT NULL,
  `id_abonnement_id` int(11) DEFAULT NULL,
  `date_paiement` date NOT NULL,
  `userid_id` int(11) DEFAULT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT INTO `paiement` (`id`, `montant`, `id_abonnement_id`, `date_paiement`, `userid_id`, `stripe_session_id`) VALUES
(6, 700, 3, '2025-02-18', 19, NULL),
(7, 700, 3, '2025-02-18', 19, NULL),
(8, 250, 2, '2025-02-20', 24, NULL),
(9, 100, 1, '2025-02-26', 24, 'cs_test_a1yj88FpZVj0T4bCvKqBfvNEPD2ncizKfI3WoLOa0bgZ7xhT9Jc6Sas7kd'),
(10, 700, 3, '2025-02-26', 24, 'cs_test_a1tmcMjAcdZ4oVFR3LTxw3xGUItVpB5n2YVaPK7PulEGWKALXNxr8QPlFH'),
(12, 700, 3, '2025-02-26', 24, 'cs_test_a1xqurAZxyZ8NGQo4HNueFPmpcdNgapH9moTvUF8GRwJnx2OUtmSsM8Pw5'),
(13, 250, 2, '2025-02-27', 24, 'cs_test_a13SGY0T5iREJulV131NYPUj5JkAnsRutTSGelChUHcYxhBXAAJCRljq4c'),
(14, 100, 1, '2025-03-02', 24, 'cs_test_a1W23M6WKaRdLBVh3y7saEt6YF77igpagL9fThlSUWP6yBYkbmZmyvcUGY'),
(15, 250, 2, '2025-03-02', 24, 'cs_test_a16i0ypSWI6gkhBotY91hFTzeGXahmxWp0Ja0c4PoGbbKYy4TcDZVpr7T0'),
(16, 250, 2, '2025-03-02', 24, 'cs_test_a16i0ypSWI6gkhBotY91hFTzeGXahmxWp0Ja0c4PoGbbKYy4TcDZVpr7T0'),
(17, 250, 2, '2025-03-02', 24, 'cs_test_a16i0ypSWI6gkhBotY91hFTzeGXahmxWp0Ja0c4PoGbbKYy4TcDZVpr7T0'),
(18, 250, 2, '2025-03-02', 24, 'cs_test_a16i0ypSWI6gkhBotY91hFTzeGXahmxWp0Ja0c4PoGbbKYy4TcDZVpr7T0'),
(19, 250, 2, '2025-03-02', 24, 'cs_test_a16i0ypSWI6gkhBotY91hFTzeGXahmxWp0Ja0c4PoGbbKYy4TcDZVpr7T0'),
(20, 100, 1, '2025-03-02', 24, 'cs_test_a1bsvgfyxk9dhWGkDRLdRp0zaW7hW0HinvsKfs1sIz8XU02U97i5Q9OQY0'),
(21, 100, 1, '2025-03-02', 24, 'cs_test_a1jtcZIdJA2UiNGZqNpNTdMUaGlz4721pkcpr9kVU46cbdD8Yhj88XpuEp'),
(22, 100, 1, '2025-03-02', 24, 'cs_test_a1Zy5zydim0VbnPTKbufIBMuItUnvhKwCfYJToDBoWsge2mmRUW441g0uG');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B1DC7A1E4FFF9576` (`id_abonnement_id`),
  ADD KEY `IDX_B1DC7A1E58E0A285` (`userid_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_B1DC7A1E4FFF9576` FOREIGN KEY (`id_abonnement_id`) REFERENCES `abonnement` (`id`),
  ADD CONSTRAINT `FK_B1DC7A1E58E0A285` FOREIGN KEY (`userid_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
