<?php
$pageTitle   = 'Detail Paket - TravelDNE';
$currentPage = 'paket';
require_once 'views/header.php';

/* ================= VALIDASI ID ================= */
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: ' . base_url('paket.php'));
    exit;
}

/* ================= AMBIL DATA ================= */
$stmt = $pdo->prepare("SELECT * FROM paket WHERE id = ?");
$stmt->execute([$id]);
$paket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paket) {
    header('Location: ' . base_url('paket.php'));
    exit;
}

/* ================= FOTO ================= */
$foto = $paket['foto'] && file_exists("uploads/packages/{$paket['foto']}")
    ? base_url("uploads/packages/{$paket['foto']}")
    : base_url("assets/img/default-paket.jpg");
?>

<section class="section section-lg paket-detail">
    <div class="container">

        <!-- HEADER -->
        <header class="section-header">
            <h2><?= htmlspecialchars($paket['nama']) ?></h2>
            <p class="subtitle">Paket wisata terbaik untuk liburan Anda</p>
        </header>

        <div class="detail-grid">

            <!-- FOTO -->
            <div class="detail-image">
                <img src="<?= $foto ?>"
                     alt="<?= htmlspecialchars($paket['nama']) ?>">
            </div>

            <!-- INFO -->
            <div class="detail-info">

                <div class="detail-price">
                    <?= formatRupiah($paket['harga']) ?>
                </div>

                <div class="detail-desc">
                    <?= nl2br(htmlspecialchars($paket['deskripsi'])) ?>
                </div>

                <ul class="detail-meta">
                    <li>
                        <i class="fas fa-clock"></i>
                        Durasi: <?= htmlspecialchars($paket['durasi'] ?? 'Flexible') ?>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        Destinasi: <?= htmlspecialchars($paket['lokasi'] ?? 'Indonesia') ?>
                    </li>
                </ul>

                <!-- ACTION -->
                <div class="detail-actions">
                    <?php if (isLoggedIn()): ?>
                        <a href="<?= base_url('booking/create.php?paket_id=' . $paket['id']) ?>"
                           class="btn btn-primary">
                            <i class="fas fa-ticket-alt"></i> Booking Sekarang
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('auth/login.php') ?>"
                           class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login untuk Booking
                        </a>
                    <?php endif; ?>

                    <a href="<?= base_url('paket.php') ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<?php require_once 'views/footer.php'; ?>
