<?php
/**
 * Forgot Password Page
 * Allows users to request password reset
 */
require_once 'config/database.php';
require_once 'lib/auth.php';
require_once 'lib/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . base_url('index.php'));
    exit;
}

$error = '';
$success = '';
$resetLink = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Email wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } else {
        try {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT id, nama FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Save token to database
                $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
                $stmt->execute([$token, $expires, $user['id']]);

                // Generate reset link (in production, this would be sent via email)
                $resetLink = base_url('reset-password.php?token=' . $token);

                $success = 'Link reset password telah dibuat. Karena ini adalah simulasi (tidak ada SMTP), silakan klik link di bawah:';
            } else {
                // For security, show the same message even if email not found
                $success = 'Jika email terdaftar, instruksi reset password akan dikirim.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}

$pageTitle = 'Lupa Password - TravelDNE';
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
                <h2>Lupa Password?</h2>
                <p class="text-muted">Masukkan email Anda untuk reset password</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $success ?>
                </div>

                <?php if ($resetLink): ?>
                    <div class="alert"
                        style="background: var(--bg-tertiary); border: 1px solid var(--primary); margin-top: 1rem;">
                        <p style="margin-bottom: 0.5rem; font-weight: 500;">
                            <i class="fas fa-link"></i> Link Reset Password:
                        </p>
                        <a href="<?= $resetLink ?>" style="word-break: break-all; color: var(--primary);">
                            <?= $resetLink ?>
                        </a>
                        <p class="text-muted" style="margin-top: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-clock"></i> Link berlaku selama 1 jam
                        </p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">
                        <i class="fas fa-paper-plane"></i> Kirim Link Reset
                    </button>
                </form>
            <?php endif; ?>

            <p class="text-center mt-2" style="margin-top: 1.5rem;">
                Ingat password? <a href="<?= base_url('login.php') ?>">Masuk sekarang</a>
            </p>

            <p class="text-center">
                <a href="<?= base_url() ?>" class="text-muted">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </p>
        </div>
    </div>

    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>

</html>