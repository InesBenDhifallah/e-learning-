

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `phonenumber` varchar(255) DEFAULT NULL,
  `matiere` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `work` varchar(255) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `pref` varchar(255) DEFAULT NULL,
  `idmatiere_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `phonenumber`, `matiere`, `experience`, `reason`, `is_active`, `work`, `adress`, `pref`, `idmatiere_id`) VALUES
(1, 'aaaa@azrazr.com', '[\"ROLE_TEACHER\"]', '$2y$13$Rd/wuGR1HVo8m9kJ57vYEOPK9UGLIAPoOURtzfjQ2Vom7lkOzaNsK', 'aaa', '123456', 'mathematiques', 12, 'aerzer', 0, NULL, NULL, NULL, NULL),
(2, 'zaeraezr@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$wumCJA73t/eg5xMXAMDI0uVgb6Z74fDWh.SEi4gqhA7SOh6QVHynC', 'aaaa', '123123', 'sciences', 12, 'azrazr', 0, NULL, NULL, NULL, NULL),
(3, 'zaeraezaezeazer@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$r3jFTOhIq/3wGVebP4NovucCLmb8.WhYdPmAFd.MxssveYCqAk2zy', 'aaaa', '123123', 'sciences', 12, 'azrazr', 0, NULL, NULL, NULL, NULL),
(4, 'aaaab@azrazr.com', '[\"ROLE_TEACHER\"]', '$2y$13$GqxhpC8pCXA6um4xkwsKvu3tuZJxIs.wTLenGM2awa5oUWozyZ2xK', 'azerezar', '123123', 'sciences', 14, 'zzz', 0, NULL, NULL, NULL, NULL),
(5, 'aaaaaaaaaaa@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$VoPE6BfQqk26SwDBtcRBPuWDsWm0HG252sDdzJyB5CccBKc.Th/62', 'aaa', '1234456', 'sciences', 12, 'aaa', 0, NULL, NULL, NULL, NULL),
(6, 'nanous.bellagha@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$v.zkuZkvKBk8Buy6eVbcFuLCNmjk7Mu5eGVj87ueOVOrR9TzakwmC', 'nanousti', '123456', 'sciences', 12, 'aaa', 0, NULL, NULL, NULL, NULL),
(7, 'ines.bendhifallah@gmail.com', '[]', '$2y$13$a3uBAVkxPTvVPgvVy5blVO7DNNP9etF557m3BNqfZT7J4/S8No6/q', 'ines', '123456', 'math', 12, 'rzaraer', 0, NULL, NULL, '0', NULL),
(8, 'oumaima.ghediri@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$YW4cGqEVkGydlyV9sIAkbeM9jpuPGl5ZBPccTonzkvLPOsjCtgr.C', 'razer', '12346578', 'azerr', 12, 'aezrazer', 0, NULL, NULL, '0', NULL),
(11, 'oumaima.aghediri@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$VKRBXjVj6yl5NG/wNG3LsuC0Pj6wELEn/XlWqSryjAIgdiLHC43py', 'razer', '12346578', 'azerr', 12, 'aezrazer', 0, NULL, NULL, '0', NULL),
(12, 'ines.bendhifallah@istic.ucar.tn', '[\"ROLE_TEACHER\"]', '$2y$13$CtdefmsdVSnXrivVU/tV9uXL/kVgwTYXiHt89znYHw21LpugiVWqC', 'razer', '12346578', 'azerr', 12, 'aezrazer', 0, NULL, NULL, '0', NULL),
(13, 'bellagha.aziz03@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$dC6VVez21x0PE6MYs45i4.R2lOGKGYz.FzewtrProsvauPcYWKXzS', 'aezr', '123456799', 'azer', 12, 'aezrazer', 0, NULL, NULL, '0', NULL),
(14, 'mohamedaziz.bellagha@istic.ucar.tn', '[\"ROLE_TEACHER\"]', '$2y$13$kGgpHdsOAmP/GFopNajsQu5ePymFQWPBObID36iK0jbSDA2bf/JC2', 'azerzer', '12345678', 'azerzare', 12, 'aezrazre', 0, NULL, NULL, '0', NULL),
(15, 'bellagha.khalil01@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$WW85b/GCce6h9PsYUsdVZ.OmxrssydESxKxgYkk.UOTH5y6OIahOO', 'azerzer', '12345678', 'azerzare', 12, 'aezrazre', 0, NULL, NULL, '0', NULL),
(16, 'kkk@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$PXjU1ZsdQZR4csn3cOlYNePT6ywppPXTRlj0WGesgOay2VRAvPCnG', 'zerazer', '123456', 'azerzar', 12, 'zaer', 0, NULL, NULL, '0', NULL),
(17, 'aezr@gmail.com', '[\"ROLE_Parent\"]', '$2y$13$53wRVXlocnBrEMnRoo82KetBA9F37Ez.sZ0SUIf.uMPfz4Z/1qcS6', 'zerazre', '123456', NULL, NULL, NULL, 0, 'zaerazr', 'email', '0', NULL),
(18, 'abc@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$h84Yu3jxYu26gxPUE2Ho1eqQMWdKdD9KtoVCqSebntqC6VnXT0KL.', 'azer', '123456', 'azer', 12, 'zar', 0, NULL, NULL, '0', NULL),
(19, 'aaa@gmail.com', '[\"ROLE_Parent\"]', '$2y$13$oThKqd3FmReVER7ivfYnqeRba01cn9gEbAD3nygQ77deV1YN4jbzi', 'aaze', '12345678', NULL, NULL, NULL, 0, 'aeazeae', 'email', '0', NULL),
(20, 'bbb@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$MuETC3CtDln1vEyhJaXKpuxDSGMAI.bPf3v4dnMnLtvYnAh6doM8O', 'bbb', '12345678', NULL, NULL, NULL, 0, 'azeaze', 'email', '0', NULL),
(21, 'bbbbc@gmail.com', '[\"ROLE_Parent\"]', '$2y$13$7sFnjCUF.zHjEia48xBFMu3ctjy0rda6.SLRutQ.k1cZqpnU9TEjm', 'bbb', '12345678', NULL, NULL, NULL, 0, 'azeaze', 'email', '0', NULL),
(22, 'ooo@gmail.com', '[\"ROLE_Parent\"]', '$2y$13$yCGiwwjJ5zujCJktk.TZ6.JovYAgsDByXvZcXZdhFT2EZhmzEgsgC', 'ooo', '12345678', NULL, NULL, NULL, 0, 'azerazr', 'email', '0', NULL),
(23, 'zzz@gmail.com', '[\"ROLE_TEACHER\"]', '$2y$13$UYKBZ6OAROKJyvO60LVRNeVYS62YIUQA69JWkEqVRW0tjbbS7CK/m', 'aazer', '12345678', 'aerezr', 12, 'azerezar', 0, NULL, NULL, '0', NULL),
(24, 'bendhifallahines@gmail.com', '[\"ROLE_Parent\"]', '$2y$13$TtG7LCAT05ITJKHDYookGO0FEw3j4aANKkOAUUt5AiPEZDyYoWuQ2', 'aaaa', '+21655672594', NULL, NULL, NULL, 0, 'azeaze', 'aziz', 'email', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D64939C5CF62` (`idmatiere_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64939C5CF62` FOREIGN KEY (`idmatiere_id`) REFERENCES `module` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
