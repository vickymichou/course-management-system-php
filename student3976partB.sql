-- THIS IS MY DATABASE FOR MY PHP WEBSITE

-- Database: `student3976`
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- Creating table structure for `announcements`
CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Creating table structure for `homework`
CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `goals` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `required_files` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Creating table structure for `documents`
CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Creating table structure for `users`
CREATE TABLE `users` (
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `loginame` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Student','Tutor') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--  Inserting data for `users`
INSERT INTO `users` (`firstName`, `lastName`, `loginame`, `password`, `role`) VALUES
('Nikos', 'Mulotos', 'nikmulo@csd.auth.gr', '12345m', 'Student'),
('Giannis', 'Ladopoulos', 'gianlado@csd.auth.gr', '12345l', 'Student'),
('Dimitris', 'Vlaxos', 'dimvlax@gmail.com', '12345v', 'Tutor'),
('Vasilis', 'Chatzipavlidis', 'billchatzi@gmail.com', '12345c', 'Tutor');


-- Defines the 'id' field as the primary key for the 'users' table
ALTER TABLE `users`
  ADD PRIMARY KEY (`loginame`);
COMMIT;

-- Defines the 'id' field as the primary key for the 'announcements' table
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

-- Modifies the 'id' field in the 'announcements' table 
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- Defines the 'id' field as the primary key for the 'homework' table
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`);

-- Modifies the 'id' field in the 'homework' table 
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- Defines the 'id' field as the primary key for the 'documents' table
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

-- Modifies the 'id' field in the 'documents' table 
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


--COMMIT;
