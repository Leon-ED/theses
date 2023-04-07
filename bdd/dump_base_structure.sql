-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : sql208.epizy.com
-- Généré le :  ven. 07 avr. 2023 à 17:35
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP :  7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `epiz_33242290_theses`
--

-- --------------------------------------------------------

--
-- Structure de la table `alertes`
--

CREATE TABLE `alertes` (
  `id` int(10) UNSIGNED NOT NULL,
  `idCompte` int(11) NOT NULL,
  `motCle` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `a_dirige`
--

CREATE TABLE `a_dirige` (
  `id` int(10) UNSIGNED NOT NULL,
  `idPersonne` int(11) NOT NULL,
  `nnt` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

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
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `id` int(10) UNSIGNED NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `etablissement`
--

CREATE TABLE `etablissement` (
  `id` int(11) NOT NULL,
  `nom` text NOT NULL,
  `idRef` varchar(12) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `idPersonne` int(11) NOT NULL,
  `nomPersonne` varchar(255) DEFAULT NULL,
  `prenomPersonne` varchar(255) DEFAULT NULL,
  `idRef` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sujets`
--

CREATE TABLE `sujets` (
  `idMot` int(11) NOT NULL,
  `mot` text NOT NULL
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
  `resume_fr` text DEFAULT NULL,
  `resume_en` text CHARACTER SET utf8 DEFAULT NULL
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
-- Index pour les tables déchargées
--

--
-- Index pour la table `alertes`
--
ALTER TABLE `alertes`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `nnt` (`nnt`),
  ADD KEY `idPersonne` (`idPersonne`,`nnt`);

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`);

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
  ADD UNIQUE KEY `idRef` (`idRef`),
  ADD KEY `nomPersonne` (`nomPersonne`,`prenomPersonne`),
  ADD KEY `idx_personne_prenomPersonne` (`prenomPersonne`),
  ADD KEY `idx_personne_nomPersonne` (`nomPersonne`);

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
  ADD UNIQUE KEY `iddoc` (`iddoc`),
  ADD KEY `idx_these_titre_fr` (`titre_fr`),
  ADD KEY `idx_these_resume_fr` (`resume_fr`(768)),
  ADD KEY `idx_these_resume_en` (`resume_en`(1024)),
  ADD KEY `idx_these_titre_en` (`titre_en`),
  ADD KEY `idx_these_discipline` (`discipline`),
  ADD KEY `idx_these_nnt` (`nnt`),
  ADD KEY `idx_these_dateSoutenance` (`dateSoutenance`);

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `alertes`
--
ALTER TABLE `alertes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `a_dirige`
--
ALTER TABLE `a_dirige`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `a_ecrit`
--
ALTER TABLE `a_ecrit`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etablissement`
--
ALTER TABLE `etablissement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `idPersonne` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sujets`
--
ALTER TABLE `sujets`
  MODIFY `idMot` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `these`
--
ALTER TABLE `these`
  MODIFY `idThese` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `these_etablissement`
--
ALTER TABLE `these_etablissement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `these_sujet`
--
ALTER TABLE `these_sujet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
