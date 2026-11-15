CREATE USER IF NOT EXISTS 'Estudiante'@'%' IDENTIFIED BY 'Developer';

DROP DATABASE IF EXISTS crud_usuarios;
CREATE DATABASE crud_usuarios
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON crud_usuarios.* TO 'Estudiante'@'%';
FLUSH PRIVILEGES;

USE crud_usuarios;

CREATE TABLE users (
                       id CHAR(36) NOT NULL PRIMARY KEY,
                       name VARCHAR(100) NOT NULL,
                       email VARCHAR(150) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       role VARCHAR(20) NOT NULL DEFAULT 'MEMBER',
                       status VARCHAR(20) NOT NULL DEFAULT 'PENDING',
                       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE equipos_futbol (
                                id CHAR(36) NOT NULL PRIMARY KEY,
                                nombre VARCHAR(100) NOT NULL UNIQUE,
                                eslogan VARCHAR(150) NULL,
                                tecnico VARCHAR(100) NOT NULL,
                                pais VARCHAR(60) NOT NULL,
                                ciudad VARCHAR(60) NOT NULL,
                                categoria VARCHAR(50) NOT NULL,
                                num_goles INT UNSIGNED NOT NULL DEFAULT 0,
                                num_partidos_jugados INT UNSIGNED NOT NULL DEFAULT 0,
                                num_partidos_ganados INT UNSIGNED NOT NULL DEFAULT 0,
                                num_empates INT UNSIGNED NOT NULL DEFAULT 0,
                                num_campeonatos INT UNSIGNED NOT NULL DEFAULT 0,
                                num_expulsiones INT UNSIGNED NOT NULL DEFAULT 0,
                                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                CONSTRAINT chk_partidos CHECK (num_partidos_ganados + num_empates <= num_partidos_jugados)
) ENGINE=InnoDB;