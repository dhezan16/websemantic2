<?php
session_start();
require_once 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    } elseif ($_SESSION['role'] === 'prodi') {
        header("Location: prodi/dashboard.php");
        exit();
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username dan Password wajib diisi!";
    } else {
        $username = mysqli_real_escape_string($conn, $username);

        $sql = "SELECT u.id_user, u.username, u.password, u.id_prodi, r.nama_role
                FROM users u
                JOIN roles r ON u.id_role = r.id_role
                WHERE u.username = '$username'
                LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            $password_valid = false;

            if (password_verify($password, $user['password'])) {
                $password_valid = true;
            } elseif ($password === $user['password']) {
                $password_valid = true;
            }

            if ($password_valid) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = strtolower($user['nama_role']);
                $_SESSION['id_prodi'] = $user['id_prodi'];

                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                    exit();
                } elseif ($_SESSION['role'] === 'prodi') {
                    header("Location: prodi/dashboard.php");
                    exit();
                } else {
                    $error = "Role pengguna tidak valid.";
                }
            } else {
                $error = "Password yang Anda masukkan salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | SIM-MAHASISWA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 30px;

            background:
                radial-gradient(circle at 15% 20%,
                    rgba(212, 175, 55, .12),
                    transparent 30%),
                radial-gradient(circle at 85% 80%,
                    rgba(30, 86, 160, .25),
                    transparent 35%),
                #06152D;

            position: relative;
            overflow: hidden;
        }


        /* =========================================
           BACKGROUND DECORATION
        ========================================= */

        body::before {
            content: "";

            position: absolute;

            width: 450px;
            height: 450px;

            border-radius: 50%;

            background: rgba(212, 175, 55, .04);

            top: -180px;
            right: -100px;
        }

        body::after {
            content: "";

            position: absolute;

            width: 350px;
            height: 350px;

            border-radius: 50%;

            background: rgba(30, 86, 160, .08);

            bottom: -150px;
            left: -100px;
        }


        /* =========================================
           CONTAINER
        ========================================= */

        .login-container {

            width: 100%;
            max-width: 1050px;

            min-height: 610px;

            display: grid;

            grid-template-columns: 1.05fr .95fr;

            position: relative;
            z-index: 2;

            background: rgba(255, 255, 255, .97);

            border-radius: 28px;

            overflow: hidden;

            box-shadow:
                0 35px 90px rgba(0, 0, 0, .35);
        }


        /* =========================================
           LEFT SIDE
        ========================================= */

        .login-info {

            position: relative;

            padding: 55px;

            display: flex;
            flex-direction: column;
            justify-content: center;

            color: white;

            background:
                linear-gradient(145deg,
                    #071933 0%,
                    #0B2347 55%,
                    #0F2C59 100%);

            overflow: hidden;
        }


        .login-info::before {

            content: "";

            position: absolute;

            width: 320px;
            height: 320px;

            border-radius: 50%;

            border: 1px solid rgba(212, 175, 55, .15);

            right: -160px;
            top: -100px;
        }


        .login-info::after {

            content: "";

            position: absolute;

            width: 230px;
            height: 230px;

            border-radius: 50%;

            border: 1px solid rgba(255, 255, 255, .06);

            left: -120px;
            bottom: -80px;
        }


        .info-content {

            position: relative;
            z-index: 2;
        }


        /* =========================================
           LOGO
        ========================================= */

        .logo-wrapper {

            display: flex;

            align-items: center;

            gap: 14px;

            margin-bottom: 40px;
        }


        .logo {

            width: 58px;
            height: 58px;

            border-radius: 17px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(135deg,
                    #D4AF37,
                    #F0D878);

            color: #0F2C59;

            font-size: 17px;

            font-weight: 800;

            box-shadow:
                0 10px 30px rgba(212, 175, 55, .18);
        }


        .logo-text strong {

            display: block;

            font-size: 14px;

            letter-spacing: .3px;
        }


        .logo-text span {

            display: block;

            margin-top: 3px;

            font-size: 10px;

            color: rgba(255, 255, 255, .5);

            text-transform: uppercase;

            letter-spacing: 1.5px;
        }


        /* =========================================
           TITLE
        ========================================= */

        .login-info h1 {

            font-size: 39px;

            line-height: 1.15;

            letter-spacing: -.8px;

            margin-bottom: 18px;
        }


        .login-info h1 span {

            color: #D4AF37;
        }


        .login-info>.info-content>p {

            max-width: 420px;

            color: rgba(255, 255, 255, .65);

            font-size: 14px;

            line-height: 1.8;

            margin-bottom: 35px;
        }


        /* =========================================
           FEATURES
        ========================================= */

        .info-list {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 12px;

            list-style: none;
        }


        .info-list li {

            padding: 13px 14px;

            border-radius: 10px;

            background: rgba(255, 255, 255, .045);

            border: 1px solid rgba(255, 255, 255, .06);

            color: rgba(255, 255, 255, .8);

            font-size: 12px;

            display: flex;

            align-items: center;

            gap: 8px;
        }


        .check {

            color: #D4AF37;

            font-weight: 800;
        }


        /* =========================================
           RIGHT SIDE
        ========================================= */

        .login-form {

            padding: 55px 55px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            background: #FFFFFF;
        }


        .form-header {

            margin-bottom: 32px;
        }


        .form-header .badge {

            display: inline-block;

            padding: 7px 11px;

            border-radius: 8px;

            background: #F1F5F9;

            color: #0F2C59;

            font-size: 10px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

            margin-bottom: 15px;
        }


        .login-form h2 {

            color: #0F2C59;

            font-size: 29px;

            letter-spacing: -.5px;

            margin-bottom: 8px;
        }


        .login-form>.form-header>p {

            color: #64748B;

            font-size: 13px;

            line-height: 1.6;
        }


        /* =========================================
           ALERT
        ========================================= */

        .alert {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 13px 14px;

            border-radius: 10px;

            margin-bottom: 20px;

            background: #FEF2F2;

            border: 1px solid #FECACA;

            color: #B91C1C;

            font-size: 12px;
        }


        /* =========================================
           FORM
        ========================================= */

        .form-group {

            margin-bottom: 19px;
        }


        .form-group label {

            display: block;

            margin-bottom: 8px;

            color: #334155;

            font-size: 12px;

            font-weight: 700;
        }


        .input-wrapper {

            position: relative;
        }


        .input-icon {

            position: absolute;

            left: 14px;

            top: 50%;

            transform: translateY(-50%);

            color: #94A3B8;

            font-size: 15px;

            pointer-events: none;
        }


        .form-control {

            width: 100%;

            padding: 13px 15px 13px 42px;

            border: 1px solid #E2E8F0;

            border-radius: 11px;

            outline: none;

            font-size: 13px;

            color: #1E293B;

            background: #F8FAFC;

            transition: .2s;
        }


        .form-control::placeholder {

            color: #94A3B8;
        }


        .form-control:focus {

            border-color: #0F2C59;

            background: white;

            box-shadow:
                0 0 0 4px rgba(15, 44, 89, .08);
        }


        /* =========================================
           BUTTON
        ========================================= */

        .btn-login {

            width: 100%;

            border: none;

            padding: 14px;

            border-radius: 11px;

            background:
                linear-gradient(135deg,
                    #0F2C59,
                    #1E56A0);

            color: white;

            font-size: 13px;

            font-weight: 700;

            cursor: pointer;

            transition: .2s;

            box-shadow:
                0 8px 20px rgba(15, 44, 89, .18);
        }


        .btn-login:hover {

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(15, 44, 89, .25);
        }


        .btn-login:active {

            transform: translateY(0);
        }


        /* =========================================
           FOOTER
        ========================================= */

        .footer-login {

            margin-top: 28px;

            padding-top: 20px;

            border-top: 1px solid #F1F5F9;

            text-align: center;

            font-size: 11px;

            color: #94A3B8;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media(max-width: 800px) {

            body {
                padding: 18px;
            }


            .login-container {

                grid-template-columns: 1fr;

                max-width: 520px;

                min-height: auto;
            }


            .login-info {

                padding: 35px;
            }


            .login-info h1 {

                font-size: 30px;
            }


            .logo-wrapper {

                margin-bottom: 25px;
            }


            .login-info>.info-content>p {

                margin-bottom: 25px;
            }


            .login-form {

                padding: 40px 35px;
            }
        }


        @media(max-width: 500px) {

            body {
                padding: 12px;
            }


            .login-container {

                border-radius: 20px;
            }


            .login-info {

                padding: 30px 25px;
            }


            .login-info h1 {

                font-size: 27px;
            }


            .info-list {

                grid-template-columns: 1fr;
            }


            .login-form {

                padding: 32px 25px;
            }


            .login-form h2 {

                font-size: 25px;
            }
        }
    </style>

</head>


<body>


    <div class="login-container">


        <!-- =========================================
         INFORMASI SISTEM
    ========================================== -->

        <section class="login-info">

            <div class="info-content">


                <div class="logo-wrapper">

                    <div class="logo">
                        UMB
                    </div>

                    <div class="logo-text">

                        <strong>
                            Universitas Muhammadiyah Bengkulu
                        </strong>

                        <span>
                            Sistem Informasi Akademik
                        </span>

                    </div>

                </div>


                <h1>
                    SIM-<span>MAHASISWA</span>
                </h1>


                <p>
                    Sistem Informasi Manajemen Mahasiswa
                    untuk membantu pengelolaan data akademik
                    secara terstruktur dan terintegrasi.
                </p>


                <ul class="info-list">

                    <li>
                        <span class="check">✓</span>
                        Manajemen Mahasiswa
                    </li>

                    <li>
                        <span class="check">✓</span>
                        Program Studi
                    </li>

                    <li>
                        <span class="check">✓</span>
                        Data Fakultas
                    </li>

                    <li>
                        <span class="check">✓</span>
                        Hak Akses Pengguna
                    </li>

                </ul>

            </div>

        </section>


        <!-- =========================================
         FORM LOGIN
    ========================================== -->

        <section class="login-form">


            <div class="form-header">

                <span class="badge">
                    Secure Login
                </span>

                <h2>
                    Selamat Datang 👋
                </h2>

                <p>
                    Masukkan akun Anda untuk mengakses
                    SIM-MAHASISWA.
                </p>

            </div>


            <?php if (!empty($error)): ?>

                <div class="alert">

                    <span>⚠</span>

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action="index.php">


                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            👤
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            placeholder="Masukkan username"
                            autocomplete="username"
                            required
                            value="<?php
                                    echo isset($_POST['username'])
                                        ? htmlspecialchars($_POST['username'])
                                        : '';
                                    ?>">

                    </div>

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            🔒
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required>

                    </div>

                </div>


                <button
                    type="submit"
                    name="login"
                    class="btn-login">
                    Masuk ke Sistem →
                </button>


            </form>


            <div class="footer-login">

                © <?php echo date('Y'); ?>
                Universitas Muhammadiyah Bengkulu

            </div>


        </section>


    </div>


</body>

</html>