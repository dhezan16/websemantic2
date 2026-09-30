<?php

// ========================================================
// Koneksi Database - SIM Mahasiswa
// Hosting: InfinityFree
// ========================================================

$host     = "sql300.infinityfree.com";
$username = "if0_43034446";
$password = "3WVVRLzbfj5";
$database = "if0_43034446_websemantic2";

// Koneksi MySQLi
$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// URL website
$main_url = "websemantic2.rf.gd";

?>
