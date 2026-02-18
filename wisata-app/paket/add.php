<?php
/**
 * Add New Package
 */
require_once '../config/database.php';
require_once '../lib/auth.php';
require_once '../lib/functions.php';

requireAdmin();

$pageTitle = 'Tambah Paket - Admin';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $deskripsi = sanitize($_POST['deskripsi'] ?? '');
    $harga = floatval($_POST['harga'] ?? 0);
    $durasi = sanitize($_POST['durasi'] ?? '');
    $lokasi = sanitize($_POST['lokasi'] ?? '');
    $foto = null;

    // Validation
    if (empty($nama) || empty($harga)) {
        $error = 'Nama dan harga wajib diisi';
    } else {
        // Handle file upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadFile($_FILES['foto'], 'uploads/packages');
            if ($upload['success']) {
                $foto = $upload['filename'];
            } else {
                $error = $upload['error'];
            }
        }

        if (empty($error)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO paket (nama, deskripsi, harga, durasi, lokasi, foto) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nama, $deskripsi, $harga, $durasi, $lokasi, $foto]);

                setFlash('success', 'Paket wisata berhasil ditambahkan');
                header('Location: ' . base_url('paket/'));
                exit;
            } catch (PDOException $e) {
                $error = 'Gagal menyimpan data: ' . $e->getMessage();
            }
        }
    }
}

include '../views/admin_header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Paket Wisata</h1>
        <p class="text-muted">Buat paket wisata baru</p>
    </div>
    <a href="<?= base_url('paket/') ?>" class="btn btn-ghost">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?= $error ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Nama Paket *</label>
                <input type="text" name="nama" class="form-control"
                    value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" placeholder="Contoh: Pesona Bali 4D3N"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"
                    placeholder="Jelaskan detail paket wisata..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Harga (Rp) *</label>
                    <input type="number" name="harga" class="form-control"
                        value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" placeholder="3500000" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi</label>
                    <input type="text" name="durasi" class="form-control"
                        value="<?= htmlspecialchars($_POST['durasi'] ?? '') ?>" placeholder="Contoh: 4 Hari 3 Malam">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Lokasi</label>
                <input type="text" name="lokasi" class="form-control"
                    value="<?= htmlspecialchars($_POST['lokasi'] ?? '') ?>" placeholder="Contoh: Bali, Indonesia">
            </div>

            <div class="form-group">
                <label class="form-label">Foto Paket</label>
                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="form-hint">Format: JPG, PNG, WebP. Maksimal 2MB</small>
            </div>

            <div class="d-flex gap-1" style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="<?= base_url('paket/') ?>" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include '../views/admin_footer.php'; ?>