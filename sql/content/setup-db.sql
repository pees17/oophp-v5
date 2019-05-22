--
-- Setup for the article:
-- https://dbwebb.se/kunskap/kom-igang-med-php-pdo-och-mysql
--

--
-- Create the database with a test user
--
DROP USER IF EXISTS 'user'@localhost;
CREATE USER 'user'@localhost
IDENTIFIED
BY 'pass'
;

CREATE DATABASE IF NOT EXISTS oophp;
GRANT ALL ON oophp.* TO user@localhost;

USE oophp;
