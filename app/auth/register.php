<?php
declare(strict_types=1);
require_once '../config.php';

// Jika user sudah login, redirect ke dashboard (misal index.php)
if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard.php');
    exit;
}

$errors = [];
$email = '';
$name = '';

// Proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email'] ?? '');
    $name = sanitize_input($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validasi
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email tidak valid.';
    }
    if (!$name) {
        $errors['name'] = 'Nama lengkap wajib diisi.';
    }
    if (!$password || strlen($password) < 8) {
        $errors['password'] = 'Password harus minimal 8 karakter.';
    }
    if ($password !== $password_confirm) {
        $errors['password_confirm'] = 'Password konfirmasi tidak cocok.';
    }

    // Cek email sudah ada atau belum
    if (empty($errors)) {
        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors['email'] = 'Email sudah terdaftar.';
        }
        $stmt->close();
    }

    // Jika tidak ada error, simpan user baru
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare('INSERT INTO users (email, password_hash, name) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $email, $password_hash, $name);
        if ($stmt->execute()) {
            // Login otomatis setelah registrasi
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_name'] = $name;
            header('Location: ../dashboard.php');
            exit;
        } else {
            $errors['general'] = 'Terjadi kesalahan, silahkan coba lagi.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register - FindIt</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
<style>
    /* Reset dan tipografi */
    * {
        box-sizing: border-box;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #6366f1, #06b6d4);
        margin: 0;
        color: #f9fafb;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }
    main {
        background: rgba(255 255 255 / 0.1);
        border-radius: 16px;
        padding: 40px 30px;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 16px 40px rgba(0,0,0,0.2);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        text-align: center;
    }
    h1 {
        margin-bottom: 24px;
        font-weight: 900;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        letter-spacing: 1px;
        color: #e0e7ff;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
        text-align: left;
    }
    label {
        font-weight: 600;
        font-size: 0.9rem;
        color: #a5b4fc;
    }
    input[type="text"], input[type="email"], input[type="password"] {
        padding: 12px 16px;
        border-radius: 12px;
        border: none;
        outline: none;
        font-size: 1rem;
        background: rgba(255 255 255 / 0.2);
        color: #fff;
        transition: background-color 0.3s ease;
    }
    input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
        background: rgba(255 255 255 / 0.35);
    }
    .error {
        color: #f87171;
        font-size: 0.85rem;
        margin-top: 4px;
    }
    button {
        background: linear-gradient(135deg, #8b5cf6 0%, #06b6d4 100%);
        font-weight: 700;
        font-size: 1rem;
        color: white;
        border: none;
        border-radius: 16px;
        padding: 14px;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    button:hover, button:focus {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.5);
    }
    .material-icons {
        font-size: 20px;
        vertical-align: middle;
    }
    .link-login {
        margin-top: 16px;
        text-align: center;
        font-size: 0.9rem;
        color: #a5b4fc;
    }
    .link-login a {
        color: #eef2ff;
        text-decoration: none;
        font-weight: 600;
    }
    .link-login a:hover {
        text-decoration: underline;
    }
    .general-error {
        color: #f87171;
        margin-bottom: 16px;
        font-weight: 600;
    }
</style>
</head>
<body>
<main role="main" aria-labelledby="register-heading">
    <h1 id="register-heading">Buat Akun FindIt</h1>
    <?php if (!empty($errors['general'])): ?>
        <div class="general-error" role="alert"><?= $errors['general']; ?></div>
    <?php endif; ?>
    <form action="" method="POST" novalidate>
        <label for="name">Nama Lengkap</label>
        <input id="name" name="name" type="text" required minlength="3" maxlength="100" value="<?= htmlentities($name); ?>" aria-describedby="name-error" />
        <?php if (!empty($errors['name'])): ?>
            <div id="name-error" class="error" role="alert"><?= $errors['name']; ?></div>
        <?php endif; ?>

        <label for="email">Alamat Email</label>
        <input id="email" name="email" type="email" required value="<?= htmlentities($email); ?>" aria-describedby="email-error" />
        <?php if (!empty($errors['email'])): ?>
            <div id="email-error" class="error" role="alert"><?= $errors['email']; ?></div>
        <?php endif; ?>

        <label for="password">Password (min 8 karakter)</label>
        <input id="password" name="password" type="password" required minlength="8" aria-describedby="password-error" autocomplete="new-password" />
        <?php if (!empty($errors['password'])): ?>
            <div id="password-error" class="error" role="alert"><?= $errors['password']; ?></div>
        <?php endif; ?>

        <label for="password_confirm">Konfirmasi Password</label>
        <input id="password_confirm" name="password_confirm" type="password" required aria-describedby="password-confirm-error" autocomplete="new-password" />
        <?php if (!empty($errors['password_confirm'])): ?>
            <div id="password-confirm-error" class="error" role="alert"><?= $errors['password_confirm']; ?></div>
        <?php endif; ?>

        <button type="submit" aria-label="Daftar akun baru">
            <span class="material-icons" aria-hidden="true">person_add</span> Daftar
        </button>
    </form>
    <div class="link-login">
        Sudah punya akun? <a href="login.php">Masuk di sini</a>
    </div>
</main>
</body>
</html>

