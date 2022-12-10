-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  sqletud.u-pem.fr
-- Généré le :  Mar 15 Novembre 2022 à 23:13
-- Version du serveur :  5.7.30-log
-- Version de PHP :  7.0.33-0+deb9u7

/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `a_dirige` (
  `id` int(10) UNSIGNED NOT NULL,
  `idPersonne` int(11) NOT NULL,
  `nnt` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Structure de la table `a_ecrit`
--

CREATE TABLE `a_ecrit` (
  `id` int(10) UNSIGNED NOT NULL,
  `idPersonne` int(11) NOT NULL,
  `nnt` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `etablissement`
--

CREATE TABLE `etablissement` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `idRef` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `idPersonne` int(11) NOT NULL,
  `nomPersonne` varchar(30) DEFAULT NULL,
  `prenomPersonne` varchar(30) DEFAULT NULL,
  `idRef` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sujets`
--

CREATE TABLE `sujets` (
  `idMot` int(11) NOT NULL,
  `mot` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `these`
--

CREATE TABLE `these` (
  `idThese` int(11) NOT NULL,
  `titre_fr` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `titre_en` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `dateSoutenance` date DEFAULT NULL,
  `langue` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `estSoutenue` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `estAccessible` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `discipline` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `nnt` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `iddoc` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `resume_fr` text CHARACTER SET utf8,
  `resume_en` text CHARACTER SET utf8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `these_etablissement`
--

CREATE TABLE `these_etablissement` (
  `id` int(11) NOT NULL,
  `nnt` varchar(12) NOT NULL,
  `id_etablissement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `these_sujet`
--

CREATE TABLE `these_sujet` (
  `id` int(10) UNSIGNED NOT NULL,
  `nnt` varchar(12) NOT NULL,
  `idMot` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `a_dirige`
--
ALTER TABLE `a_dirige`
  ADD PRIMARY KEY (`id`,`idPersonne`,`nnt`),
  ADD KEY `fk_personne3` (`idPersonne`),
  ADD KEY `fk_nnt` (`nnt`);

--
-- Index pour la table `a_ecrit`
--
ALTER TABLE `a_ecrit`
  ADD PRIMARY KEY (`id`,`idPersonne`,`nnt`),
  ADD KEY `fk_idPersonne` (`idPersonne`),
  ADD KEY `nnt` (`nnt`);

--
-- Index pour la table `etablissement`
--
ALTER TABLE `etablissement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idRef` (`idRef`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`idPersonne`),
  ADD UNIQUE KEY `idRef` (`idRef`);

--
-- Index pour la table `sujets`
--
ALTER TABLE `sujets`
  ADD PRIMARY KEY (`idMot`);

--
-- Index pour la table `these`
--
ALTER TABLE `these`
  ADD PRIMARY KEY (`idThese`),
  ADD UNIQUE KEY `nnt` (`nnt`),
  ADD UNIQUE KEY `iddoc` (`iddoc`);

--
-- Index pour la table `these_etablissement`
--
ALTER TABLE `these_etablissement`
  ADD PRIMARY KEY (`id`,`nnt`,`id_etablissement`),
  ADD KEY `fk_id_etablissement` (`id_etablissement`),
  ADD KEY `nnt` (`nnt`);

--
-- Index pour la table `these_sujet`
--
ALTER TABLE `these_sujet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nnt` (`nnt`),
  ADD KEY `idMot` (`idMot`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `a_dirige`
--
ALTER TABLE `a_dirige`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1594;
--
-- AUTO_INCREMENT pour la table `a_ecrit`
--
ALTER TABLE `a_ecrit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1363;
--
-- AUTO_INCREMENT pour la table `etablissement`
--
ALTER TABLE `etablissement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;
--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `idPersonne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2856;
--
-- AUTO_INCREMENT pour la table `sujets`
--
ALTER TABLE `sujets`
  MODIFY `idMot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4070;
--
-- AUTO_INCREMENT pour la table `these`
--
ALTER TABLE `these`
  MODIFY `idThese` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1363;
--
-- AUTO_INCREMENT pour la table `these_etablissement`
--
ALTER TABLE `these_etablissement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1441;
--
-- AUTO_INCREMENT pour la table `these_sujet`
--
ALTER TABLE `these_sujet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5059;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `a_dirige`
--
ALTER TABLE `a_dirige`
  ADD CONSTRAINT `fk_nnt` FOREIGN KEY (`nnt`) REFERENCES `these` (`nnt`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_personne3` FOREIGN KEY (`idPersonne`) REFERENCES `personne` (`idPersonne`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `a_ecrit`
--
ALTER TABLE `a_ecrit`
  ADD CONSTRAINT `a_ecrit_ibfk_1` FOREIGN KEY (`nnt`) REFERENCES `these` (`nnt`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_idPersonne` FOREIGN KEY (`idPersonne`) REFERENCES `personne` (`idPersonne`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `these_etablissement`
--
ALTER TABLE `these_etablissement`
  ADD CONSTRAINT `fk_id_etablissement` FOREIGN KEY (`id_etablissement`) REFERENCES `etablissement` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `these_etablissement_ibfk_1` FOREIGN KEY (`nnt`) REFERENCES `these` (`nnt`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `these_sujet`
--
ALTER TABLE `these_sujet`
  ADD CONSTRAINT `these_sujet_ibfk_1` FOREIGN KEY (`nnt`) REFERENCES `these` (`nnt`) ON UPDATE CASCADE,
  ADD CONSTRAINT `these_sujet_ibfk_2` FOREIGN KEY (`idMot`) REFERENCES `sujets` (`idMot`) ON UPDATE CASCADE;

