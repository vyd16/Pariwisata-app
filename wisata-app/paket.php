<?php
$pageTitle = 'Paket Wisata - TravelDNE';
$currentPage = 'paket';
require_once 'views/header.php';

/* Ambil data paket */
$packages = [];
try {
    $stmt = $pdo->query("SELECT * FROM paket ORDER BY created_at DESC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
}
?>

<section class="section section-lg" id="paket">
    <div class="container">

        <!-- HEADER -->
        <div class="section-header">
            <h2>Paket <span class="text-primary">Wisata</span></h2>
            <p>Pilih paket wisata terbaik sesuai kebutuhan liburan Anda</p>
        </div>

        <!-- LIST PAKET -->
        <div class="package-grid">

            <?php if (count($packages) > 0): ?>
                <?php foreach ($packages as $pkg): ?>
                    <div class="card package-card">

                        <!-- FOTO -->
                        <img
                        src="<?= base_url('uploads/packages/' . trim($pkg['foto'])) ?>"
                        alt="<?= htmlspecialchars($pkg['nama']) ?>"
                        class="card-img"
                        >


                        <!-- BODY -->
                        <div class="card-body">
                            <h3><?= htmlspecialchars($pkg['nama']) ?></h3>
                            <p><?= truncate(strip_tags($pkg['deskripsi']), 100) ?></p>

                            <div class="card-price">
                                <?= formatRupiah($pkg['harga']) ?>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="card-footer">

                            <!-- DETAIL -->
                            <a href="<?= base_url('/detail.php?id=' . $pkg['id']) ?>"
                               class="btn btn-outline btn-sm">
                                Detail
                            </a>

                            <!-- BOOKING -->
                            <?php if (isLoggedIn()): ?>
                                <a href="<?= base_url('booking/create.php?paket_id=' . $pkg['id']) ?>"
                                   class="btn btn-primary btn-sm">
                                    Booking
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('/login.php') ?>"
                                   class="btn btn-primary btn-sm">
                                    Booking
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;opacity:.7">
                    Belum ada paket wisata tersedia.
                </p>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php require_once 'views/footer.php'; ?>
