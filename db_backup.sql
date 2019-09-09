-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 10 Cze 2019, 04:29
-- Wersja serwera: 10.1.38-MariaDB
-- Wersja PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `inzynierka`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `devicegroups`
--

CREATE TABLE `devicegroups` (
  `idDeviceGroup` int(11) NOT NULL,
  `idFamily` int(11) NOT NULL,
  `deviceGroupName` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `deviceGroupAddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `temperatureDevice` varchar(8) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `devicemeasurements`
--

CREATE TABLE `devicemeasurements` (
  `idDeviceMeasurement` int(11) NOT NULL,
  `idDevice` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `idMeasurementType` int(11) NOT NULL,
  `deviceMeasurementValue` float NOT NULL,
  `deviceMeasurementDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `devices`
--

CREATE TABLE `devices` (
  `idDevice` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `deviceName` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `idDeviceType` int(11) NOT NULL,
  `idDeviceGroup` int(11) NOT NULL,
  `displayOrder` int(11) NOT NULL,
  `deviceActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `devicetypes`
--

CREATE TABLE `devicetypes` (
  `idDeviceType` int(11) NOT NULL,
  `deviceTypeName` varchar(32) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `devicetypes`
--

INSERT INTO `devicetypes` (`idDeviceType`, `deviceTypeName`) VALUES
(1, 'singleSocket'),
(2, 'dualSocket'),
(3, 'x4relay'),
(4, 'weatherStation'),
(5, 'pirSensor'),
(6, 'temperatureSenso'),
(7, 'zraszacz'),
(8, 'light'),
(9, 'blind'),
(10, 'alarmStation');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `families`
--

CREATE TABLE `families` (
  `idFamily` int(11) NOT NULL,
  `familyName` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `familyAddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `measurementtypes`
--

CREATE TABLE `measurementtypes` (
  `idMeasurementType` int(11) NOT NULL,
  `measurementTypeName` varchar(32) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `measurementtypes`
--

INSERT INTO `measurementtypes` (`idMeasurementType`, `measurementTypeName`) VALUES
(1, 'temperature'),
(2, 'lux'),
(3, 'humidity'),
(4, 'preassure'),
(5, 'rl1'),
(6, 'rl2'),
(7, 'rl3'),
(8, 'rl4'),
(9, 'pir'),
(10, 'blindState'),
(11, 'lightState'),
(12, 'floodState'),
(13, 'fireState'),
(14, 'smokeState');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sceneactions`
--

CREATE TABLE `sceneactions` (
  `idSceneAction` int(11) NOT NULL,
  `idScene` int(11) NOT NULL,
  `idDevice` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `idMeasurementType` int(11) NOT NULL,
  `measurementValue` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `sceneconditions`
--

CREATE TABLE `sceneconditions` (
  `idSceneCondition` int(11) NOT NULL,
  `idScene` int(11) NOT NULL,
  `idDevice` varchar(8) COLLATE utf8_polish_ci NOT NULL,
  `idMeasurementType` int(11) NOT NULL,
  `conditionSign` tinyint(4) NOT NULL COMMENT '4 znaki: <, >, = na 2 bitach zapisze',
  `measurementValue` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `scenegroups`
--

CREATE TABLE `scenegroups` (
  `idSceneGroup` int(11) NOT NULL,
  `idFamily` int(11) NOT NULL,
  `sceneGroupName` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `sceneGroupAddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `scenes`
--

CREATE TABLE `scenes` (
  `idScene` int(11) NOT NULL,
  `idSceneGroup` int(11) NOT NULL,
  `sceneName` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `sceneAddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sceneActive` tinyint(1) NOT NULL,
  `startHour` time NOT NULL,
  `endHour` time NOT NULL,
  `linkCondition` varchar(8) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `userloginhistories`
--

CREATE TABLE `userloginhistories` (
  `idUserLoginHistory` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `userLoginDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userLoginIpAddress` varbinary(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `userpermissions`
--

CREATE TABLE `userpermissions` (
  `idUser` int(11) NOT NULL,
  `deviceGroupPermission` tinyint(1) NOT NULL,
  `devicePermission` tinyint(1) NOT NULL,
  `scenePermission` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `idUser` int(11) NOT NULL,
  `userLogin` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `userPassword` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `userAddDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userEmail` varchar(64) COLLATE utf8_polish_ci NOT NULL,
  `userActive` tinyint(1) NOT NULL,
  `idFamily` int(11) NOT NULL,
  `userType` tinyint(1) NOT NULL,
  `userActivated` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `devicegroups`
--
ALTER TABLE `devicegroups`
  ADD PRIMARY KEY (`idDeviceGroup`),
  ADD KEY `idFamily` (`idFamily`),
  ADD KEY `temperatureDevice` (`temperatureDevice`);

--
-- Indeksy dla tabeli `devicemeasurements`
--
ALTER TABLE `devicemeasurements`
  ADD PRIMARY KEY (`idDeviceMeasurement`),
  ADD KEY `idDevice` (`idDevice`),
  ADD KEY `idMeasurementType` (`idMeasurementType`);

--
-- Indeksy dla tabeli `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`idDevice`),
  ADD KEY `idDeviceType` (`idDeviceType`),
  ADD KEY `idDeviceGroup` (`idDeviceGroup`);

--
-- Indeksy dla tabeli `devicetypes`
--
ALTER TABLE `devicetypes`
  ADD PRIMARY KEY (`idDeviceType`);

--
-- Indeksy dla tabeli `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`idFamily`);

--
-- Indeksy dla tabeli `measurementtypes`
--
ALTER TABLE `measurementtypes`
  ADD PRIMARY KEY (`idMeasurementType`);

--
-- Indeksy dla tabeli `sceneactions`
--
ALTER TABLE `sceneactions`
  ADD PRIMARY KEY (`idSceneAction`),
  ADD KEY `idScene` (`idScene`),
  ADD KEY `idDevice` (`idDevice`),
  ADD KEY `idMeasurementType` (`idMeasurementType`);

--
-- Indeksy dla tabeli `sceneconditions`
--
ALTER TABLE `sceneconditions`
  ADD PRIMARY KEY (`idSceneCondition`),
  ADD KEY `idScene` (`idScene`),
  ADD KEY `idDevice` (`idDevice`),
  ADD KEY `idMeasurementType` (`idMeasurementType`);

--
-- Indeksy dla tabeli `scenegroups`
--
ALTER TABLE `scenegroups`
  ADD PRIMARY KEY (`idSceneGroup`),
  ADD KEY `idFamily` (`idFamily`);

--
-- Indeksy dla tabeli `scenes`
--
ALTER TABLE `scenes`
  ADD PRIMARY KEY (`idScene`),
  ADD KEY `idSceneGroup` (`idSceneGroup`);

--
-- Indeksy dla tabeli `userloginhistories`
--
ALTER TABLE `userloginhistories`
  ADD PRIMARY KEY (`idUserLoginHistory`),
  ADD KEY `idUser` (`idUser`);

--
-- Indeksy dla tabeli `userpermissions`
--
ALTER TABLE `userpermissions`
  ADD PRIMARY KEY (`idUser`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `userLogin` (`userLogin`),
  ADD UNIQUE KEY `userEmail` (`userEmail`),
  ADD KEY `idFamily` (`idFamily`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `devicegroups`
--
ALTER TABLE `devicegroups`
  MODIFY `idDeviceGroup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `devicemeasurements`
--
ALTER TABLE `devicemeasurements`
  MODIFY `idDeviceMeasurement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `devicetypes`
--
ALTER TABLE `devicetypes`
  MODIFY `idDeviceType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `families`
--
ALTER TABLE `families`
  MODIFY `idFamily` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `measurementtypes`
--
ALTER TABLE `measurementtypes`
  MODIFY `idMeasurementType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `sceneactions`
--
ALTER TABLE `sceneactions`
  MODIFY `idSceneAction` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `sceneconditions`
--
ALTER TABLE `sceneconditions`
  MODIFY `idSceneCondition` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `scenegroups`
--
ALTER TABLE `scenegroups`
  MODIFY `idSceneGroup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `userloginhistories`
--
ALTER TABLE `userloginhistories`
  MODIFY `idUserLoginHistory` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `devicegroups`
--
ALTER TABLE `devicegroups`
  ADD CONSTRAINT `devicegroups_ibfk_1` FOREIGN KEY (`idFamily`) REFERENCES `families` (`idFamily`);

--
-- Ograniczenia dla tabeli `devicemeasurements`
--
ALTER TABLE `devicemeasurements`
  ADD CONSTRAINT `devicemeasurements_ibfk_1` FOREIGN KEY (`idDevice`) REFERENCES `devices` (`idDevice`) ON DELETE CASCADE,
  ADD CONSTRAINT `devicemeasurements_ibfk_2` FOREIGN KEY (`idMeasurementType`) REFERENCES `measurementtypes` (`idMeasurementType`);

--
-- Ograniczenia dla tabeli `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`idDeviceGroup`) REFERENCES `devicegroups` (`idDeviceGroup`),
  ADD CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`idDeviceType`) REFERENCES `devicetypes` (`idDeviceType`);

--
-- Ograniczenia dla tabeli `sceneactions`
--
ALTER TABLE `sceneactions`
  ADD CONSTRAINT `sceneactions_ibfk_1` FOREIGN KEY (`idScene`) REFERENCES `scenes` (`idScene`),
  ADD CONSTRAINT `sceneactions_ibfk_2` FOREIGN KEY (`idDevice`) REFERENCES `devices` (`idDevice`),
  ADD CONSTRAINT `sceneactions_ibfk_3` FOREIGN KEY (`idMeasurementType`) REFERENCES `measurementtypes` (`idMeasurementType`);

--
-- Ograniczenia dla tabeli `sceneconditions`
--
ALTER TABLE `sceneconditions`
  ADD CONSTRAINT `sceneconditions_ibfk_1` FOREIGN KEY (`idScene`) REFERENCES `scenes` (`idScene`),
  ADD CONSTRAINT `sceneconditions_ibfk_2` FOREIGN KEY (`idDevice`) REFERENCES `devices` (`idDevice`),
  ADD CONSTRAINT `sceneconditions_ibfk_3` FOREIGN KEY (`idMeasurementType`) REFERENCES `measurementtypes` (`idMeasurementType`);

--
-- Ograniczenia dla tabeli `scenegroups`
--
ALTER TABLE `scenegroups`
  ADD CONSTRAINT `scenegroups_ibfk_1` FOREIGN KEY (`idFamily`) REFERENCES `families` (`idFamily`);

--
-- Ograniczenia dla tabeli `scenes`
--
ALTER TABLE `scenes`
  ADD CONSTRAINT `scenes_ibfk_1` FOREIGN KEY (`idSceneGroup`) REFERENCES `scenegroups` (`idSceneGroup`);

--
-- Ograniczenia dla tabeli `userloginhistories`
--
ALTER TABLE `userloginhistories`
  ADD CONSTRAINT `userloginhistories_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Ograniczenia dla tabeli `userpermissions`
--
ALTER TABLE `userpermissions`
  ADD CONSTRAINT `userpermissions_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`);

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`idFamily`) REFERENCES `families` (`idFamily`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
