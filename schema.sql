START TRANSACTION;
CREATE TABLE `teachers` (
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `abbreviation` varchar(10) NOT NULL,
  `email` varchar(50) DEFAULT NULL
);

INSERT INTO `teachers` (`last_name`, `first_name`, `abbreviation`, `email`) VALUES
('Smith', 'John', 'Js', 'john.smith@acme.com'),
('Smith', 'Tom', 'Ts', 'jane.smith@acme.com');

ALTER TABLE `teachers`
  ADD PRIMARY KEY (`abbreviation`);
COMMIT;
