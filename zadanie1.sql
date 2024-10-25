CREATE TABLE `zadanie` (
  `id_form` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `surname` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `phone` int(9) NOT NULL,
  `client_no` varchar(12) COLLATE utf8_polish_ci NOT NULL,
  `choose` int(11) NOT NULL,
  `agreement1` tinyint(1) NOT NULL,
  `agreement2` tinyint(1) NOT NULL,
  `user_info` text COLLATE utf8_polish_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `zadanie` ADD PRIMARY KEY (`id_form`);

ALTER TABLE `zadanie` MODIFY `id_form` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `zadanie` 
ADD COLUMN `agreement3` TINYINT(1) NULL DEFAULT NULL AFTER `agreement2`,
CHANGE COLUMN `phone` `phone` INT NULL DEFAULT NULL ;

ALTER TABLE `zadanie` 
ADD COLUMN `account_number` VARCHAR(28) NULL DEFAULT NULL AFTER `client_no`;

ALTER TABLE `zadanie` 
CHANGE COLUMN `name` `name` VARCHAR(30) NOT NULL ,
CHANGE COLUMN `surname` `surname` VARCHAR(40) CHARACTER SET 'utf8mb3' NOT NULL ,
CHANGE COLUMN `email` `email` VARCHAR(80) CHARACTER SET 'utf8mb3' NOT NULL ;
