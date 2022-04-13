# LoginAndCreateAccount

This is a basic Login- and Register-Site. Please note that all of the outputs that are seen by the user, are in german. 
If you want to use this code, make sure your Database is built like this:

````
DROP DATABASE IF EXISTS `project`;

CREATE DATABASE `project`;

/* Table for Users */

CREATE TABLE `project`.`users` ( 
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), 
  UNIQUE (`id`),
  UNIQUE (`email`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* Table for IP-Adresses, to prevent bruteforce attacks */

CREATE TABLE `project`.`loginAttempts` ( 
  `ip` VARCHAR(20) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
````
