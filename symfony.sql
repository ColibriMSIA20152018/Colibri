-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 11 Avril 2016 à 23:52
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `symfony`
--


--
-- Vider la table avant d'insérer `type_acteur`
--

-- TRUNCATE TABLE `type_acteur`;
--
-- Contenu de la table `type_acteur`
--

INSERT INTO `type_acteur` (`id`, `libelle`) VALUES
(1, 'Producteur'),
(2, 'Consommateur');

--
-- TRUNCATE TABLE `type_panier`;
--

--
-- Contenu de la table `type_panier`
--

INSERT INTO `type_panier` (`id`, `libelle`) VALUES
(1, 'Petit'),
(2, 'Moyen'),
(3, 'Grand');

--
-- Vider la table avant d'insérer `famille`
--

-- TRUNCATE TABLE `famille`;
--
-- Contenu de la table `famille`
--

INSERT INTO `famille` (`id`, `libelle`) VALUES
(1, 'légume'),
(3, 'fruit'),
(4, 'viande');


--
-- Vider la table avant d'insérer `saison`
--

-- TRUNCATE TABLE `saison`;
--
-- Contenu de la table `saison`
--

INSERT INTO `saison` (`id`, `libelle`) VALUES
(1, 'Automne'),
(2, 'Hiver'),
(3, 'Été'),
(4, 'Printemps');

--
-- Vider la table avant d'insérer `produit`
--

-- TRUNCATE TABLE `produit`;
--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`id`, `libelle`, `famille_id`) VALUES
(1, 'Pomme', 3),
(2, 'Fraise', 3),
(3, 'Cornichon', 1),
(4, 'Tomate', 1);

--
-- Vider la table avant d'insérer `acteur`
--

-- TRUNCATE TABLE `acteur`;
--
-- Contenu de la table `acteur`
--

INSERT INTO `acteur` (`id`, `nom`, `prenom`, `date_naissance`, `type_acteur_id`) VALUES
(1, 'BERGER', 'Fabien', '1994-05-20', 2),
(2, 'BENI', 'Céline', '1966-01-01', 1);


--
-- Vider la table avant d'insérer `panier`
--

-- TRUNCATE TABLE `panier`;
--
-- Contenu de la table `panier`
--

INSERT INTO `panier` (`id`, `libelle`, `saison_id`, `type_panier_id`, `prix`) VALUES
(1, 'Petit panier des prés', 1, 1, 10),
(2, 'Moyen panier des collines', 2, 2, 20),
(3, 'Grand panier des champs', 1, 3, 30);

--
-- Vider la table avant d'insérer `panier_produit`
--

-- TRUNCATE TABLE `panier_produit`;
--
-- Contenu de la table `panier_produit`
--

INSERT INTO `panier_produit` (`id`, `produit_id`, `panier_id`, `quantite`) VALUES
(1, 1, 1, 10),
(2, 3, 1, 1),
(3, 4, 1, 5);

--
-- Vider la table avant d'insérer `contrat`
--

-- TRUNCATE TABLE `contrat`;
--
-- Contenu de la table `contrat`
--

INSERT INTO `contrat` (`id`, `consommateur_id`, `producteur_id`, `panier_id`) VALUES
(1, 1, 2, 2);


--
-- Vider la table avant d'insérer `stock`
--

-- TRUNCATE TABLE `stock`;
--
-- Contenu de la table `stock`
--

INSERT INTO `stock` (`id`, `produit_id`, `quantite`) VALUES
(1, 1, 100),
(2, 2, 50),
(3, 3, 10),
(4, 4, 30);



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
