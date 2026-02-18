<?php
/**
 * Reset Password Page
 * Allows users to set new password with valid token
 */
require_once 'config/database.php';
require_once 'lib/auth.php';
require_once 'lib/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . base_url('index.php'));
    exit;
}

$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$validToken = false;
$user = null;

// Validate token
if (empty($token)) {
    $error = 'Token tidak valid';
} else {
    try {
        $stmt = $pdo->prepare("SELECT id, nama, email FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            $validToken = true;
        } else {
            $error = 'Token tidak valid atau sudah kadaluarsa. Silakan request reset password baru.';
        }
    } catch (PDOException $e) {
        $error = 'Terjadi kesalahan. Silakan coba lagi.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($password)) {
        $error = 'Password wajib diisi';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $password_confirm) {
        $error = 'Konfirmasi password tidak cocok';
    } else {
        try {
            // Update password and clear token
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);

            setFlash('success', 'Password berhasil diubah! Silakan login dengan password baru.');
            header('Location: ' . base_url('login.php'));
            exit;
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}

$pageTitle = 'Reset Password - TravelDNE';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $pageTitle ?>
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card animate-fade-in-up">
            <div class="auth-header">
                <a href="<?= base_url() ?>" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <span>TravelDNE</span>
                </a>
                <h2>Reset Password</h2>
                <p class="text-muted">Buat password baru untuk akun Anda</p>
            </div>

            <?php if ($error && !$validToken): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
                <p class="text-center" style="margin-top: 1rem;">
                    <a href="<?= base_url('forgot-password.php') ?>" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Request Reset Baru
                    </a>
                </p>
            <?php elseif ($validToken): ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <div class="alert alert-info"
                    style="background: rgba(13, 148, 136, 0.1); border: 1px solid var(--primary);">
                    <i class="fas fa-user"></i>
                    Reset password untuk: <strong>
                        <?= htmlspecialchars($user['email']) ?>
                    </strong>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter"
                            required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirm" class="form-control"
                            placeholder="Ulangi password baru" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </form>
            <?php endif; ?>

            <p class="text-center mt-2" style="margin-top: 1.5rem;">
                <a href="<?= base_url('login.php') ?>">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>
            </p>
        </div>
    </div>

    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>

</html>