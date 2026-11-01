CREATE DATABASE IF NOT EXISTS toms3;
USE toms3;

CREATE TABLE IF NOT EXISTS roles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    permission_level INT NOT NULL
);

CREATE TABLE IF NOT EXISTS accounts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    FOREIGN KEY(role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS urls(
    id INT AUTO_INCREMENT PRIMARY KEY,
    url TEXT NOT NULL,
    alias VARCHAR(255) UNIQUE NOT NULL,
    expires_on DATETIME,
    associated_to INT NOT NULL,
    token VARCHAR(255),
    FOREIGN KEY(associated_to) REFERENCES accounts(id)
);

INSERT INTO roles(name, permission_level) VALUES ("User", 1), ("Admin", 2), ("Super-admin", 3);

SET GLOBAL event_scheduler = ON;
CREATE EVENT del_urls
ON SCHEDULE EVERY 1 WEEK
STARTS CURRENT_TIMESTAMP
DO
    DELETE FROM urls WHERE expires_on IS NOT NULL AND expires_on <= NOW();