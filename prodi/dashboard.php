<?php

session_start();
require_once '../koneksi.php';

// =====================================================
// CEK LOGIN
// =====================================================

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// =====================================================
// CEK ROLE
// =====================================================

if ($_SESSION['role'] !== 'prodi') {
    header("Location: ../index.php");
    exit();
}


// =====================================================
// AMBIL DATA STATISTIK DARI DATABASE
// =====================================================

// Total mahasiswa
$query_mahasiswa = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM mahasiswa"
);

$total_mahasiswa = 0;

if ($query_mahasiswa) {
    $data = mysqli_fetch_assoc($query_mahasiswa);
    $total_mahasiswa = $data['total'];
}


// Total program studi
$query_prodi = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM program_studi"
);

$total_prodi = 0;

if ($query_prodi) {
    $data = mysqli_fetch_assoc($query_prodi);
    $total_prodi = $data['total'];
}


// Total fakultas
$query_fakultas = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM fakultas"
);

$total_fakultas = 0;

if ($query_fakultas) {
    $data = mysqli_fetch_assoc($query_fakultas);
    $total_fakultas = $data['total'];
}


// Total pengguna
$query_users = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

$total_users = 0;

if ($query_users) {
    $data = mysqli_fetch_assoc($query_users);
    $total_users = $data['total'];
}


// =====================================================
// DATA ROLE PENGGUNA
// =====================================================

$query_roles = mysqli_query(
    $conn,
    "SELECT 
        r.nama_role,
        COUNT(u.id_user) AS jumlah
     FROM roles r
     LEFT JOIN users u ON u.id_role = r.id_role
     GROUP BY r.id_role, r.nama_role
     ORDER BY r.id_role"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard Admin | SIM-MAHASISWA</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #F1F5F9;
            color: #1E293B;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;

            background: linear-gradient(
                180deg,
                #071933,
                #0F2C59
            );

            color: white;
            padding: 25px 18px;
        }


        .brand {
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 10px;
            margin-bottom: 35px;
        }


        .brand-logo {
            width: 45px;
            height: 45px;

            border-radius: 50%;

            background: #D4AF37;
            color: #0F2C59;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: 800;
        }


        .brand h2 {
            font-size: 16px;
        }


        .brand p {
            font-size: 11px;

            color: rgba(255,255,255,.65);

            margin-top: 3px;
        }


        .menu-title {
            font-size: 11px;

            text-transform: uppercase;
            letter-spacing: 1px;

            color: rgba(255,255,255,.45);

            padding: 0 12px;
            margin-bottom: 10px;
        }


        .menu {
            list-style: none;
        }


        .menu li {
            margin-bottom: 5px;
        }


        .menu a {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 12px;

            border-radius: 9px;

            text-decoration: none;

            color: rgba(255,255,255,.8);

            font-size: 14px;

            transition: .2s;
        }


        .menu a:hover,
        .menu a.active {

            background: rgba(255,255,255,.1);

            color: white;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .main {

            margin-left: 250px;

            min-height: 100vh;
        }


        /* =====================================================
           TOPBAR
        ===================================================== */

        .topbar {

            height: 75px;

            background: white;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 35px;

            border-bottom: 1px solid #E2E8F0;
        }


        .topbar h1 {

            font-size: 20px;

            color: #0F2C59;
        }


        .user-info {

            display: flex;
            align-items: center;

            gap: 12px;
        }


        .avatar {

            width: 40px;
            height: 40px;

            border-radius: 50%;

            background: #0F2C59;

            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: 700;
        }


        .user-text strong {

            display: block;

            font-size: 13px;
        }


        .user-text span {

            font-size: 11px;

            color: #64748B;
        }


        .logout {

            margin-left: 15px;

            text-decoration: none;

            color: #EF4444;

            font-size: 13px;

            font-weight: 600;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .content {

            padding: 35px;
        }


        .welcome {

            margin-bottom: 30px;
        }


        .welcome h2 {

            font-size: 25px;

            color: #0F2C59;

            margin-bottom: 5px;
        }


        .welcome p {

            color: #64748B;

            font-size: 14px;
        }


        /* =====================================================
           STATISTIC CARD
        ===================================================== */

        .stats {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 20px;

            margin-bottom: 35px;
        }


        .stat-card {

            background: white;

            border-radius: 15px;

            padding: 22px;

            border: 1px solid #E2E8F0;

            box-shadow:
                0 3px 12px
                rgba(15,44,89,.05);
        }


        .stat-icon {

            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background:
                rgba(15,44,89,.08);

            font-size: 20px;

            margin-bottom: 15px;
        }


        .stat-card h3 {

            font-size: 27px;

            color: #0F2C59;

            margin-bottom: 3px;
        }


        .stat-card p {

            font-size: 13px;

            color: #64748B;
        }


        /* =====================================================
           DATABASE INFO
        ===================================================== */

        .database-section {

            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 20px;

            margin-bottom: 35px;
        }


        .database-card {

            background: white;

            border: 1px solid #E2E8F0;

            border-radius: 15px;

            padding: 25px;
        }


        .database-card h2 {

            font-size: 18px;

            color: #0F2C59;

            margin-bottom: 20px;
        }


        .database-list {

            list-style: none;
        }


        .database-list li {

            display: flex;

            align-items: center;
            justify-content: space-between;

            padding: 13px 0;

            border-bottom:
                1px solid #E2E8F0;

            font-size: 13px;
        }


        .database-list li:last-child {

            border-bottom: none;
        }


        .database-name {

            font-weight: 600;

            color: #334155;
        }


        .database-count {

            background: #F1F5F9;

            color: #0F2C59;

            padding: 5px 10px;

            border-radius: 7px;

            font-size: 12px;

            font-weight: 700;
        }


        /* =====================================================
           QUICK MENU
        ===================================================== */

        .section-title {

            font-size: 18px;

            color: #0F2C59;

            margin-bottom: 15px;
        }


        .quick-menu {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }


        .quick-card {

            background: white;

            border: 1px solid #E2E8F0;

            border-radius: 15px;

            padding: 25px;

            text-decoration: none;

            color: #1E293B;

            transition: .2s;
        }


        .quick-card:hover {

            transform:
                translateY(-3px);

            border-color: #D4AF37;

            box-shadow:
                0 8px 20px
                rgba(15,44,89,.08);
        }


        .quick-card .icon {

            font-size: 28px;

            margin-bottom: 12px;
        }


        .quick-card h3 {

            font-size: 16px;

            color: #0F2C59;

            margin-bottom: 6px;
        }


        .quick-card p {

            font-size: 13px;

            color: #64748B;

            line-height: 1.5;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media(max-width: 1000px) {

            .stats {

                grid-template-columns:
                    repeat(2, 1fr);
            }

            .quick-menu {

                grid-template-columns:
                    repeat(2, 1fr);
            }

            .database-section {

                grid-template-columns: 1fr;
            }
        }


        @media(max-width: 700px) {

            .sidebar {

                width: 70px;

                padding: 20px 10px;
            }


            .brand-text,
            .menu-title,
            .menu a span {

                display: none;
            }


            .brand {

                justify-content: center;
            }


            .main {

                margin-left: 70px;
            }


            .topbar {

                padding: 0 20px;
            }


            .content {

                padding: 20px;
            }


            .stats {

                grid-template-columns: 1fr;
            }


            .quick-menu {

                grid-template-columns: 1fr;
            }


            .database-section {

                grid-template-columns: 1fr;
            }


            .user-text {

                display: none;
            }
        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar">

    <div class="brand">

        <div class="brand-logo">
            UMB
        </div>

        <div class="brand-text">

            <h2>
                SIM-MAHASISWA
            </h2>

            <p>
                Administrator
            </p>

        </div>

    </div>


    <div class="menu-title">
        Menu Utama
    </div>


    <ul class="menu">

        <li>

            <a
                href="dashboard.php"
                class="active"
            >
                🏠
                <span>
                    Dashboard
                </span>
            </a>

        </li>


        <li>

            <a href="#">

                👨‍🎓

                <span>
                    Data Mahasiswa
                </span>

            </a>

        </li>


        <li>

            <a href="#">

                🎓

                <span>
                    Program Studi
                </span>

            </a>

        </li>


        <li>

            <a href="#">

                🏛️

                <span>
                    Fakultas
                </span>

            </a>

        </li>


        <li>

            <a href="#">

                👤

                <span>
                    Manajemen User
                </span>

            </a>

        </li>

    </ul>

</aside>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main">


    <!-- TOPBAR -->

    <header class="topbar">

        <h1>
            Dashboard Admin
        </h1>


        <div class="user-info">

            <div class="avatar">

                <?php
                echo strtoupper(
                    substr(
                        $_SESSION['username'],
                        0,
                        1
                    )
                );
                ?>

            </div>


            <div class="user-text">

                <strong>

                    <?php
                    echo htmlspecialchars(
                        $_SESSION['username']
                    );
                    ?>

                </strong>

                <span>
                    Administrator
                </span>

            </div>


            <a
                href="../logout.php"
                class="logout"
            >
                Logout
            </a>

        </div>

    </header>


    <!-- CONTENT -->

    <section class="content">


        <!-- WELCOME -->

        <div class="welcome">

            <h2>

                Selamat Datang,
                <?php
                echo htmlspecialchars(
                    $_SESSION['username']
                );
                ?>!

            </h2>

            <p>
                Berikut adalah ringkasan
                data Sistem Informasi Manajemen Mahasiswa.
            </p>

        </div>


        <!-- =================================================
             STATISTIK
        ================================================== -->

        <div class="stats">


            <!-- MAHASISWA -->

            <div class="stat-card">

                <div class="stat-icon">
                    👨‍🎓
                </div>

                <h3>
                    <?php echo $total_mahasiswa; ?>
                </h3>

                <p>
                    Total Mahasiswa
                </p>

            </div>


            <!-- PROGRAM STUDI -->

            <div class="stat-card">

                <div class="stat-icon">
                    🎓
                </div>

                <h3>
                    <?php echo $total_prodi; ?>
                </h3>

                <p>
                    Program Studi
                </p>

            </div>


            <!-- FAKULTAS -->

            <div class="stat-card">

                <div class="stat-icon">
                    🏛️
                </div>

                <h3>
                    <?php echo $total_fakultas; ?>
                </h3>

                <p>
                    Fakultas
                </p>

            </div>


            <!-- USER -->

            <div class="stat-card">

                <div class="stat-icon">
                    👤
                </div>

                <h3>
                    <?php echo $total_users; ?>
                </h3>

                <p>
                    Pengguna Sistem
                </p>

            </div>

        </div>


        <!-- =================================================
             INFORMASI DATABASE
        ================================================== -->

        <div class="database-section">


            <!-- DATA SISTEM -->

            <div class="database-card">

                <h2>
                    📊 Ringkasan Data
                </h2>


                <ul class="database-list">

                    <li>

                        <span class="database-name">
                            👨‍🎓 Mahasiswa
                        </span>

                        <span class="database-count">
                            <?php echo $total_mahasiswa; ?>
                            data
                        </span>

                    </li>


                    <li>

                        <span class="database-name">
                            🎓 Program Studi
                        </span>

                        <span class="database-count">
                            <?php echo $total_prodi; ?>
                            data
                        </span>

                    </li>


                    <li>

                        <span class="database-name">
                            🏛️ Fakultas
                        </span>

                        <span class="database-count">
                            <?php echo $total_fakultas; ?>
                            data
                        </span>

                    </li>


                    <li>

                        <span class="database-name">
                            👤 Users
                        </span>

                        <span class="database-count">
                            <?php echo $total_users; ?>
                            akun
                        </span>

                    </li>

                </ul>

            </div>


            <!-- ROLE -->

            <div class="database-card">

                <h2>
                    🔐 Pengguna Berdasarkan Role
                </h2>


                <ul class="database-list">

                    <?php if ($query_roles && mysqli_num_rows($query_roles) > 0): ?>

                        <?php while ($role = mysqli_fetch_assoc($query_roles)): ?>

                            <li>

                                <span class="database-name">

                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            $role['nama_role']
                                        )
                                    );
                                    ?>

                                </span>

                                <span class="database-count">

                                    <?php
                                    echo $role['jumlah'];
                                    ?>

                                    akun

                                </span>

                            </li>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <li>

                            <span class="database-name">
                                Belum ada data role
                            </span>

                        </li>

                    <?php endif; ?>

                </ul>

            </div>

        </div>


        <!-- =================================================
             MENU CEPAT
        ================================================== -->

        <h2 class="section-title">
            Menu Pengelolaan
        </h2>


        <div class="quick-menu">


            <a
                href="#"
                class="quick-card"
            >

                <div class="icon">
                    👨‍🎓
                </div>

                <h3>
                    Data Mahasiswa
                </h3>

                <p>
                    Kelola seluruh data mahasiswa
                    yang terdaftar dalam sistem.
                </p>

            </a>


            <a
                href="#"
                class="quick-card"
            >

                <div class="icon">
                    🎓
                </div>

                <h3>
                    Program Studi
                </h3>

                <p>
                    Kelola program studi
                    dan hubungan dengan fakultas.
                </p>

            </a>


            <a
                href="#"
                class="quick-card"
            >

                <div class="icon">
                    🏛️
                </div>

                <h3>
                    Fakultas
                </h3>

                <p>
                    Kelola data fakultas
                    Universitas Muhammadiyah Bengkulu.
                </p>

            </a>


            <a
                href="#"
                class="quick-card"
            >

                <div class="icon">
                    👤
                </div>

                <h3>
                    Manajemen User
                </h3>

                <p>
                    Kelola akun admin
                    dan pengelola program studi.
                </p>

            </a>


            <a
                href="#"
                class="quick-card"
            >

                <div class="icon">
                    📊
                </div>

                <h3>
                    Laporan
                </h3>

                <p>
                    Lihat dan kelola
                    ringkasan data akademik.
                </p>

            </a>


            <a
                href="../logout.php"
                class="quick-card"
            >

                <div class="icon">
                    🚪
                </div>

                <h3>
                    Keluar Sistem
                </h3>

                <p>
                    Keluar dari akun
                    dan kembali ke halaman login.
                </p>

            </a>


        </div>

    </section>

</main>

</body>

</html>