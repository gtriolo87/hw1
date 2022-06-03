-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20220601.866861edac
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 04, 2022 alle 01:31
-- Versione del server: 10.4.24-MariaDB
-- Versione PHP: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbhw1`
--
CREATE DATABASE IF NOT EXISTS `dbhw1` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dbhw1`;

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(10) NOT NULL,
  `canLike` tinyint(1) NOT NULL DEFAULT 1,
  `canAddJob` tinyint(1) NOT NULL DEFAULT 0,
  `canEditJob` tinyint(1) NOT NULL DEFAULT 0,
  `canAddTask` tinyint(1) NOT NULL DEFAULT 0,
  `canEditTask` tinyint(1) NOT NULL DEFAULT 0,
  `canWorkTask` tinyint(1) NOT NULL DEFAULT 0,
  `canManageUsers` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`id`, `name`, `canLike`, `canAddJob`, `canEditJob`, `canAddTask`, `canEditTask`, `canWorkTask`, `canManageUsers`) VALUES
(1, 'Visitatore', 1, 0, 0, 0, 0, 0, 0),
(2, 'Admin', 1, 1, 1, 1, 1, 1, 1),
(3, 'Manager', 1, 1, 1, 1, 1, 1, 0),
(4, 'Operatore', 1, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `device` varchar(255) NOT NULL,
  `endingYear` year(4) DEFAULT NULL,
  `description` text NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `keywords` text DEFAULT NULL,
  `hasVideo` tinyint(1) NOT NULL DEFAULT 0,
  `jobEnded` tinyint(1) NOT NULL DEFAULT 0,
  `nLikes` int(11) NOT NULL DEFAULT 0,
  `nTasks` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL DEFAULT 'images/jobs/defaultJob.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `customer`, `device`, `endingYear`, `description`, `latitude`, `longitude`, `keywords`, `hasVideo`, `jobEnded`, `nLikes`, `nTasks`, `image`) VALUES
(6, 'Depuratore Punta Gradelle', 'Veolia Water Technologies Italia', 'SE PES (PLC Quantum)', 2017, 'Sviluppo su sistema DCS Ibrido (PES) dell\'impianto di depurazione di Punta Gradelle, situato a Vico Equense. L\'impianto è sviluppato all\'interno di una galleria ed è costituito da una prima area di grigliatura, sollevamento e dissabbiatura, una seconda area di ossidazione e filtrazione a membrane, una terza area di disinfezione UV.', 40.6584, 14.4178, 'punta gradelle depuratore veolia sorrento vico equense', 1, 1, 2, 0, 'images/jobs/job1.jpg'),
(8, 'Potabilizzatore Bardonecchia', 'Suez WTAS Italy S.r.l.', 'SE M580 Hot-StandBy', 2020, 'Sviluppo PLC del sistema di automazione e controllo del Potabilizzatore di Bardonecchia. Il potabilizzatore è costituito da una sezione di sollevamento, la grigliatura iniziale, la filtrazione a membrane, l\'ozonizzazione, la filtrazione a carbone e l\'accumulo. Rifornisce con acqua potabile il Comune di Bardonecchia più tutti i comuni della Val di Susa.', 45.0791, 6.70805, 'bardonecchia potabilizzatore suez smat Val susa valle', 0, 1, 4, 0, 'images/jobs/job3.jpg'),
(9, 'Revamping Potabilizzatore Sambuca', 'Siciliacque spa', 'SE GeoSCADA Expert', 2021, 'Revamping del sistema SCADA dell\'impianto di potabilizzazione di Sambuca di Sicilia, gestito da Siciliacque. Lo SCADA è stato realizzato in doppia grafica 2D e 3D. La parte 3D è stata preceduta da rilievo in campo tramite aerofogrammetria con l\'utilizzo di un drone. Le nuvole di punti ottenute sono state usate per lo sviluppo dei modelli 3D fotorealistici in seguito renderizzati ed elaborati per ottenere dei sinottici SCADA animati in 3D.', 37.6718, 13.1066, '3D sambuca potabilizzatore siciliacque', 0, 1, 1, 0, 'images/jobs/job4.png'),
(10, 'Turbina San Giovannello', 'WeCons - Siciliacque spa', 'SE M340 - GeoSCADA Expert', 2021, 'Sviluppo della logica PLC della microturbina installata in linea nell\'impianto di Trapani. La microturbina in linea produce una potenza istantanea di circa 40kW sfruttando la differenza di pressione tra monte e valle al nodo idraulico. ', 38.0195, 12.5675, 'siciliacque wecons turbina san giovannello turbine idroelettrico', 0, 1, 2, 0, 'images/jobs/job5.jpg'),
(11, 'Impianto Farmaceutico TEVA Sicor', 'AS per Teva Pharmaceutical Industries', 'Sistema DCS Emerson Delta-V', 2022, 'Sviluppo su sistema DCS delle nuove centrifughe per la produzione dei principi attivi. Sviluppo interfacciamento su sistema Delta-V con un PLC Siemens S7-300 e un Siemens S7-1500 tramite protocollo Profibus-DP. Sviluppo intefaccia grafica su DCS e logiche a contorno per la gestione delle acque di scarico delle centrifughe e l\'azionamento delle pompe di dosaggio.', 45.537, 9.05425, 'farmaceutico teva as automazione sistemi dcs emerson', 0, 1, 3, 0, 'images/jobs/job6.png'),
(13, 'Depuratore Amalfi', 'Veolia Water Technologies Italia', 'SE PES (SE M580 Hot-StandBy + SE M340)', 2018, 'Sviluppo su sistema DCS Ibrido (PES) dell\'impianto di depurazione di Amalfi. L\'impianto è costituito da 4 sollevamenti remoti che rilanciano l\'acqua da trattare all\'impianto. Esso è costituito da una prima parte di grigliatura e dissabbiatura, seguiti da un\'equalizzazione che rilancia parte del liquame alle vasce di ossidazione situate all\'interno di una galleria. Dopo l\'ossidazione avviene la filtrazione a membrane e l\'acqua depurata viene rilasciata per caduta in mare.', 40.6327, 14.5945, 'amalfi depuratore veolia pes schneider', 0, 1, 1, 0, 'images/jobs/job2.jpg'),
(14, 'Depuratore Sant\'Antonino', 'Schneider Electric / Alfa spa', 'PLC SE M580 Hot-StandBy', 2000, 'Revamping del depuratore di Sant\'Antonino situato a Lonate Pozzolo. L\'impianto è attualmente costituito da 14 PLC, misti tra SE Premium e SE M340, che saranno sostituiti da un unico PLC Ridondato M580. Il lavoro riguarderà quindi il revamping delle logiche d\'impianto e dello SCADA. L\'ambiente di Sviluppo è Process Expert', 45.5761, 8.75642, 'depuratore sant antonino schneider', 0, 0, 2, 0, 'images/jobs/defaultJob.png');

-- --------------------------------------------------------

--
-- Struttura della tabella `likes`
--

CREATE TABLE `likes` (
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `likes`
--

INSERT INTO `likes` (`job_id`, `user_id`) VALUES
(6, 2),
(6, 6),
(8, 1),
(8, 2),
(8, 6),
(8, 9),
(9, 1),
(10, 2),
(10, 6),
(11, 1),
(11, 2),
(11, 6),
(13, 6),
(14, 2),
(14, 8);

--
-- Trigger `likes`
--
DELIMITER $$
CREATE TRIGGER `newLike_trigger` AFTER INSERT ON `likes` FOR EACH ROW UPDATE jobs
SET nLikes=nLikes+1
where id=new.job_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remLike_trigger` AFTER DELETE ON `likes` FOR EACH ROW UPDATE jobs
SET nLikes=nLikes-1
where id=old.job_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `addingDate` datetime NOT NULL,
  `taskTitle` varchar(127) NOT NULL,
  `request` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `endingDate` datetime DEFAULT NULL,
  `note` text DEFAULT NULL,
  `isWorked` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `tasks`
--
DELIMITER $$
CREATE TRIGGER `newTask_trigger` AFTER INSERT ON `tasks` FOR EACH ROW UPDATE jobs
SET nTasks=nTasks+1
WHERE id=new.job_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remTask_trigger` AFTER DELETE ON `tasks` FOR EACH ROW UPDATE jobs
SET nTasks=nTasks-1
WHERE id=old.job_id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `nome` varchar(25) NOT NULL,
  `cognome` varchar(25) NOT NULL,
  `email` varchar(127) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nome`, `cognome`, `email`, `group_id`) VALUES
(1, 'guest', '123456', 'Utente', 'Visitatore', 'visitatore@tim.it', 1),
(2, 'Administrator', 'A123456', 'Utente', 'Amministratore', 'admin@tim.it', 2),
(3, 'uManager', '654321M', 'Utente', 'Manager', 'manager@tim.it', 3),
(4, 'uOperatore', 'operatore01!', 'Utente', 'Operatore', 'operatore@tim.it', 4),
(5, 'gtriolo', 'tRIOLO', 'Giuseppe', 'Triolo Puleio', 'gtriolo@tim.it', 4),
(6, 'ema', 'nuela', 'Emanuela', 'Paterno', 'pater@tim.it', 3),
(7, 'prova', 'prova01', 'Prova', 'Utente', 'prova@ciao.com', 1),
(8, 'prova2', 'NuovaPass', 'Prova', 'due', 'prova@tiscali.it', 1),
(9, 'prova3', 'OldPass', 'prova', 'tre', 'tre.prova@wind.it', 1);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `v_user_permissions`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `v_user_permissions` (
`id` int(11)
,`username` varchar(15)
,`canLike` tinyint(1)
,`canAddJob` tinyint(1)
,`canEditJob` tinyint(1)
,`canAddTask` tinyint(1)
,`canEditTask` tinyint(1)
,`canWorkTask` tinyint(1)
,`canManageUsers` tinyint(1)
);

-- --------------------------------------------------------

--
-- Struttura per vista `v_user_permissions`
--
DROP TABLE IF EXISTS `v_user_permissions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_permissions`  AS SELECT `a`.`id` AS `id`, `a`.`username` AS `username`, `b`.`canLike` AS `canLike`, `b`.`canAddJob` AS `canAddJob`, `b`.`canEditJob` AS `canEditJob`, `b`.`canAddTask` AS `canAddTask`, `b`.`canEditTask` AS `canEditTask`, `b`.`canWorkTask` AS `canWorkTask`, `b`.`canManageUsers` AS `canManageUsers` FROM (`users` `a` join `groups` `b` on(`a`.`group_id` = `b`.`id`))  ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `likes`
--
ALTER TABLE `likes`
  ADD UNIQUE KEY `like` (`job_id`,`user_id`) USING BTREE,
  ADD KEY `job_id` (`job_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indici per le tabelle `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `manager_id` (`manager_id`) USING BTREE,
  ADD KEY `job_id` (`job_id`) USING BTREE;

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `group_id` (`group_id`) USING BTREE;

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_3` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Limiti per la tabella `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



