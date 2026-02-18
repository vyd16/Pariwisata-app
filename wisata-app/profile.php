<?php
/**
 * User Profile Edit Page
 * Allows logged-in users to edit their own profile
 */
require_once 'config/database.php';
require_once 'lib/auth.php';
require_once 'lib/functions.php';

requireLogin();

$currentUser = getCurrentUser();
$error = '';
$success = '';

// Get full user data from database
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$currentUser['id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    setFlash('danger', 'Gagal memuat data profil');
    header('Location: ' . base_url('index.php'));
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $foto = $user['foto'];

    // Validation
    if (empty($nama) || empty($email)) {
        $error = 'Nama dan email wajib diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    } elseif (!empty($password) && strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif (!empty($password) && $password !== $password_confirm) {
        $error = 'Konfirmasi password tidak cocok';
    } elseif (!in_array($jenis_kelamin, ['L', 'P', ''])) {
        $error = 'Jenis kelamin tidak valid';
    } else {
        try {
            // Check if email already used by another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $currentUser['id']]);

            if ($stmt->fetch()) {
                $error = 'Email sudah digunakan oleh pengguna lain';
            } else {
                // Handle foto upload
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $fileType = $finfo->file($_FILES['foto']['tmp_name']);

                    if (!in_array($fileType, $allowedTypes)) {
                        $error = 'Hanya file gambar (JPG, PNG, GIF, WEBP) yang diperbolehkan';
                    } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                        $error = 'Ukuran file maksimal 2MB';
                    } else {
                        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                        $newFileName = 'user_' . $currentUser['id'] . '_' . time() . '.' . $ext;
                        $uploadDir = 'uploads/users/';

                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $newFileName)) {
                            // Delete old photo if exists
                            if ($user['foto'] && file_exists($uploadDir . $user['foto'])) {
                                unlink($uploadDir . $user['foto']);
                            }
                            $foto = $newFileName;
                        }
                    }
                }

                if (empty($error)) {
                    // Build update query
                    $sql = "UPDATE users SET nama = ?, email = ?, jenis_kelamin = ?, foto = ?";
                    $params = [$nama, $email, $jenis_kelamin ?: null, $foto];

                    // Add password if provided
                    if (!empty($password)) {
                        $sql .= ", password = ?";
                        $params[] = password_hash($password, PASSWORD_DEFAULT);
                    }

                    $sql .= " WHERE id = ?";
                    $params[] = $currentUser['id'];

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);

                    // Update session data
                    $_SESSION['user_nama'] = $nama;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_foto'] = $foto;

                    $success = 'Profil berhasil diperbarui!';

                    // Refresh user data
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$currentUser['id']]);
                    $user = $stmt->fetch();
                }
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}

$pageTitle = 'Edit Profil - TravelDNE';
include 'views/header.php';
?>

<main class="container" style="padding: 2rem 0; max-width: 600px;">
    <div class="card animate-fade-in-up">
        <div class="card-body">
            <div class="page-header" style="margin-bottom: 2rem;">
                <div>
                    <h1 class="page-title">Edit Profil</h1>
                    <p class="text-muted">Perbarui informasi akun Anda</p>
                </div>
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
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <!-- Current Photo -->
                <div style="text-align: center; margin-bottom: 2rem;">
                    <?php
                    $currentPhoto = $user['foto']
                        ? base_url('uploads/users/' . $user['foto'])
                        : 'https://ui-avatars.com/api/?name=' . urlencode($user['nama']) . '&background=0d9488&color=fff&size=150';
                    ?>
                    <img src="<?= $currentPhoto ?>" alt="<?= htmlspecialchars($user['nama']) ?>"
                        class="avatar avatar-lg" style="width: 120px; height: 120px; margin-bottom: 1rem;">
                    <p class="text-muted" style="margin: 0;">
                        <?= htmlspecialchars($user['nama']) ?>
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap *</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L" <?= ($user['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki
                        </option>
                        <option value="P" <?= ($user['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="form-hint">Format: JPG, PNG, GIF, WEBP. Maks: 2MB</small>
                </div>

                <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--border);">

                <h4 style="margin-bottom: 1rem; color: var(--text-primary);">
                    <i class="fas fa-lock"></i> Ubah Password
                </h4>
                <p class="text-muted" style="margin-bottom: 1rem; font-size: 0.875rem;">
                    Kosongkan jika tidak ingin mengubah password
                </p>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirm" class="form-control"
                        placeholder="Ulangi password baru">
                </div>

                <div class="d-flex gap-1" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="<?= base_url('index.php') ?>" class="btn btn-ghost">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'views/footer.php'; ?>