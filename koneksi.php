<?php

// ========================================================
// Koneksi Database - SIM Mahasiswa
// Database lokal
// ========================================================

$host     = "localhost";
$username = "root";
$password = "";
$database = "universitassemantik72";

// Koneksi MySQLi
$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

$main_url ="http://websemantik.test"
?>