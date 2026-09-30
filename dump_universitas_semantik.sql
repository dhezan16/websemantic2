-- Hapus tabel jika sudah ada (urutan dibalik agar tidak error karena Foreign Key)
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS mahasiswa;
DROP TABLE IF EXISTS program_studi;
DROP TABLE IF EXISTS fakultas;
DROP TABLE IF EXISTS roles;

-- 1. Tabel Fakultas
CREATE TABLE fakultas (
    id_fakultas INT AUTO_INCREMENT PRIMARY KEY,
    nama_fakultas VARCHAR(100) NOT NULL
);

-- Data Dummy Fakultas
INSERT INTO fakultas (id_fakultas, nama_fakultas) VALUES
(1, 'Fakultas Teknik dan Ilmu Komputer'),
(2, 'Fakultas Ekonomi dan Bisnis');

-- 2. Tabel Program Studi
CREATE TABLE program_studi (
    id_prodi INT AUTO_INCREMENT PRIMARY KEY,
    id_fakultas INT NOT NULL,
    nama_prodi VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_fakultas) REFERENCES fakultas(id_fakultas) ON DELETE CASCADE
);

-- Data Dummy Program Studi
INSERT INTO program_studi (id_prodi, id_fakultas, nama_prodi) VALUES
(1, 1, 'Teknik Informatika'),
(2, 1, 'Sistem Informasi'),
(3, 2, 'Manajemen'),
(4, 2, 'Akuntansi');

-- 3. Tabel Mahasiswa
CREATE TABLE mahasiswa (
    npm VARCHAR(20) PRIMARY KEY,
    nama_mahasiswa VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    alamat TEXT NOT NULL,
    tanggal_masuk DATE NOT NULL,
    id_prodi INT NOT NULL,
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi) ON DELETE CASCADE
);

-- Data Dummy Mahasiswa
INSERT INTO mahasiswa (npm, nama_mahasiswa, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, tanggal_masuk, id_prodi) VALUES
('230102001', 'Ahmad Fauzi', 'L', 'Jakarta', '2004-05-12', 'Jl. Merdeka No. 10, Jakarta', '2023-08-15', 1),
('230102002', 'Siti Aminah', 'P', 'Bandung', '2004-09-20', 'Jl. Asia Afrika No. 45, Bandung', '2023-08-15', 1),
('230202001', 'Budi Santoso', 'L', 'Surabaya', '2003-11-05', 'Jl. Pemuda No. 12, Surabaya', '2023-08-15', 3);

-- 4. Tabel Roles
CREATE TABLE roles (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(50) NOT NULL
);

-- Data Dummy Roles
INSERT INTO roles (id_role, nama_role) VALUES
(1, 'Admin'),
(2, 'Prodi');

-- 5. Tabel Users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    id_role INT NOT NULL,
    id_prodi INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES roles(id_role) ON DELETE CASCADE,
    FOREIGN KEY (id_prodi) REFERENCES program_studi(id_prodi) ON DELETE SET NULL
);

-- Data Dummy Users 
-- Catatan: Password di bawah menggunakan hash untuk 'admin123' ($2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
INSERT INTO users (id_user, username, password, id_role, id_prodi) VALUES
(1, 'admin_pusat', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NULL),
(2, 'prodi_ti', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 1),
(3, 'prodi_manajemen', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 3);
