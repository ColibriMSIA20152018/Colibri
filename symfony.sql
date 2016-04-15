-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2016 at 05:03 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `symfony`
--

-- --------------------------------------------------------

--
-- Table structure for table `acteur`
--

DROP TABLE IF EXISTS `acteur`;
CREATE TABLE IF NOT EXISTS `acteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_acteur_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `adresse_id` int(11) DEFAULT NULL,
  `amap_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_EAFAD3624DE7DC5C` (`adresse_id`),
  KEY `IDX_EAFAD3626EA9165A` (`type_acteur_id`),
  KEY `IDX_EAFAD36252AA66E8` (`amap_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `acteur`
--

INSERT INTO `acteur` (`id`, `type_acteur_id`, `nom`, `prenom`, `date_naissance`, `adresse_id`, `amap_id`) VALUES
(1, 2, 'BERGER', 'Fabien', '1994-05-20', 3, 1),
(2, 1, 'BENI', 'Céline', '1966-01-01', 4, 1),
(4, 2, 'DEBLOND', 'Marina', '1991-02-02', 2, 1),
(5, 2, 'BARBARIN', 'Baptiste', '1995-10-25', 6, 1),
(6, 2, 'JUL', 'JUL', '1995-10-25', 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
CREATE TABLE IF NOT EXISTS `adresse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numRue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `typeVoie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nomVoie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `adresse`
--

INSERT INTO `adresse` (`id`, `numRue`, `typeVoie`, `nomVoie`, `ville`, `cp`) VALUES
(2, '125', 'rue', 'je sais pas où', 'Châteauroux', '36000'),
(3, '34', 'avenue', 'François Mitterrand', 'Châteauroux', '36000'),
(4, '16', 'place', 'St-Cyran', 'Châteauroux', '36000'),
(5, '69', 'rue', 'de la Canebière', 'Marseille', '13000'),
(6, '12', 'boulevard', 'de la Châtre', 'Châteauroux', '36000'),
(7, '12', 'WESH', 'ALORS', 'Marseille', '13000'),
(8, '34', 'rue', 'francois mitterrand', 'Châteauroux', '36000'),
(9, '13', 'chemin', 'du trouduc', 'Marseille', '13000');

-- --------------------------------------------------------

--
-- Table structure for table `amap`
--

DROP TABLE IF EXISTS `amap`;
CREATE TABLE IF NOT EXISTS `amap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_id` int(11) DEFAULT NULL,
  `entrepot_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CE323CD34DE7DC5C` (`adresse_id`),
  UNIQUE KEY `UNIQ_CE323CD372831E97` (`entrepot_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `amap`
--

INSERT INTO `amap` (`id`, `libelle`, `adresse_id`, `entrepot_id`) VALUES
(1, 'Châteauroux', 2, NULL),
(2, 'Lille', 4, NULL),
(3, 'Marseille', 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contrat`
--

DROP TABLE IF EXISTS `contrat`;
CREATE TABLE IF NOT EXISTS `contrat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `consommateur_id` int(11) DEFAULT NULL,
  `producteur_id` int(11) DEFAULT NULL,
  `panier_id` int(11) DEFAULT NULL,
  `amap_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_60349993865AC8FB` (`consommateur_id`),
  KEY `IDX_60349993AB9BB300` (`producteur_id`),
  KEY `IDX_60349993F77D927C` (`panier_id`),
  KEY `IDX_6034999352AA66E8` (`amap_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contrat`
--

INSERT INTO `contrat` (`id`, `consommateur_id`, `producteur_id`, `panier_id`, `amap_id`) VALUES
(1, 1, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `entrepot`
--

DROP TABLE IF EXISTS `entrepot`;
CREATE TABLE IF NOT EXISTS `entrepot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D805175A4DE7DC5C` (`adresse_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `entrepot`
--

INSERT INTO `entrepot` (`id`, `libelle`, `adresse_id`) VALUES
(1, 'Châteauroux', 8),
(2, 'Marseille', 9);

-- --------------------------------------------------------

--
-- Table structure for table `famille`
--

DROP TABLE IF EXISTS `famille`;
CREATE TABLE IF NOT EXISTS `famille` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `famille`
--

INSERT INTO `famille` (`id`, `libelle`) VALUES
(1, 'légume'),
(3, 'fruit'),
(4, 'viande');

-- --------------------------------------------------------

--
-- Table structure for table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `saison_id` int(11) DEFAULT NULL,
  `type_panier_id` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prix` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_24CC0DF2F965414C` (`saison_id`),
  KEY `IDX_24CC0DF243BBD36C` (`type_panier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `panier`
--

INSERT INTO `panier` (`id`, `saison_id`, `type_panier_id`, `libelle`, `prix`) VALUES
(1, 1, 1, 'Petit panier des prés', 10),
(2, 2, 2, 'Moyen panier des collines', 20),
(3, 1, 3, 'Grand panier des champs', 30);

-- --------------------------------------------------------

--
-- Table structure for table `panier_produit`
--

DROP TABLE IF EXISTS `panier_produit`;
CREATE TABLE IF NOT EXISTS `panier_produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) DEFAULT NULL,
  `panier_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D31F28A6F347EFB` (`produit_id`),
  KEY `IDX_D31F28A6F77D927C` (`panier_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `panier_produit`
--

INSERT INTO `panier_produit` (`id`, `produit_id`, `panier_id`, `quantite`) VALUES
(1, 1, 1, 10),
(2, 3, 1, 1),
(3, 4, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `famille_id` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_29A5EC2797A77B84` (`famille_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `famille_id`, `libelle`) VALUES
(1, 3, 'Pomme'),
(2, 3, 'Fraise'),
(3, 1, 'Cornichon'),
(4, 1, 'Tomate');

-- --------------------------------------------------------

--
-- Table structure for table `saison`
--

DROP TABLE IF EXISTS `saison`;
CREATE TABLE IF NOT EXISTS `saison` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `saison`
--

INSERT INTO `saison` (`id`, `libelle`) VALUES
(1, 'Automne'),
(2, 'Hiver'),
(3, 'Été'),
(4, 'Printemps');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `entrepot_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4B36566072831E97` (`entrepot_id`),
  KEY `IDX_4B365660F347EFB` (`produit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `produit_id`, `quantite`, `entrepot_id`) VALUES
(5, 1, 100, 1),
(6, 2, 100, 1),
(8, 1, 90, 2),
(9, 2, 100, 2),
(10, 3, 99, 2),
(11, 4, 95, 2),
(12, 3, 100, 1),
(13, 4, 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `type_acteur`
--

DROP TABLE IF EXISTS `type_acteur`;
CREATE TABLE IF NOT EXISTS `type_acteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `type_acteur`
--

INSERT INTO `type_acteur` (`id`, `libelle`) VALUES
(1, 'Producteur'),
(2, 'Consommateur');

-- --------------------------------------------------------

--
-- Table structure for table `type_panier`
--

DROP TABLE IF EXISTS `type_panier`;
CREATE TABLE IF NOT EXISTS `type_panier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `type_panier`
--

INSERT INTO `type_panier` (`id`, `libelle`) VALUES
(1, 'Petit'),
(2, 'Moyen'),
(3, 'Grand');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acteur`
--
ALTER TABLE `acteur`
  ADD CONSTRAINT `FK_EAFAD3624DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `adresse` (`id`),
  ADD CONSTRAINT `FK_EAFAD36252AA66E8` FOREIGN KEY (`amap_id`) REFERENCES `amap` (`id`),
  ADD CONSTRAINT `FK_EAFAD3626EA9165A` FOREIGN KEY (`type_acteur_id`) REFERENCES `type_acteur` (`id`);

--
-- Constraints for table `amap`
--
ALTER TABLE `amap`
  ADD CONSTRAINT `FK_CE323CD372831E97` FOREIGN KEY (`entrepot_id`) REFERENCES `entrepot` (`id`),
  ADD CONSTRAINT `FK_CE323CD34DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `adresse` (`id`);

--
-- Constraints for table `contrat`
--
ALTER TABLE `contrat`
  ADD CONSTRAINT `FK_6034999352AA66E8` FOREIGN KEY (`amap_id`) REFERENCES `amap` (`id`),
  ADD CONSTRAINT `FK_60349993865AC8FB` FOREIGN KEY (`consommateur_id`) REFERENCES `acteur` (`id`),
  ADD CONSTRAINT `FK_60349993AB9BB300` FOREIGN KEY (`producteur_id`) REFERENCES `acteur` (`id`),
  ADD CONSTRAINT `FK_60349993F77D927C` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`id`);

--
-- Constraints for table `entrepot`
--
ALTER TABLE `entrepot`
  ADD CONSTRAINT `FK_D805175A4DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `adresse` (`id`);

--
-- Constraints for table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `FK_24CC0DF243BBD36C` FOREIGN KEY (`type_panier_id`) REFERENCES `type_panier` (`id`),
  ADD CONSTRAINT `FK_24CC0DF2F965414C` FOREIGN KEY (`saison_id`) REFERENCES `saison` (`id`);

--
-- Constraints for table `panier_produit`
--
ALTER TABLE `panier_produit`
  ADD CONSTRAINT `FK_D31F28A6F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK_D31F28A6F77D927C` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`id`);

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC2797A77B84` FOREIGN KEY (`famille_id`) REFERENCES `famille` (`id`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `FK_4B36566072831E97` FOREIGN KEY (`entrepot_id`) REFERENCES `entrepot` (`id`),
  ADD CONSTRAINT `FK_4B365660F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
