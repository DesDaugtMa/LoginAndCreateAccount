# LoginAndCreateAccount

This is a basic Login- and Register-Site. If you want use this code, make sure your Database is built like this:

````
DROP DATABASE IF EXISTS `bodycount`;

CREATE DATABASE `bodycount`;

/* Table for Users */

CREATE TABLE `bodycount`.`users` ( 
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

CREATE TABLE `bodycount`.`loginAttempts` ( 
  `ip` VARCHAR(20) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
````
