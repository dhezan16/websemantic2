CREATE DATABASE IF NOT EXISTS universitassemantik72
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE universitassemantik72;

-- Hapus tabel lama jika ada
DROP TABLE IF EXISTS mahasiswa;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS program_studi;
DROP TABLE IF EXISTS fakultas;
DROP TABLE IF EXISTS roles;


-- ========================================================
-- TABLE FAKULTAS
-- ========================================================

CREATE TABLE fakultas (
    id_fakultas INT AUTO_INCREMENT NOT NULL,
    nama_fakultas VARCHAR(100) NOT NULL,
    PRIMARY KEY (id_fakultas)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- TABLE PROGRAM STUDI
-- ========================================================

CREATE TABLE program_studi (
    id_prodi INT AUTO_INCREMENT NOT NULL,
    nama_prodi VARCHAR(100) NOT NULL,
    id_fakultas INT NOT NULL,
    PRIMARY KEY (id_prodi),
    KEY fk_prodi_fakultas (id_fakultas),
    CONSTRAINT fk_prodi_fakultas
        FOREIGN KEY (id_fakultas)
        REFERENCES fakultas (id_fakultas)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- TABLE MAHASISWA
-- ========================================================

CREATE TABLE mahasiswa (
    npm VARCHAR(20) NOT NULL,
    nama_mahasiswa VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    tanggal_masuk DATE NOT NULL,
    alamat TEXT,
    id_prodi INT NOT NULL,
    PRIMARY KEY (npm),
    KEY fk_mahasiswa_prodi (id_prodi),
    CONSTRAINT fk_mahasiswa_prodi
        FOREIGN KEY (id_prodi)
        REFERENCES program_studi (id_prodi)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- TABLE ROLES
-- ========================================================

CREATE TABLE roles (
    id_role INT AUTO_INCREMENT NOT NULL,
    nama_role VARCHAR(50) NOT NULL UNIQUE,
    keterangan VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id_role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- TABLE USERS
-- ========================================================

CREATE TABLE users (
    id_user INT AUTO_INCREMENT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    id_prodi INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user),
    KEY fk_users_roles (id_role),
    KEY fk_users_prodi (id_prodi),
    CONSTRAINT fk_users_roles
        FOREIGN KEY (id_role)
        REFERENCES roles (id_role)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    CONSTRAINT fk_users_prodi
        FOREIGN KEY (id_prodi)
        REFERENCES program_studi (id_prodi)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ========================================================
-- DATA ROLES
-- ========================================================

INSERT INTO roles (id_role, nama_role, keterangan) VALUES
(1, 'admin', 'Pemegang hak tertinggi sistem'),
(2, 'prodi', 'Pengelola data program studi dan mahasiswa pada prodi terkait');


-- ========================================================
-- DATA FAKULTAS
-- ========================================================

INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
(1, 'Teknik');


-- ========================================================
-- DATA PROGRAM STUDI
-- ========================================================

INSERT INTO program_studi (id_prodi, nama_prodi, id_fakultas) VALUES
(1, 'Teknik Informatika', 1);


-- ========================================================
-- DATA USERS
-- ========================================================

INSERT INTO users (
    id_user,
    username,
    password,
    id_role,
    id_prodi
) VALUES
(
    1,
    'admin',
    '$2y$10$e8p2y84J98B8v6.Nq22u8e88v6qU63zD/011.8.8.8.8.8.8.8.8',
    1,
    NULL
),
(
    2,
    'admin_tif',
    '$2y$10$e8p2y84J98B8v6.Nq22u8e88v6qU63zD/011.8.8.8.8.8.8.8.8',
    2,
    1
);


-- ========================================================
-- DATA MAHASISWA
-- ========================================================

INSERT INTO mahasiswa (
    npm,
    nama_mahasiswa,
    jenis_kelamin,
    tempat_lahir,
    tanggal_lahir,
    tanggal_masuk,
    alamat,
    id_prodi
) VALUES
(
    '2155201001',
    'Ahmad Rizky',
    'L',
    'Bengkulu',
    '2002-05-12',
    '2021-09-01',
    'Jl. Suprapto No. 12, Bengkulu',
    1
);
