-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Nov 2018 um 16:03
-- Server-Version: 10.1.37-MariaDB
-- PHP-Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `larifari`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `member`
--

CREATE TABLE `member` (
  `nickname` varchar(32) NOT NULL,
  `lastName` varchar(64) NOT NULL,
  `firstName` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL DEFAULT '',
  `studyPath` varchar(128) DEFAULT NULL,
  `description` text,
  `startsem` varchar(16) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `member`
--

INSERT INTO `member` (`nickname`, `lastName`, `firstName`, `password`, `studyPath`, `description`, `startsem`, `admin`) VALUES
('atran', 'Tran', 'Andy', '', NULL, NULL, NULL, 1),
('hmertens', 'Mertens', 'Hannah', '', 'B. Sc. Informatik', NULL, NULL, 1),
('hsteidl', 'Steidl', 'Hendrik', '123', 'Informatik', NULL, '2016.2', 0),
('lverscht', 'Verscht', 'Lena', '', 'Informatik', NULL, 'WS 16/17', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `sender` varchar(32) NOT NULL,
  `receiver` varchar(32) NOT NULL,
  `message` text NOT NULL,
  `timestmp` bigint(20) NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `content` text,
  `timestmp` bigint(20) NOT NULL,
  `author` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `news`
--

INSERT INTO `news` (`content`, `timestmp`, `author`) VALUES
('Dies ist eine Information!', 1543330099, 'hsteidl'),
('Hallo, ich bin noch eine Info!', 1543330100, 'lverscht');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offer`
--

CREATE TABLE `offer` (
  `oID` int(11) NOT NULL,
  `title` varchar(64) NOT NULL,
  `author` varchar(128) NOT NULL,
  `offerer` varchar(32) NOT NULL,
  `offer_state` int(11) NOT NULL,
  `item_state` varchar(32) DEFAULT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `description` text,
  `picture` varchar(256) DEFAULT NULL,
  `isbn` varchar(32) DEFAULT NULL,
  `edition` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `offer`
--

INSERT INTO `offer` (`oID`, `title`, `author`, `offerer`, `offer_state`, `item_state`, `price`, `description`, `picture`, `isbn`, `edition`) VALUES
(1, 'AfI-Script 16/17', 'Walcher <3', 'lverscht', 1, 'verbrannt', '99.00', 'Diesen tollen Aschehaufen brauche ich nicht mehr.', NULL, 'none', '3.'),
(2, 'Ein Buch', 'von einem Autor', 'atran', 2, 'gebraucht', '10.00', 'Das Buch ist cool.\r\n\r\n{In Bearbeitung}', NULL, '3-979-128-125', '1. ed'),
(3, 'Stocha Formelsammlung', 'Stocha Prof', 'hsteidl', 0, 'neu', '9.99', 'Hab nicht gelernt, das Buch ist über.', NULL, NULL, '6. Auflage'),
(4, 'Mein Tagebuch', 'Hannah Mertens', 'hmertens', 1, 'Wie neu', '1.99', 'Dies ist mein Tagebuch :)', NULL, 'keine', 'einzige');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offer_module`
--

CREATE TABLE `offer_module` (
  `module` varchar(64) NOT NULL,
  `offer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `offer_module`
--

INSERT INTO `offer_module` (`module`, `offer`) VALUES
('Analysis für Informatiker', 1),
('Ein Modul', 2),
('Stocha für andere Studiengänge', 3),
('Stocha für Info', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offer_state`
--

CREATE TABLE `offer_state` (
  `sID` int(11) NOT NULL,
  `state` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `offer_state`
--

INSERT INTO `offer_state` (`sID`, `state`) VALUES
(0, 'geschlossen'),
(1, 'offen'),
(2, 'gesperrt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `offer_studypath`
--

CREATE TABLE `offer_studypath` (
  `study_path` varchar(64) NOT NULL,
  `offer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `offer_studypath`
--

INSERT INTO `offer_studypath` (`study_path`, `offer`) VALUES
('B. Sc. Informatik', 1),
('Ein Studiengang', 2),
('Informatik', 1),
('Informatik', 3),
('Keiner', 4),
('Mathematik', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `watch_list`
--

CREATE TABLE `watch_list` (
  `nickname` varchar(32) NOT NULL,
  `offer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `watch_list`
--

INSERT INTO `watch_list` (`nickname`, `offer`) VALUES
('atran', 1),
('hsteidl', 4);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`nickname`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`sender`,`receiver`,`timestmp`),
  ADD KEY `receiver` (`receiver`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`timestmp`,`author`),
  ADD KEY `author` (`author`);

--
-- Indizes für die Tabelle `offer`
--
ALTER TABLE `offer`
  ADD PRIMARY KEY (`oID`),
  ADD KEY `offerer` (`offerer`),
  ADD KEY `offer_state` (`offer_state`);

--
-- Indizes für die Tabelle `offer_module`
--
ALTER TABLE `offer_module`
  ADD PRIMARY KEY (`module`,`offer`),
  ADD KEY `offer` (`offer`);

--
-- Indizes für die Tabelle `offer_state`
--
ALTER TABLE `offer_state`
  ADD PRIMARY KEY (`sID`);

--
-- Indizes für die Tabelle `offer_studypath`
--
ALTER TABLE `offer_studypath`
  ADD PRIMARY KEY (`study_path`,`offer`),
  ADD KEY `offer` (`offer`);

--
-- Indizes für die Tabelle `watch_list`
--
ALTER TABLE `watch_list`
  ADD PRIMARY KEY (`nickname`,`offer`),
  ADD KEY `offer` (`offer`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `offer`
--
ALTER TABLE `offer`
  MODIFY `oID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `offer_state`
--
ALTER TABLE `offer_state`
  MODIFY `sID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `member` (`nickname`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `member` (`nickname`);

--
-- Constraints der Tabelle `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author`) REFERENCES `member` (`nickname`);

--
-- Constraints der Tabelle `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`offerer`) REFERENCES `member` (`nickname`),
  ADD CONSTRAINT `offer_ibfk_2` FOREIGN KEY (`offer_state`) REFERENCES `offer_state` (`sID`);

--
-- Constraints der Tabelle `offer_module`
--
ALTER TABLE `offer_module`
  ADD CONSTRAINT `offer_module_ibfk_1` FOREIGN KEY (`offer`) REFERENCES `offer` (`oID`);

--
-- Constraints der Tabelle `offer_studypath`
--
ALTER TABLE `offer_studypath`
  ADD CONSTRAINT `offer_studypath_ibfk_1` FOREIGN KEY (`offer`) REFERENCES `offer` (`oID`);

--
-- Constraints der Tabelle `watch_list`
--
ALTER TABLE `watch_list`
  ADD CONSTRAINT `watch_list_ibfk_1` FOREIGN KEY (`offer`) REFERENCES `offer` (`oID`),
  ADD CONSTRAINT `watch_list_ibfk_2` FOREIGN KEY (`nickname`) REFERENCES `member` (`nickname`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
